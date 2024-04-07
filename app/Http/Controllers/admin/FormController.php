<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;
use App\Http\Resources\form\FormResource;
use App\Models\Form;
use App\Models\Indicator;
use App\Models\Program;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{

    public function store(StoreFormRequest $request, Program $program, Standard $standard, Indicator $indicator): FormResource
    {
        $request->validated();
        $path = null;
        if($request->hasFile('path'))
        {
            $file = $request->file('path');
            $path = $file->store('indicators', 'public');
        }
        $form = Form::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status,
            'indicator_id' => $indicator->id,
            'path' => $path,
        ]);

        return new FormResource($form);

    }

    public function update(UpdateFormRequest $request, Program $program, Standard $standard, Indicator $indicator, Form $form): FormResource
    {
        $validatedData = $request->validated();
        $path = null;
        if($request->hasFile('path'))
        {
            $file = $request->file('path');
            $path = $file->store('indicators', 'public');
        }
        $validatedData['path'] = $path;
        $form->update($validatedData);

        return new FormResource($form);
    }

    public function destroy(Program $program, Standard $standard, Indicator $indicator, Form $form): \Illuminate\Http\JsonResponse
    {
        $form->delete();

        return response()->json([
            'msg' => 'Form deleted'
        ]);
    }
    public function download(string $id)
    {
        $file = Form::find($id);
        if(!$file)
        {
            return response()->json([
                'error' => 'File does not exist'
            ],404);
        }
        return response()->download(storage_path('app/public/'.$file->path));
    }

}
