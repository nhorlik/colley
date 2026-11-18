<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MuscleGroup;
use App\Support\ApiResponse;

class MuscleGroupController extends Controller
{
    public function index()
    {
        $items = MuscleGroup::query()
            ->select([
                'izomcsoport_id', 'kod', 'nev',
                'testoldal', 'mesh_nev', 'testterulet_id',
            ])
            ->orderBy('sorrend')
            ->get();

        return ApiResponse::success($items);
    }
}
