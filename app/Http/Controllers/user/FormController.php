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
        $validatedData = $request->validate ([
            "value" => "sometimes|max:255|min:3",
            "status" => "sometimes|boolean",
            'uploaded' => "sometimes|boolean",
            "user_id" => "required|exists:users,id"
        ]);

        $indicator = $standard->indicators()->findOrFail($indicator->id);
        $form = $indicator->forms()->findOrFail($form->id);
        // handle refusing the file
        if (isset($validatedData['status']) && !$validatedData['status']) {
            $validatedData['value'] = null;
        }

        $form->update($validatedData);
        // assign form to user
        $form->users()->sync($validatedData['user_id']);
        return new FormResource($form);
    }

    public function uploadFile(Request $request, Program $program, Standard $standard ,Indicator $indicator, Form $form)
    {
        if($program->id !== $standard->program->id)
        {
            abort(404);
        }
        $validatedData = $request->validate ([
            "path" => "required|file|mimes:jpeg,jpg,png,docx,pdf|max:2048",
            "user_id" => "required|exists:users,id",
            "status" => "sometimes|boolean",
        ]);
        $indicator = $standard->indicators()->findOrFail($indicator->id);
        $form = $indicator->forms()->findOrFail($form->id);
        // handle refusing the file
        if ($form->path !== null && $form->path !== '') {
            Storage::disk('public')->delete($form->path);
        }
        if (isset($validatedData['status']) && !$validatedData['status']) {
            $validatedData['path'] = null;
        }
        if($request->hasFile('path'))
        {
            $file = $request->file('path');
            $path = $file->store('indicators', 'public');
            $validatedData['path'] = $path;
        }
        $validatedData['uploaded'] = true;
        $form->update($validatedData);
        // assign form to user
        $form->users()->sync($validatedData['user_id']);
        return new FormResource($form);
    }

    public function quickUpload(Request $request, Indicator $indicator, Form $form)
    {

        $validatedData = $request->validate ([
            "path" => "required|file|mimes:jpeg,jpg,png,docx,pdf|max:2048",
            "user_id" => "required|exists:users,id",
            "status" => "sometimes|boolean",
        ]);

        $form = $indicator->forms()->findOrFail($form->id);

        if ($form->path !== null && $form->path !== '') {
            Storage::disk('public')->delete($form->path);
        }
        if (isset($validatedData['status']) && !$validatedData['status']) {
            $validatedData['path'] = null;
        }
        if($request->hasFile('path'))
        {
            $file = $request->file('path');
            $path = $file->store('indicators', 'public');
            $validatedData['path'] = $path;
        }
        $validatedData['uploaded'] = true;
        $form->update($validatedData);
        // assign form to user
        $form->users()->sync($validatedData['user_id']);
        return response()->json(['data' => $form]);

    }

    public function quickUpdate(Request $request, Indicator $indicator, Form $form)
    {
        $validatedData = $request->validate ([
            "value" => "sometimes|max:255|min:3",
            "status" => "sometimes|boolean",
            "user_id" => "required|exists:users,id"
        ]);
        $form = $indicator->forms()->findOrFail($form->id);
        // handle refusing the file
        if (isset($validatedData['status']) && !$validatedData['status']) {
            $validatedData['value'] = null;
        }

        $form->update($validatedData);
        // assign form to user
        $form->users()->sync($validatedData['user_id']);
        return new FormResource($form);
    }

    public function getForms(Form $form)
    {
        return response()->json(['data' => $form]);
    }
}
