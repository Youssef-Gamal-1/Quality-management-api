<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Form;
use App\Models\Indicator;
use App\Models\Program;
use App\Models\Standard;
use App\Models\User;
use App\Policies\FormPolicy;
use App\Policies\IndicatorPolicy;
use App\Policies\ProgramPolicy;
use App\Policies\StandardPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Standard::class => StandardPolicy::class,
        Program::class => ProgramPolicy::class,
        Indicator::class => IndicatorPolicy::class,
        Form::class  => FormPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
