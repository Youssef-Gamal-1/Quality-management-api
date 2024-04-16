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
        return new StandardCollection(Standard::all());
    }

    public function store(StandardRequest $request,  Program $program)
    {
        $validatedData = $request->validated();
        $standardCoordinator = null;

        // Check if a user ID is provided
        if (isset($validatedData['user_id'])) {
            // Find the user by ID
            $standardCoordinator = User::findOrFail($validatedData['user_id']);

            // Check if the user already has a standard associated
            if ($standardCoordinator->SC === 1) {
                return response()->json([
                    'msg' => 'User already has a standard associated'
                ], 400);
            }
        }

        // Create a new standard instance with the validated data
        $standard = new Standard($validatedData);

        // Save the standard to the database
        $standard->save();

        // If a standard coordinator is provided, associate the user with the standard
        if ($standardCoordinator) {
            $standardCoordinator->SC = 1;
            $standardCoordinator->save();

            // Associate the standard with the user
            $standard->user()->associate($standardCoordinator);
        }

        // Return the newly created standard resource
        return new StandardResource($standard);
    }
    public function show(Program $program ,Standard $standard)
    {
        return new StandardResource($standard);
    }

    public function update(Request $request, Program $program , Standard $standard)
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
                if ($user->SC === 1) {
                    return response()->json([
                        'msg' => 'User already has a standard associated'
                    ], 400);
                }

                // If the standard is currently associated with a user
                if ($standard->user) {
                    // Reset the SC flag of the current user
                    $standard->user->SC = 0;
                    $standard->user->save();
                }

                // Associate the standard with the new user and set SC flag to 1
                $standard->user()->associate($user);
                $user->SC = 1;
                $user->save();
            }
        }

        // Update the standard with the validated data
        $standard->update($validatedData);

        return new StandardResource($standard);
    }

    public function destroy(Program $program ,Standard $standard)
    {
        if ($standard->user) {
            $standard->user->SC = 0;
            $standard->user->save();
        }

        $standard->delete();

        return response()->json([
            'msg' => 'Standard deleted successfully!'
        ]);
    }
}
