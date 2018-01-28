<?php

namespace App\Factories;

use App\Factories\Factory;
use App\Currency;
use App\Helpers\CurrenciesUpdater;

class CurrencyFactory extends Factory
{
    
    public static function get(string $symbol)
    {
        return Currency::ofSymbol($symbol)->first();
    }

    public static function createIfNotExists(string $symbol)
    {
        $currency = self::get($symbol);
        if (is_null($currency)) {
            $currency = self::seekForCurrency($symbol);
        }

        return $currency;
    }

    public static function seekForCurrency(string $symbol)
    {
        $currency = null;

        foreach (config('currencyservices') as $serviceClassPath) {
            $service = new $serviceClassPath();
            $currencyData = $service->find($symbol);

            if (!is_null($currencyData)) {
                $currency = self::createCurrency($currencyData);
                break;
            }
        }

        return $currency;
    }

    public static function updateCurrencyService(Currency $currency)
    {
        foreach (config('currencyservices') as $serviceClassPath) {
            $service = new $serviceClassPath();
            $currencyData = $service->find($currency->symbol);

            if (!is_null($currencyData)) {
                $currency = self::updateCurrency($currency, $currencyData);
                break;
            }
        }

        return $currency;
    }

    private static function updateCurrency(Currency $currency, array $currencyData)
    {
        $currency->name = $currencyData['name'];
        $currency->symbol = $currencyData['symbol'];
        $currency->handler = $currencyData['handler'];
        $currency->icon_src = $currencyData['icon_src'];
        $currency->webpage_url = $currencyData['webpage_url'];
        $currency->usd_value = $currencyData['usd_value'];
        $currency->cad_value = $currencyData['cad_value'];
        $currency->btc_value = $currencyData['btc_value'];
        $currency->percent_change_1h = $currencyData['percent_change_1h'];
        $currency->percent_change_24h = $currencyData['percent_change_24h'];
        $currency->percent_change_7d = $currencyData['percent_change_7d'];
        $currency->description = '';
        $currency->data = $currencyData['data'];
        $currency->save();
        $currency->fresh();

        return $currency;
    }

    private static function createCurrency(array $currencyData)
    {
        $currency = new Currency();

        return self::updateCurrency($currency, $currencyData);
    }
}