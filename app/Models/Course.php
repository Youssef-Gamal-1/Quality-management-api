<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['title','code','hours','type'];

    public function programs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Program::class,'program_courses');
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'course_user');
    }

    public function degrees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Degree::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(CourseFiles::class);
    }

    public function questionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class);
    }

}
