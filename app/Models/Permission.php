<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
      'name',
      'grant_date',
      'expiration_date'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_file_permission')
            ->withPivot('standard_id','form_id');
    }

    public function file(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Form::class,'user_file_permission')
            ->withPivot('user_id','standard_id');
    }

    public function standard(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Standard::class,'user_file_permission')
            ->withPivot('user_id','form_id');
    }

    // filter for searching
    public function scopeSearch($query, $search = null)
    {
        if ($search) {
            $query->where('name', 'like', '%'.$search['search'].'%');
        }

        return $query;
    }

}
