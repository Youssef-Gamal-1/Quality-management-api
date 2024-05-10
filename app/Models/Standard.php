<?php

namespace App\Models;

use http\Env\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = ['title','user_id','program_id','type'];

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
        $indicatorsFinishedRatio = [];
        $numberOfFiles = 0;
        $latestFiles = [];
        foreach($standardIndicators as $indicator){
            $indicatorsFinishedRatio[$indicator->id] = $indicator->getFinishedReportsRatio();
            $standardIndicatorsFinished += $indicatorsFinishedRatio[$indicator->id];
            $numberOfFiles += $indicator->forms()->count();
        }
        $standardRatio = $standardIndicatorsFinished / $numberOfIndicators;
        $standardCoordinator = $this->user->name ?? 'Not associated yet!';
        return [
            'id' => $this->id,
            'title' => $this->title, // standard title
            'program' => $this->program->title ?? 'Not associated yet!',
            'Standard Coordinator' => $standardCoordinator, // standard coordinator name
            'IndicatorsRatio' => $indicatorsFinishedRatio, // indicators ratios
            'StandardRatio' => $standardRatio, // standard ratio
            'AcceptedFiles' => $standardIndicatorsFinished, // accepted files in the standard
            'NumberOfFiles' => $numberOfFiles // total number of files within standard
        ];
    }

    // Model Relationships
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class,'user_file_permission')
            ->withPivot('user_id');
    }
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
