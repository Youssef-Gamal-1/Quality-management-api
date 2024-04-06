<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\program\ProgramCollection;
use App\Http\Requests\Program\StoreRequest;
use App\Http\Requests\Program\UpdateRequest;
use App\Http\Resources\admin\program\ProgramResource;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class ProgramController extends Controller
{

    public function index()
    {
        $programs = Program::all();

        return new ProgramCollection($programs);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        // Check if user_id is provided
        if (!isset($data['user_id'])) {
            return response()->json([
                'msg' => 'User id not provided'
            ], 400);
        }

        // Find a valid user based on the provided user_id and conditions
        $validUser = User::where('id', $data['user_id'])
            ->where(function ($query) {
                $query->where('PC', 1)
                    ->orWhere('TS', 1);
            })
            ->first();

        // If no valid user is found, return an error response
        if (!$validUser) {
            return response()->json([
                'msg' => 'Not a valid user'
            ], 400);
        }

        // Create a new program and sync the user_id with the program's users relationship
        $program = Program::create($data);
        $program->users()->sync([$validUser->id]);

        return new ProgramResource($program);
    }

    public function show(Program $program)
    {
        return new ProgramResource($program);
    }

    public function update(UpdateRequest $request, Program $program)
    {
        $data = $request->validated();
        $validUser = '';
        if(isset($data['user_id']))
        {
            $validUser = User::where('id',$data['user_id'])
                ->where('PC',1)
                ->orWhere('TS',1)
                ->first();
            if(!$validUser)
            {
                return response()->json([
                    'msg' => 'Not valid user'
                ], 400);
            }
        }

        $program->update($data);
        if($validUser !== '')
        {
            $program->users()->sync([$validUser->id]);
        }
        return new ProgramResource($program);
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return response()->json([
            'msg' => 'Program deleted successfully!'
        ]);
    }
}
