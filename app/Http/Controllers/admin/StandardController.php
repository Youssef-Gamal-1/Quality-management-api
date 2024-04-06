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
    public function index()
    {
        return new StandardCollection(Standard::all());
    }

    public function store(StandardRequest $request, Program $program)
    {
        $validatedData = $request->validated();

        if (!isset($validatedData['user_id'])) {
            return response()->json([
                'msg' => 'You must specify the user for the standard'
            ], 400);
        }

        $user = User::findOrFail($validatedData['user_id']);
        // Check if the user already has a standard associated
        if ($user->standard()->exists()) {
            return response()->json([
                'msg' => 'User already has a standard associated'
            ], 400);
        }
        // Create the standard associated with the program and user
        $standard = new Standard($validatedData);
        $standard->program()->associate($program);
        $standard->user()->associate($user);
        $standard->save();

        return new StandardResource($standard);
    }


    public function show(Standard $standard)
    {
        return new StandardResource($standard);
    }

    public function update(Request $request, Program $program, Standard $standard)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'sometimes|string|required|max:255',
            'user_id' => 'sometimes|required|exists:users,id'
        ]);

        // Check if a user_id is provided in the request
        if (isset($validatedData['user_id'])) {
            // Find the corresponding User model
            $user = User::findOrFail($validatedData['user_id']);

            // If the new user is different from the current one associated with the standard
            if ($user->id !== $standard->user_id) {
                // Check if the new user already has a standard associated
                if ($user->standard()->exists()) {
                    return response()->json([
                        'msg' => 'User already has a standard associated'
                    ], 400);
                }
            }

            // Associate the standard with the new user
            $standard->user()->associate($user);
        }

        // Update the standard with the validated data
        $standard->update($validatedData);

        return new StandardResource($standard);
    }

    public function destroy(Standard $standard)
    {
        $standard->delete();

        return response()->json([
            'msg' => 'Standard deleted successfully!'
        ]);
    }
}
