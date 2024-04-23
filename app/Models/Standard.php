<?php

namespace App\Models;

use http\Env\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = ['title','user_id','program_id'];

    // function to calculate the finished reports inside the standard
    // It will help in generating reports
    function getStandardInfo(): array|int
    {
        $numberOfIndicators = $this->indicators()->count();
        if($numberOfIndicators === 0){
            return 0;
        }
        $standardIndicators = $this->indicators()->get();

        $standardIndicatorsFinished = 0;
        $indicatorsRatio = [];
        $numberOfFiles = 0;
        foreach($standardIndicators as $indicator){
            $indicatorsFinishedRatio[$indicator->title] = $indicator->getFinishedReportsRatio();
            $standardIndicatorsFinished += $indicatorsFinishedRatio[$indicator->title];
            $numberOfFiles += $indicator->forms()->count();
        }
        $standardRatio = $standardIndicatorsFinished / $numberOfIndicators;
        $standardCoordinator = $this->user ?? 'Not associated yet!';
        return [
            'title' => $this->title,
            'Standard Coordinator' => $standardCoordinator,
            'IndicatorsRatio' => $indicatorsRatio,
            'StandardRatio' => $standardRatio,
            'UploadedFiles' => $standardIndicatorsFinished,
            'NumberOfFiles' => $numberOfFiles
        ];
    }

    // Model Relationships
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Program::class);
    }


    public function indicators(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
