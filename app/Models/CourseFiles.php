<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFiles extends Model
{
    use HasFactory;
    protected $fillable = ['title','path','uploaded','course_id'];
    protected $hidden = ['path'];
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
