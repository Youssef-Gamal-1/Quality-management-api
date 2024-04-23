<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Standard\StandardRequest;
use App\Http\Resources\standard\StandardCollection;
use App\Http\Resources\standard\StandardResource;
use App\Models\Program;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Exception;

class StandardController extends Controller
{
    public function index(Program $program)
    {
        $standards = $program->standards;
        return new StandardCollection($standards);
    }

    public function store(StandardRequest $request, Program $program)
    {
        $validatedData = $request->validated();
        $standardCoordinator = null;
        // Check if a user ID is provided
        if (isset($validatedData['user_id'])) {
            $standardCoordinator = User::findOrFail($validatedData['user_id']);
            // Check if the user already has a standard associated
            if ($standardCoordinator->SC === 1) {
                return response()->json([
                    'msg' => 'User already has a standard associated'
                ], 400);
            }
        }
        $programs = Program::all();
        foreach($programs as $newProgram) {
            $validatedData['program_id'] = $newProgram->id;
            $standard = Standard::create($validatedData);
            if ($standardCoordinator) {
                $standardCoordinator->SC = 1;
                $standardCoordinator->save();

                $standard->user()->associate($standardCoordinator);
            }
        }
        return new StandardResource($program->standards()->latest()->first());
    }
    public function show(Program $program ,Standard $standard) // route model binding
    {
        try {
            $this->validateStandard($program,$standard);
            return new StandardResource($standard);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Program $program , Standard $standard)
    {
        try {
            $this->validateStandard($program,$standard);
            $validatedData = $request->validate([
                'title' => 'sometimes|string|required|max:255',
                'user_id' => 'sometimes|required|exists:users,id'
            ]);
            $standardTitle = $standard->title;
            $standards = Standard::where('title',$standardTitle)->get();
            foreach($standards as $newStandard) {
                if (isset($validatedData['user_id'])) {
                    $user = User::findOrFail($validatedData['user_id']);
                    if ($user->id !== $newStandard->user_id) {
                        if ($user->SC === 1) {
                            return response()->json([
                                'msg' => 'User already has a standard associated'
                            ], 400);
                        }
                        if ($newStandard->user) {
                            $newStandard->user->SC = 0;
                            $newStandard->user->save();
                        }
                        $newStandard->user()->associate($user);
                        $newStandard->SC = 1;
                        $newStandard->save();
                    }
                }
                $newStandard->update($validatedData);
            }


            return response()->json(['success' => 'Standard updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Program $program ,Standard $standard)
    {
        try {
            $this->validateStandard($program,$standard);
            if ($standard->user) {
                $standard->user->SC = 0;
                $standard->user->save();
            }
            // loop on all programs based on the standard title to delete them
            $standardTitle = $standard->title;
            $programs = Program::all();
            foreach($programs as $newProgram) {
                $newProgram->standards()->where('title',$standardTitle)->delete();
            }
            return response()->json([
                'msg' => 'Standard deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getReport(Program $program, Standard $standard): \Illuminate\Http\JsonResponse
    {
        try{
            $this->validateStandard($program, $standard);
            return response()->json($standard->getStandardInfo());
        } catch (Exception $e) {
            return response()->json(['fail' => $e->getMessage()], 400);
        }

    }
    private function validateStandard(Program $program, Standard $standard): void
    {
        if($program->id !== $standard->program->id)
        {
            throw new \Exception('Standard does not belong to the specified program.', 404);
        }
    }
}

