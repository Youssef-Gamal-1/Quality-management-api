<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = ['content','value','question_id'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'student_answers')
            ->withPivot('questionnaire_id','question_id');
    }
}
