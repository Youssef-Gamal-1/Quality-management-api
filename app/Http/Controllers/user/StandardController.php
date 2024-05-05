<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\Standard\StandardRequest;
use App\Http\Resources\standard\StandardCollection;
use App\Http\Resources\standard\StandardResource;
use App\Models\Program;
use App\Models\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Standard::class,'standard');
    }

    public function index(Program $program)
    {
        $standards = $program->standards;

        return new StandardCollection($standards);
    }
    public function show(Program $program, Standard $standard)
    {
        $standard = $program->standards()->findOrFail($standard->id);
        $this->authorize('view', $standard);

        return new StandardResource($standard);
    }

    public function update(Request $request, Program $program, Standard $standard)
    {
        $standard = $program->standards()->findOrFail($standard->id);
        $validated = $request->validate(['title' => 'sometimes|string|max:255|min:5']);
        $standards = Standard::where('title',$standard->title)->get();
        foreach($standards as $st)
        {
            $st->update($validated);
        }

        return new StandardResource(Standard::where('id',$standard->id)->first());
    }

}
