<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return ApiResponse::success([
            'id' => $user->felhasznalo_id,
            'nev' => $user->nev,
            'email' => $user->email,
            'cel' => $user->cel,
            'szint' => $user->szint,
            'testalkat' => $user->testalkat,
            'nem' => $user->nem,
            'szuletesi_ev' => $user->szuletesi_ev,
            'magassag_cm' => $user->magassag_cm,
            'testsuly_kg' => $user->testsuly_kg,
            'heti_edzesszam' => $user->heti_edzesszam,
            'otthon_edz' => (bool) $user->otthon_edz,
            'rehab_mod' => (bool) $user->rehab_mod,
        ]);
    }
}
