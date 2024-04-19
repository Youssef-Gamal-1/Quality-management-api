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
            $indicatorTitle = $indicator->title;
            $indicators = Indicator::where('title',$indicatorTitle)->get();

            foreach($indicators as $newIndicator){
                $newIndicator->forms()->create([
                    'title' => $validatedData['title'],
                    'type' => $validatedData['type']
                ]);
            }

            return new FormResource($indicator->forms()->latest()->first());
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception],422);
        }
    }

    public function update(UpdateFormRequest $request, Program $program ,Standard $standard, Indicator $indicator, Form $form)
    {
        try {
            $this->validateStandard($program,$standard);
            $validatedData = $request->validated();
//            $path = null;
//            if($request->hasFile('path'))
//            {
//                $file = $request->file('path');
//                $path = $file->store('indicators', 'public');
//            }
//            $validatedData['path'] = $path;
            $formTitle = $form->title;
            $forms = Form::where('title',$formTitle)->get();
            foreach($forms as $newForm){
                $newForm->update($validatedData);
            }

            return response()->json(['success' => 'Form updated successfully!'],200);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'The requested resource not found!']);
        }
    }

    public function destroy( Program $program ,Standard $standard, Indicator $indicator, Form $form): \Illuminate\Http\JsonResponse
    {
        try {
            $this->validateStandard($program,$standard);
            $formTitle = $form->title;
            $forms = Form::where('title',$formTitle)->get();
            foreach($forms as $newForm){
                $newForm->delete();
            }
            return response()->json([
                'success' => 'Form deleted successfully!'
            ], 200);
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
