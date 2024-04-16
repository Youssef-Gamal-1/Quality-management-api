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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{

    public function store(StoreFormRequest $request, Program $program, Standard $standard, Indicator $indicator): FormResource|JsonResponse
    {

        try {
            $this->validateStandard($program,$standard);
            $validatedData = $request->validated();
            $path = null;
            if($request->hasFile('path'))
            {
                $file = $request->file('path');
                $path = $file->store('indicators', 'public');
            }
            $form = $indicator->forms()->create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'type' => $validatedData['type'],
                'status' => $validatedData['status'],
                'indicator_id' => $indicator->id,
                'path' => $path,
            ]);
            return new FormResource($form);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception],422);
        }
    }

    public function update(UpdateFormRequest $request, Program $program ,Standard $standard, Indicator $indicator, Form $form)
    {
        try {
            $this->validateStandard($program,$standard);
            $validatedData = $request->validated();
            $path = null;
            if($request->hasFile('path'))
            {
                $file = $request->file('path');
                $path = $file->store('indicators', 'public');
            }
            $validatedData['path'] = $path;
            $form = $indicator->forms()->findOrFail($form->id);
            $form->update($validatedData);

            return new FormResource($form);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'The requested resource not found!']);
        }
    }

    public function destroy( Program $program ,Standard $standard, Indicator $indicator, Form $form): \Illuminate\Http\JsonResponse
    {
        try {
            $this->validateStandard($program,$standard);
            $form = $indicator->forms()->findOrFail($form->id);
            $form->delete();
            return response()->json([
                'msg' => 'Form deleted'
            ]);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'The requested resource not found!']);
        }
    }
    public function download(string $id)
    {
        $file = Form::findOrFail($id);
        if(!$file)
        {
            return response()->json([
                'error' => 'File does not exist'
            ],404);
        }
        return response()->download(storage_path('app/public/'.$file->path));
    }

    private function validateStandard(Program $program, Standard $standard)
    {
        if($program->id !== $standard->program->id)
        {
            throw new \Exception('The requested resource not found!', 404);
        }
    }
}
