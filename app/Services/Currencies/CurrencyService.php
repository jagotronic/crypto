<?php

namespace App\Services\Currencies;

use App\Services\ApiService;

abstract class CurrencyService extends ApiService
{
    abstract public function find(string $symbol);

    // abstract public function getIconPath(string $symbol);

    // abstract public function getWebPageUrl(string $symbol);

    protected function getCurrencyDataModel(string $symbol, string $service)
    {
        $service = explode('\\', $service);

        return [
            'name' => $symbol,
            'handler' => array_pop($service),
            'symbol' => $symbol,
            'icon_src' => null,
            'webpage_url' => null,
            'data' => [],
            'usd_value' => 0,
            'cad_value' => 0,
            'btc_value' => 0,
            'percent_change_1h' => 0,
            'percent_change_24h' => 0,
            'percent_change_7d' => 0,
            'description' => ''
        ];
    }
}
