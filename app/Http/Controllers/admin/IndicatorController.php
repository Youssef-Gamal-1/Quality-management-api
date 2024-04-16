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

    public function index(Program $program ,Standard $standard)
    {
        try {
            $this->validateStandard($program, $standard);
            $indicators = $standard->indicators()->paginate(1);
            return new IndicatorCollection($indicators);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request,  Program $program ,Standard $standard)
    {
        try {
            $this->validateStandard($program, $standard);
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'number' => 'required|integer|min:1',
            ]);

            $validatedData['standard_id'] = $standard->id;
            $indicator = Indicator::create($validatedData);

            return new IndicatorResource($indicator);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function show(Program $program ,Standard $standard, Indicator $indicator)
    {
        try {
            $indicator = $standard->indicators()->findOrFail($indicator->id);
            return new IndicatorResource($indicator);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function update(Request $request,  Program $program ,Standard $standard, Indicator $indicator)
    {
        try {
            $this->validateStandard($program, $standard);
            $validatedData = $request->validate([
                'title' => 'sometimes|string|max:255',
                'number' => 'sometimes|integer|min:1',
            ]);

            $validatedData['standard_id'] = $standard->id;
            $indicator->update($validatedData);

            return new IndicatorResource($indicator);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

    }

    public function destroy(Program $program ,Standard $standard, Indicator $indicator)
    {
        try {
            $this->validateStandard($program, $standard);
            $indicators = $standard->indicators()->findOrFail($indicator->id);
            $indicator->delete();

            return response()->json([
                'msg' => 'Indicator successfully deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    private function validateStandard(Program $program, Standard $standard)
    {
        if($program->id !== $standard->program->id)
        {
            throw new \Exception('The requested resource not found!', 404);
        }
    }
}
