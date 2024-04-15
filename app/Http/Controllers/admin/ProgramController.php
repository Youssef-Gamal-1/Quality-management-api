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
        $programCoordinator = null;
        // Check if user_id is provided
        if (isset($data['user_id'])) {
            $programCoordinator = User::findOrFail($data['user_id']);
            if($programCoordinator->PC === 1 &&
                $programCoordinator->programs()->exists()
            )
            {
                return response()->json([
                    'msg' => 'User already associated with a program!'
                ]);
            }
        }
        // Create a new program and sync the user_id with the program's users relationship
        $program = Program::create($data);
        if($programCoordinator)
        {
            $program->users()->sync([$programCoordinator->id]);
            $programCoordinator->update(['PC' => 1]);
        }

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
            $validUser = User::findOrFail($data['user_id']);
            if($validUser->PC === 1)
            {
                return response()->json([
                    'msg' => 'User already associated with a program!'
                ]);
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
