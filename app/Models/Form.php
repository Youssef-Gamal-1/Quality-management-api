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
        'description',
        'indicator_id',
        'status',
        'path',
        'type'
    ];

    public function indicator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Listen for the deleting event
        static::deleting(function (Form $form) {
            // Delete the associated file when the model is being deleted
            Storage::delete($form->path);
        });
    }
}
