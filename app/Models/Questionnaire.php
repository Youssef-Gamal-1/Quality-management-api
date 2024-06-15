<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory;
    protected $fillable = ['title','course_id'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class,'student_answers')
            ->withPivot('user_id','question_id');
    }
}
