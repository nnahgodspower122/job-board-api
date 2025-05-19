<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use App\Policies\JobPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     */

     public function boot()
     {
         $this->registerPolicies();
     
         // Register the scopes
         Passport::tokensCan([
             'company' => 'Access for companies',
             'candidate' => 'Access for candidates',
         ]);
     
         // Default scope if none is provided
         Passport::setDefaultScope([
             'candidate',
         ]);
     
         // Optional: Set token expiration times
         Passport::tokensExpireIn(now()->addDays(15));
         Passport::refreshTokensExpireIn(now()->addDays(30));
     }
}
