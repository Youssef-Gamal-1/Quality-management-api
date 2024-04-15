<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\admin\UserCollection;
use App\Http\Resources\admin\UserResource;
use App\Models\Program;
use App\Models\Standard;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        $filters = request()->only([
           'search',
            'role'
        ]);
        $users = User::latest()->filter($filters)->paginate();

        return new UserCollection($users);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        unset($data['confirm-password']);
        $standard = null;
        $program = null;
        $programs = null;
        if(isset($data['standard_id']))
        {
            $standard = Standard::findOrFail($data['standard_id']);
            if($standard->user()->exists())
            {
                return response()->json([
                    "msg" => 'Standard already has a coordinator'
                ]);
            }
            unset($data['standard_id']);
        }
        if(isset($data['program_id']))
        {
            $program = Program::findOrFail($data['program_id']);
            if($program->users()->exists() && $program->users()->first()->PC === 1)
            {
                return response()->json([
                    "msg" => 'Program already has a coordinator'
                ]);
            }
            unset($data['program_id']);
        }
        if(isset($data['programs']))
        {
            $programs = $data['programs'];
            unset($data['programs']);
        }

        $user = User::create($data);
        if($standard) $user->standard_id = $standard->id;
        if($program) $user->programs()->sync($program->id);
        if($programs)
        {
            foreach($programs as $program)
            {
                $user->programs()->sync($program->id);
            }
        }
        return response()->json([
            'msg' => 'User created successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        // Remove confirm-password field if present
        if(isset($data['confirm-password']))
        {
            unset($data['confirm-password']);
        }

        // Initialize variables for related models
        $standard = null;
        $program = null;
        $programs = null;

        // Handle standard coordinator assignment
        if(isset($data['standard_id']))
        {
            $standard = Standard::findOrFail($data['standard_id']);

            // Check if the standard already has a coordinator
            if($standard->user()->exists())
            {
                return response()->json([
                    "msg" => 'Standard already has a coordinator'
                ]);
            }

            // Assign the standard to the user
            $user->standard_id = $standard->id;
            unset($data['standard_id']);
        }

        // Handle program coordinator assignment
        if(isset($data['program_id']))
        {
            $program = Program::findOrFail($data['program_id']);

            // Check if the program already has a coordinator
            if($program->users()->exists() && $program->users()->first()->PC === 1)
            {
                return response()->json([
                    "msg" => 'Program already has a coordinator'
                ]);
            }

            // Sync the program with the user
            $user->programs()->sync($program->id);
            unset($data['program_id']);
        }

        // Handle multiple program coordinators assignment
        if(isset($data['programs']))
        {
            $programs = $data['programs'];
            unset($data['programs']);
        }

        // Update the user data
        $user->update($data);

        // Return response
        return response()->json([
            'msg' => 'User data updated successfully',
            'user' => new UserResource($user)
        ]);
    }


    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'msg' => 'User deleted successfully!'
        ]);
    }
}
