<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\Program\UpdateRequest;
use App\Http\Resources\admin\program\ProgramCollection;
use App\Http\Resources\admin\program\ProgramResource;
use App\Models\Program;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct()
    {
         $this->authorizeResource(Program::class,'program');
    }

    public function index()
    {
        $programs = Program::all();

        return new ProgramCollection($programs);
    }
    public function show(Program $program)
    {
//        $this->authorize('view', $program);

        return new ProgramResource($program);
    }

    public function update(UpdateRequest $request,Program $program)
    {
        $this->authorize('update', $program);
        $validated = $request->validated();

        $program->update();

        return new ProgramResource($program);
    }

}
