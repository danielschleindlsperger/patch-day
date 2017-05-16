<?php

namespace App\Providers;

use App\Company;
use App\PatchDay;
use App\Policies\CompanyPolicy;
use App\Policies\PatchDayPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ProtocolPolicy;
use App\Policies\TechnologyPolicy;
use App\Project;
use App\Protocol;
use App\Technology;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Company::class => CompanyPolicy::class,
        PatchDay::class => PatchDayPolicy::class,
        Project::class => ProjectPolicy::class,
        Protocol::class => ProtocolPolicy::class,
        Technology::class => TechnologyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
