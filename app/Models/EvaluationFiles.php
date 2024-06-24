<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationFiles extends Model
{
    protected $fillable = ['path','standard_id'];
    use HasFactory;

    public function standard(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }
}
