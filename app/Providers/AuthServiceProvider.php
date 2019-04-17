<?php

namespace App\Providers;

use App\Policies\ReplyPolicy;
use App\Policies\UserPolicy;
use App\Reply;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Reply::class => ReplyPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissions();
    }

    private function registerPermissions()
    {
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });
    }
}
