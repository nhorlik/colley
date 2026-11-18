<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function current(Request $request)
    {
        $plan = WorkoutPlan::query()
            ->where('felhasznalo_id', $request->user()->felhasznalo_id)
            ->where('aktiv', 1)
            ->with(['days.exercises'])
            ->orderByDesc('letrehozva')
            ->first();

        if (!$plan) {
            return ApiResponse::success(['plan' => null]);
        }

        return ApiResponse::success([
            'plan' => [
                'id' => $plan->edzesterv_id,
                'nev' => $plan->nev,
                'kezdo_datum' => $plan->kezdo_datum,
                'aktiv' => (bool) $plan->aktiv,
                'letrehozva' => $plan->letrehozva,
                'days' => $plan->days->map(fn ($day) => [
                    'id' => $day->edzesnap_id,
                    'nap_sorszam' => $day->nap_sorszam,
                    'nev' => $day->nev,
                    'exercises' => $day->exercises->map(fn ($exercise) => [
                        'id' => $exercise->gyakorlat_id,
                        'nev' => $exercise->nev,
                        'tipus' => $exercise->tipus,
                        'leiras' => $exercise->leiras,
                        'beallitasok' => [
                            'sorrend' => $exercise->pivot->sorrend,
                            'szett_szam' => $exercise->pivot->szett_szam,
                            'ism_min' => $exercise->pivot->ism_min,
                            'ism_max' => $exercise->pivot->ism_max,
                            'ido_mp' => $exercise->pivot->ido_mp,
                            'piheno_mp' => $exercise->pivot->piheno_mp,
                        ],
                    ]),
                ]),
            ],
        ]);
    }

    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'focus_muscle_ids' => ['required', 'array', 'min:1', 'max:5'],
            'focus_muscle_ids.*' => ['required', 'integer', 'distinct', 'exists:izomcsoport,izomcsoport_id'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Érvénytelen fókuszizomcsoportok.', 422, $validator->errors()->toArray());
        }

        // A tervgenerálás egészségügyi kapuja és kiválasztási algoritmusa
        // a forrásadatokhoz igazított külön implementációt igényel.
        // Addig nem mentünk új tervet, és nem inaktiválunk meglévő tervet.
        return ApiResponse::error(
            'Az edzésterv-generálás még nincs aktiválva. A biztonságos egészségügyi szűrés implementálása szükséges.',
            501
        );
    }
}
