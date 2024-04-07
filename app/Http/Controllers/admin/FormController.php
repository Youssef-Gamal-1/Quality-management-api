<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Indicator;
use App\Models\Program;
use App\Models\Standard;
use Illuminate\Http\Request;

class FormController extends Controller
{

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Program $program, Standard $standard, Indicator $indicator, Form $form)
    {
        $form->delete();

        return response()->json([
            'msg' => 'Form deleted'
        ]);
    }
}
