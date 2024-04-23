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
        $standardsInfo = [];
        $programRatio = 0;
        $programFilesNumber = 0;
        $programUploadedFiles = 0;
        $numberOfStandards = 0;
        foreach($programStandards as $standard){
            $standardsInfo[$standard->title] = $standard->getStandardInfo();
            $programFilesNumber += $standardsInfo[$standard->title]['NumberOfFiles'];
            $programUploadedFiles += $standardsInfo[$standard->title]['UploadedFiles'];
            $programRatio += $standardsInfo[$standard->title]['StandardRatio'];
            $numberOfStandards++;
        }
        $programRatio = $programRatio / $numberOfStandards;
        $programCoordinator = $this->users()->where('PC',true)->first() ?? 'Not associated yet!';
        return [
            'title' => $this->title,
            'Program Coordinator' => $programCoordinator,
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
