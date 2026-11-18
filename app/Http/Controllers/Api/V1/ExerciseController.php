<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'izomcsoport_id' => ['sometimes', 'integer', 'exists:izomcsoport,izomcsoport_id'],
            'otthon_vegezheto' => ['sometimes', 'boolean'],
            'tipus' => ['sometimes', 'string', 'max:12'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Érvénytelen szűrési paraméterek.', 422, $validator->errors()->toArray());
        }

        $query = Exercise::query()
            ->where('aktiv', 1)
            ->with(['muscleGroups' => function ($q) {
                $q->select('izomcsoport.izomcsoport_id', 'kod', 'nev');
            }]);

        if ($request->filled('izomcsoport_id')) {
            $query->whereHas('muscleGroups', fn ($q) =>
                $q->where('izomcsoport.izomcsoport_id', $request->integer('izomcsoport_id'))
            );
        }

        if ($request->has('otthon_vegezheto')) {
            $query->where('otthon_vegezheto', $request->boolean('otthon_vegezheto'));
        }

        if ($request->filled('tipus')) {
            $query->where('tipus', $request->input('tipus'));
        }

        $items = $query
            ->orderBy('nev')
            ->get([
                'gyakorlat_id', 'kod', 'nev', 'tipus', 'mozgasminta',
                'eszkoz', 'otthon_vegezheto', 'nehezseg', 'terapias',
                'alap_szett', 'alap_ism_min', 'alap_ism_max',
                'alap_ido_mp', 'alap_piheno_mp', 'leiras',
            ]);

        return ApiResponse::success($items);
    }
}
