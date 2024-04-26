<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;
    protected $fillable = ['title','number','number_of_forms','standard_id'];
    // function to calculate the finished reports inside the indicator
    // It will help in generating reports
    public function getFinishedReportsRatio(): float|int
    {
        $numberOfReports = $this->forms()->count();
        if ($numberOfReports === 0) {
            return 0;
        }
        // uploaded and accepted files within the indicator
        $numberOfFinishedReports = $this->forms()->where('status',true)->count();
        return $numberOfFinishedReports / $numberOfReports * 100;
    }

    // Model Relationships

    public function standard(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    public function forms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Form::class);
    }
}
