<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\indicator\IndicatorCollection;
use App\Http\Resources\admin\indicator\IndicatorResource;
use App\Models\Indicator;
use App\Models\Program;
use App\Models\Standard;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{

    public function index()
    {
        return new IndicatorCollection(Indicator::all());
    }

    public function store(Request $request, Program $program, Standard $standard)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'number' => 'required|integer|min:1',
        ]);

        $validatedData['standard_id'] = $standard->id;
        $indicator = Indicator::create($validatedData);

        return new IndicatorResource($indicator);
    }


    public function show(Program $program, Standard $standard, Indicator $indicator)
    {
        return new IndicatorResource($indicator);
    }

    public function update(Request $request, Program $program, Standard $standard, Indicator $indicator)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'number' => 'sometimes|integer|min:1',
        ]);

        $validatedData['standard_id'] = $standard->id;
        $indicator->update($validatedData);

        return new IndicatorResource($indicator);

    }

    public function destroy(Program $program, Standard $standard, Indicator $indicator)
    {
        $indicator->delete();

        return response()->json([
            'msg' => 'Indicator successfully deleted'
        ]);
    }
}
