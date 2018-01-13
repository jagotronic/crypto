<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Factories\WalletServiceFactory;
use App\Factories\CurrencyServiceFactory;
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
            return WalletServiceFactory::isValidService($value);
        });
        Validator::extend('valid_currency_handler', function($attribute, $value, $parameters, $validator) {
            return CurrencyServiceFactory::isValidService($value);
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
