<?php

namespace App\Services\Currencies;

use Cache;
use Illuminate\Database\Eloquent\Model;

class CoinsMarkets extends CurrencyService {

    public $name = 'CoinsMarkets API';
    protected $fields = [];
    public $validation = [];

    public function handle(Model $currency)
    {
        $currencyData = $this->find($currency->symbol);

        if (empty($currencyData)) {
            $this->throwException(__CLASS__, 'INVALID RESPONSE', $result, $info);
        }

        $currency->name = $currencyData['name'];
        $currency->icon_src = $currencyData['icon_src'];
        $currency->webpage_url = $currencyData['webpage_url'];
        $currency->btc_value = $currencyData['btc_value'];
        $currency->usd_value = $currencyData['usd_value'];
        $currency->cad_value = $currencyData['cad_value'];
        $currency->percent_change_1h = $currencyData['percent_change_1h'];
        $currency->percent_change_24h = $currencyData['percent_change_24h'];
        $currency->percent_change_7d = $currencyData['percent_change_7d'];
        $currency->save();

        return $currency->fresh();
    }

    public function find(string $symbol)
    {
        $all_currencies = $this->getAllCurrencies();
        $key = 'BTC_'. $symbol;

        if (array_key_exists($key, $all_currencies)) {
            $currency = $this->completeData($all_currencies[$key]);
            $currencyData = $this->getCurrencyDataModel($symbol, __CLASS__);

            $currencyData['icon_src'] = $this->getIconSrc($currency);
            $currencyData['webpage_url'] = $this->getWebPageUrl($currency);
            $currencyData['data'] = [];
            $currencyData['usd_value'] = $currency['usd_value'];
            $currencyData['cad_value'] = $currency['cad_value'];
            $currencyData['btc_value'] = $currency['btc_value'];
            $currencyData['percent_change_1h'] = null;
            $currencyData['percent_change_24h'] = $currency['percentChange'];
            $currencyData['percent_change_7d'] = null;

            return $currencyData;
        }

        return null;
    }

    private function getAllCurrencies()
    {
        return $this->apiCall('ticker/');
    }

    private function apiCall($endpoint)
    {
        $url = 'https://cryptohub.online/api/market/'. $endpoint;
        $key = 'CoinsMarkets::'.$endpoint;
        $json = null;

        if (Cache::has($key)) {
            $cache = Cache::get($key);

            if (!empty($cache)) {
                $json = unserialize($cache);
            }
        }

        if (empty($json)) {
            $headers = array(
                'Content-type: text/xml;charset=UTF-8', 
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8', 
                'Cache-Control: no-cache', 
                'Pragma: no-cache', 
            );

            $ch = $this->initCurl($url, $headers);
            $result = $this->execute($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            if (empty($result)) {
                $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
            }

            $json = json_decode($result, true);

            if (!is_array($json)) {
                $this->throwException(__CLASS__, 'INVALID RESPONSE', $result, $info);
            }

            Cache::put($key, serialize($json), 10);
        }

        return $json;
    }

    private function getIconSrc(array $currencyData)
    {
        return null;
    }

    private function getWebPageUrl(array $currencyData)
    {
        return null;
    }

    private function completeData(array $data)
    {
        $coinMarketCapApi = new Coinmarketcap();
        $btc = $coinMarketCapApi->find('BTC');

        $data['btc_value'] = $data['last'];
        unset($data['last']);
        $data['usd_value'] = $data['btc_value'] * $btc['usd_value'];
        $data['cad_value'] = $data['btc_value'] * $btc['cad_value'];

        return $data;
    }
}
