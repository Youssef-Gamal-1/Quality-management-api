<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    public function __invoke()
    {
        $qCount = User::where('QM', true)->count();
        $teacherCount = User::where('TS', true)->count();
        $standardCoordinatorCount = User::where('SC', true)->count();
        $programCoordinatorCount = User::where('PC', true)->count();
        $studentCount = User::where('ST', true)->count();
        $evaluatorCount = User::where('EC', true)->count();

        return response()->json([
            'data' => [
                'SC_count' => $standardCoordinatorCount,
                'TS_count' => $teacherCount,
                'PC_count' => $programCoordinatorCount,
                'ST_count' => $studentCount,
                'QM_count' => $qCount,
                'EC_count' => $evaluatorCount,
            ]
        ]);

    }
}
