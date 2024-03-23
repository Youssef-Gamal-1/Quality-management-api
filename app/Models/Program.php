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
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Program::class,'program_user');
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
