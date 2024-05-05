<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\form\FormResource;
use App\Models\Form;
use App\Models\Indicator;
use App\Models\Program;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Form::class,'form');
    }

    public function update(Request $request, Program $program, Standard $standard, Indicator $indicator, Form $form)
    {
//        $this->authorize('update',$form);
        if($program->id !== $standard->program->id)
        {
            abort(404);
        }
//        return response($request->all());
        $validatedData = $request->validate ([
            "value" => "sometimes|max:255|min:3",
            "status" => "sometimes|boolean",
            "path" => "sometimes|file|mimes:jpeg,jpg,png,docx,pdf|max:2048",
        ]);
        $indicator = $standard->indicators()->findOrFail($indicator->id);
        $form = $indicator->forms()->findOrFail($form->id);
        if (!$validatedData['status']) {
            if ($form->path !== null && $form->path !== '') {
                Storage::disk('public')->delete($form->path);
            }
            $validatedData['path'] = null;
            $validatedData['value'] = null;
        } else {
            if($request->hasFile('path'))
            {
                $file = $request->file('path');
                $path = $file->store('indicators', 'public');
                $validatedData['path'] = $path;
            }
        }

        $form->update($validatedData);

        return new FormResource($form);
    }

}
