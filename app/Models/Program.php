<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'aim',
        'message',
        'code',
        'goals',
        'credit'
    ];

    public function getInfo(): array
    {
        $programStandards = $this->standards()->get();
        // Initialize variables
        $standardsInfo = [];
        $programRatio = 0;
        $programFilesNumber = 0;
        $programUploadedFiles = 0;
        $numberOfStandards = count($programStandards); // Get the count of standards directly
        $programCoordinator = $this->users()->where('PC', true)->first();
        $numberOfTeachers = $this->users()->where('TS', true)->count();

        if ($numberOfStandards !== 0) {
            foreach ($programStandards as $standard) {
                $standardInfo = $standard->getStandardInfo();
                // Skip if standard info is zero
                if ($standardInfo === 0) {
                    continue;
                }
                $standardsInfo[$standard->title] = [
                    'id' => $standardInfo['id'],
                    'title' => $standardInfo['title'],
                    'StandardRatio' => $standardInfo['StandardRatio']
                ];
                // Aggregate file numbers
                $programFilesNumber += $standardInfo['NumberOfFiles'];
                $programUploadedFiles += $standardInfo['AcceptedFiles'];
                $programRatio += $standardInfo['StandardRatio'];
            }
            // Calculate program ratio
            $programRatio /= $numberOfStandards;
        }
        // Extract program coordinator name
        $programCoordinatorName = $programCoordinator ? $programCoordinator->name : 'Not associated yet!';

        return [
            'title' => $this->title,
            'Program Coordinator' => $programCoordinatorName,
            'numberOfTeachers' => $numberOfTeachers,
            'programRatio' => $programRatio,
            'standards' => $standardsInfo,
            'UploadedFiles' => $programUploadedFiles,
            'NumberOfFiles' => $programFilesNumber,
        ];
    }

    // Model Relationships
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'program_user');
    }

    public function standards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Standard::class);
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Course::class,'program_courses');
    }
}
