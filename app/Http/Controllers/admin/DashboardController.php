<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\UserCollection;
use App\Models\CourseFiles;
use App\Models\Degree;
use App\Models\Form;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $programsNeededData = [];
        foreach ($programs as $program) {
            $programsInfo[$program->title] = $program->getInfo();
            $programsNeededData[$program->title] = [
              'title' => $programsInfo[$program->title]['title'],
              'programRatio' => $programsInfo[$program->title]['programRatio'],
              'UploadedFiles' => $programsInfo[$program->title]['UploadedFiles'],
                'numberOfTeachers' => $programsInfo[$program->title]['numberOfTeachers']
            ];
        }
// Initialize an array to hold month names and file counts for all months of the year
        $formsDetails = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0
        ];
        $uploadedMonths = Form::select(DB::raw('MONTH(updated_at) as month, COUNT(*) as count'))
            ->whereNotNull('path')
            ->groupBy('month')
            ->get();
        foreach ($uploadedMonths as $uploadedMonth) {
            // Get the month name
            $monthName = date('F', mktime(0, 0, 0, $uploadedMonth->month, 1));
            $formsDetails[$monthName] = $uploadedMonth->count;
        }
        // Count files
        $degrees = Degree::count();
        $courseFiles = CourseFiles::count();
        $numberOfFiles = Form::count();
        $numberOfUploadedFiles = Form::where('uploaded',true)->count() + $degrees + $courseFiles;
        $numberOfAcceptedFiles = Form::where('status', true)->count() + $degrees + $courseFiles;
        // Calculate ratios
        $uploadedFilesRatio = ($numberOfFiles > 0) ? ($numberOfUploadedFiles / $numberOfFiles * 100) : 0;
        $acceptedFilesRatio = ($numberOfFiles > 0) ? ($numberOfAcceptedFiles / $numberOfFiles * 100) : 0;

        return response()->json([
            'latestUsers' => $latestUsers,
            'notActivatedUsers' => $notActivatedUsers,
            'programsInfo' => $programsNeededData,
            'acceptedFilesRatio' => $acceptedFilesRatio,
            'uploadedFilesRatio' => $uploadedFilesRatio,
            'formsDetails' => $formsDetails
        ], 200);
    }

}
