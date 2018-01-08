<?php

namespace App\Factories;

use App\Factories\Factory;
use App\Currency;
use App\Helpers\CurrenciesUpdater;

class CurrencyFactory extends Factory
{
    
    static function get(string $symbol)
    {
        return Currency::ofSymbol($symbol)->first();
    }

    static function createIfNotExists(string $symbol)
    {
        $currency = self::get($symbol);

        if (is_null($currency)) {
            $data = CurrenciesUpdater::findCurrency($symbol);
            
            $currency = new Currency();
            $currency->symbol = $symbol;

            if (is_null($data)) {
                $currency->name = $symbol;
                $currency->api_path = 'UNDEFINED';
                $currency->usd_value = 0;
                $currency->cad_value = 0;
                $currency->btc_value = 0;
                $currency->percent_change_1h = 0;
                $currency->percent_change_24h = 0;
                $currency->percent_change_7d = 0;
            } else {
                $currency->name = $data['name'];
                $currency->api_path = $data['id'];
                $currency->usd_value = $data['price_usd'];
                $currency->cad_value = $data['price_cad'];
                $currency->btc_value = $data['price_btc'];
                $currency->percent_change_1h = $data['percent_change_1h'];
                $currency->percent_change_24h = $data['percent_change_24h'];
                $currency->percent_change_7d = $data['percent_change_7d'];
            }

            $currency->description = '';
            $currency->save();
        }

        return $currency;
    }
}