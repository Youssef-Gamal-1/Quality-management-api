<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\program\ProgramCollection;
use App\Http\Requests\Program\StoreRequest;
use App\Http\Requests\Program\UpdateRequest;
use App\Http\Resources\admin\program\ProgramResource;
use App\Models\Program;
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

        $program = Program::create($data);

        return new ProgramResource($program);
    }

    public function show(Program $program)
    {
        return new ProgramResource($program);
    }

    public function update(UpdateRequest $request, Program $program)
    {
        $data = $request->validated();
        $program->update($data);

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
