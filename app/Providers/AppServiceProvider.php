<?php

namespace App\Providers;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PersonalAccessToken::saving(function (PersonalAccessToken $token) {
            if ($token->isDirty('last_used_at')) {
                return false;
            }
        });
    }
}
