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
        $requestStandard = null;
        $program = null;
        $programs = null;

        if(isset($data['standard_id']))
        {
            $requestStandard = Standard::findOrFail($data['standard_id']);
            if($requestStandard->user()->exists())
            {
                return response()->json([
                    "error" => 'Standard already has a coordinator'
                ], 422); // Use 422 status code for validation errors
            }
            unset($data['standard_id']);
        }
        if(isset($data['program_id']))
        {
            $program = Program::findOrFail($data['program_id']);
            if($program->users()->exists() && $program->users()->first()->PC === 1)
            {
                return response()->json([
                    "error" => 'Program already has a coordinator'
                ], 422); // Use 422 status code for validation errors
            }
            unset($data['program_id']);
        }
        if(isset($data['programs']))
        {
            $programs = $data['programs'];
            unset($data['programs']);
        }

        $user = User::create($data);
        if($requestStandard)
        {
            $standards = Standard::where('title',$requestStandard->title)->get();
            foreach($standards as $standard)
            {
                $standard->user_id = $user->id;
                $standard->save();
            }
        };
        if($program) $user->programs()->sync($program->id);
        if($programs)
        {
            foreach($programs as $programId)
            {
                $user->programs()->attach($programId);
            }
        }

        return response()->json([
            'success' => 'User created successfully',
            'user' => new UserResource($user)
        ], 200);
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

        $requestStandard = null;
        $program = null;
        $programs = null;

        if(isset($data['standard_id']))
        {
            $requestStandard = Standard::findOrFail($data['standard_id']);
            if($requestStandard->user()->exists())
            {
                return response()->json([
                    "msg" => 'Standard already has a coordinator'
                ]);
            }

            $standards = Standard::where('title',$requestStandard->title);
            foreach($standards as $standard)
            {
                $standard->user_id = $user->id;
                $standard->save();
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

            $user->programs()->sync($program->id);
            unset($data['program_id']);
        }

        if(isset($data['programs']))
        {
            $programs = $data['programs'];
            unset($data['programs']);
        }

        $user->update($data);

        return response()->json([
            'success' => 'User data updated successfully!',
            'user' => new UserResource($user)
        ], 200);
    }


    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => 'User deleted successfully!'
        ], 200);
    }
}
