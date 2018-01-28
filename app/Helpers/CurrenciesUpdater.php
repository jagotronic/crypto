<?php

namespace App\Helpers;

use App\Currency;
use App\Factories\CurrencyServiceFactory;
use URL;
use Asset;

class CurrenciesUpdater {

    private static $currencies = [];
    private static $all_currencies = null;

    public static function updateAll() {
        $response = [];
        $start_updates_at = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        foreach (Currency::all() as $currency) {
            self::update($currency);
        }
        
        $response['update_all_time'] = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $start_updates_at;

        return $response;
    }

    public static function update(Currency $currency) {
        return $currency->refresh();
    }
}
