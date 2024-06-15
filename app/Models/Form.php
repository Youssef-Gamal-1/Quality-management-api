<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Form extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'value',
        'indicator_id',
        'status',
        'uploaded',
        'path',
        'type'
    ];

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class,'user_file_permission')
            ->withPivot('user_id');
    }

    public function indicator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'users_forms');
    }
    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function (Form $form) {
            // Delete the associated file when the model is being deleted
            if($form->path)
            {
                Storage::delete($form->path);
            }
        });
    }
}
