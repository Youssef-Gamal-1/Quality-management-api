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

class StandardController extends Controller
{
    public function index(Program $program)
    {
        $standards = $program->standards;
        return new StandardCollection($standards);
    }

    public function store(StandardRequest $request,  Program $program)
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
        $validatedData['program_id'] = $program->id;
        $standard = Standard::create($validatedData);

        if ($standardCoordinator) {
            $standardCoordinator->SC = 1;
            $standardCoordinator->save();

            $standard->user()->associate($standardCoordinator);
        }

        return new StandardResource($standard);
    }
    public function show(Program $program ,Standard $standard)
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

            if (isset($validatedData['user_id'])) {
                $user = User::findOrFail($validatedData['user_id']);

                if ($user->id !== $standard->user_id) {
                    if ($user->SC === 1) {
                        return response()->json([
                            'msg' => 'User already has a standard associated'
                        ], 400);
                    }
                    if ($standard->user) {
                        $standard->user->SC = 0;
                        $standard->user->save();
                    }

                    $standard->user()->associate($user);
                    $user->SC = 1;
                    $user->save();
                }
            }

            $standard->update($validatedData);
            return new StandardResource($standard);
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
            $standard->delete();
            return response()->json([
                'msg' => 'Standard deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    private function validateStandard(Program $program, Standard $standard)
    {
        if($program->id !== $standard->program->id)
        {
            throw new \Exception('Standard does not belong to the specified program.', 404);
        }
    }
}

