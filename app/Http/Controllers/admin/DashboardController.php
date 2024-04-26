<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\UserCollection;
use App\Models\Form;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $latestUsers = User::select('id','name')
            ->where('QM', false)
            ->latest()
            ->take(3)
            ->get();
        $notActivatedUsers = User::select('id','name')
            ->where('activated', false)
            ->latest()
            ->take(3)
            ->get();
        $programs = Program::all();
        $programsInfo = [];
        foreach ($programs as $program) {
            $programsInfo[$program->title] = $program->getInfo();
        }
        // Count files
        $numberOfFiles = Form::count();
        $numberOfUploadedFiles = Form::whereNotNull('value')->count();
        $numberOfAcceptedFiles = Form::where('status', true)->count();
        // Calculate ratios
        $uploadedFilesRatio = ($numberOfFiles > 0) ? ($numberOfUploadedFiles / $numberOfFiles * 100) : 0;
        $acceptedFilesRatio = ($numberOfFiles > 0) ? ($numberOfAcceptedFiles / $numberOfFiles * 100) : 0;

        return response()->json([
            'latestUsers' => $latestUsers,
            'notActivatedUsers' => $notActivatedUsers,
            'programsInfo' => $programsInfo,
            'acceptedFilesRatio' => $acceptedFilesRatio,
            'uploadedFilesRatio' => $uploadedFilesRatio,
        ], 200);
    }

}
