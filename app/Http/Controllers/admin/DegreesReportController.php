<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DegreesCollection;
use App\Models\Course;
use App\Models\Degree;
use Illuminate\Http\Request;

class DegreesReportController extends Controller
{
    public function __invoke()
    {
        $ratios = [];

        for ($i = 2019; $i <= 2024; $i++) {
            $degrees = Degree::where('year', $i)->pluck('success_ratio');
            $sum = $degrees->sum(); 
            $count = $degrees->count();
            $average = $count > 0 ? $sum / $count : 0;
            $ratios[$i] = $average;
        }

        return response()->json(['data' => $ratios]);
    }



}
