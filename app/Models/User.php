<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'academic_email',
        'email',
        'username',
        'phone',
        'password',
        'activated',
        'QM',
        'EC',
        'QU',
        'PC',
        'SC',
        'TS',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
       return $this->belongsToMany(Permission::class,'user_file_permission')
           ->withPivot('standard_id','form_id');
    }
    public function files(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Form::class,'user_file_permission');
    }

    public function standards(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Standard::class,'user_file_permission');
    }

    public function programs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Program::class,'program_user');
    }

    public function courses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Course::class,'course_user');
    }

    public function standard(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Standard::class);
    }

    public function forms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Form::class,'users_forms');
    }

    public function scopeFilter(Builder | QueryBuilder $query, array $filters)
    {
        return $query->when(
            $filters['search'] ?? null,
            function($query,$search) {
                $query->where(
                    fn($q) => $q->where('name','like','%'. $search .'%')
                                ->orWhere('academic_email','like','%'. $search . '%')
                                ->orWhere('email','like','%'. $search . '%')
                                ->orWhere('username','like','%'. $search . '%')
                );
            }
        )->when(
            $filters['role'] ?? null,
            function($query,$role) {
                $roles = explode(',',$role);
                foreach($roles as $role):
                    $query->where($role,1);
                endforeach;
            }
        );
    }
}
