<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\indicator\IndicatorCollection;
use App\Http\Resources\admin\indicator\IndicatorResource;
use App\Models\Indicator;
use App\Models\Program;
use App\Models\Standard;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Indicator::class, 'indicator');
    }

    public function index(Program $program, Standard $standard)
    {
        if($program->id !== $standard->program->id)
        {
            throw new \Exception('Standard does not belong to the specified program.', 404);
        }
        $indicators = $standard->indicators;

        return response()->json($indicators, 200);
    }
    public function show(Program $program, Standard $standard, Indicator $indicator)
    {
        if($program->id !== $standard->program->id)
        {
            throw new \Exception('Standard does not belong to the specified program.', 404);
        }
        $indicator = $standard->indicators()->findOrFail($indicator->id);

        return new IndicatorResource($indicator);
    }

}
