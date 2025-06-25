<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Assure-toi d'importer ton modèle User

class CustomAuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::extend('active_user_guard', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Auth\Guard
            return new \Illuminate\Auth\SessionGuard($name, Auth::createUserProvider($config['provider']), $app['session.store']);
        });

        Auth::provider('active_user_provider', function ($app, array $config) {
            return new \App\Providers\ActiveUserProvider(Auth::createUserProvider($config['model']));
        });
    }
}