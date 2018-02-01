<?php

namespace App\Services\Currencies;

use App\Currency;
use Cache;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\StockExange\StockExangeApi;

class StockExange extends CurrencyService {

    public $name = 'StockExange public API';
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
        $currency->usd_value = $currencyData['usd_value'];
        $currency->cad_value = $currencyData['cad_value'];
        $currency->btc_value = $currencyData['btc_value'];
        $currency->percent_change_1h = $currencyData['percent_change_1h'];
        $currency->percent_change_24h = $currencyData['percent_change_24h'];
        $currency->percent_change_7d = $currencyData['percent_change_7d'];
        $currency->save();

        return $currency->fresh();
    }

    private function getIconSrc(array $currency)
    {
        return '';
    }

    private function getWebPageUrl(array $currency)
    {
        return '';
    }

    public function find(string $symbol)
    {
        // $all_currencies = $this->apiCall('currencies');
        // $all_currencies = $this->apiCall('markets');
        // $all_currencies = $this->apiCall('market_summary/DSR/BTC');
        // $all_currencies = $this->apiCall('prices');
        $results = $this->apiCall('ticker');

        $seekKey = $symbol . '_BTC';

        foreach ($results as $currency) {

            if ($currency['market_name'] === $seekKey) {
                $currency = $this->completeData($currency);
                $currencyData = $this->getCurrencyDataModel($symbol, __CLASS__);

                $currencyData['name'] = $this->getLongName($symbol);
                $currencyData['icon_src'] = $this->getIconSrc($currency);
                $currencyData['webpage_url'] = $this->getWebPageUrl($currency);
                $currencyData['data'] = [];
                $currencyData['usd_value'] = $currency['usd_value'];
                $currencyData['cad_value'] = $currency['cad_value'];
                $currencyData['btc_value'] = $currency['btc_value'];
                $currencyData['percent_change_1h'] = $currency['percent_change_1h'];
                $currencyData['percent_change_24h'] = $currency['percent_change_24h'];
                $currencyData['percent_change_7d'] = $currency['percent_change_7d'];

                return $currencyData;
            }
        }

        return null;
    }

    private function getLongName(string $symbol)
    {
        $currencies = $this->apiCall('currencies');

        foreach ($currencies as $currency) {

            if ($currency['currency'] == $symbol) {
                return $currency['currency_long'];
            }
        }

        return $symbol;
    }

    private function apiCall($endpoint)
    {
        $url = 'https://stocks.exchange/api2/' . $endpoint;
        $key = 'StockExange::'.$endpoint;
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
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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

    private function completeData(array $data)
    {
        $coinMarketCapApi = new Coinmarketcap();
        $btc = $coinMarketCapApi->find('BTC');

        $data['btc_value'] = $data['last'];
        unset($data['last']);
        $data['usd_value'] = $data['btc_value'] * $btc['usd_value'];
        $data['cad_value'] = $data['btc_value'] * $btc['cad_value'];
        $data['percent_change_1h'] = null;
        $data['percent_change_24h'] = $data['spread'];
        $data['percent_change_7d'] = null;

        return $data;
    }
}

function gen_nonce($length=9) {
    $b58 = "123456789";
    $nonce = '';
    for ($i = 0; $i < $length; $i++) {
        $char = $b58[mt_rand(0, 8)];
        $nonce = $nonce . $char;
    }
    return $nonce;
}