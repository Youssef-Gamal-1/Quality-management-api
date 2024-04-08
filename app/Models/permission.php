<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permission extends Model
{
    use HasFactory;
    public $table ="permission";
    public $fillable=['p_id','grant_date','expiration_date','action','resource'];

    public function users() \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(permission::class,'permission_users');
    }
}
