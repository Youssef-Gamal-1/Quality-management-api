<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;
    protected $fillable = ['title','number','standard_id','number_of_forms'];

    public function standard(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Standard::class);
    }

    public function forms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Form::class);
    }
}
