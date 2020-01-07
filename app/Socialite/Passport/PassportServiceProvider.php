<?php

namespace App\Socialite\Passport;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use App\Socialite\PassportProvider;

class PassportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        Socialite::extend(
            'passport',
            function ($app) {
                $config = $app['config']['services.passport'];
                return Socialite::buildProvider(PassportProvider::class, $config);
            }
        );
    }
}
