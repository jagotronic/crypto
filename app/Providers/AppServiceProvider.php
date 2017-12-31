<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_wallet_handler', function($attribute, $value, $parameters, $validator) {
            $valid_handler = config('wallethandlers');
            $classPath = 'App\\Helpers\\WalletHandlers\\' . $value;

            if (in_array($classPath, $valid_handler)) {
                return true;
            }

            return false;
        });
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
