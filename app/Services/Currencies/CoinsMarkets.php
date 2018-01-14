<?php

namespace App\Services\Currencies;

use App\Currency;
use App\Factories\CurrencyFactory;
use Illuminate\Database\Eloquent\Model;

class CoinsMarkets extends CurrencyService {

    public $name = 'CoinsMarkets API';
    protected $fields = [];
    public $validation = [];
    private static $all_currencies = null;

    public function handle(Model $currency)
    {
        $json = $this->apiCall();
        $key = 'BTC_'. $currency->symbol;

        if (!array_key_exists($key, $json)) {
            $this->throwException(__CLASS__, $key . ' NOT FOUND');
        }

        $currencyData = $this->completeData($json[$key]);
        $currency->btc_value = $currencyData['btc_value'];
        $currency->usd_value = $currencyData['usd_value'];
        $currency->cad_value = $currencyData['cad_value'];
        $currency->percent_change_1h = null;
        $currency->percent_change_24h = $currencyData['percentChange'];
        $currency->percent_change_7d = null;
        $currency->save();

        return $currency->fresh();
    }

    public function find(string $symbol)
    {
        if (is_null(self::$all_currencies)) {
            self::$all_currencies = $this->apiCall();
        }

        $key = 'BTC_'. $symbol;

        if (array_key_exists($key, self::$all_currencies)) {
            $currency = $this->completeData(self::$all_currencies[$key]);
            $currencyData = $this->getCurrencyDataModel($symbol, __CLASS__);

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

    private function apiCall()
    {
        $url = 'https://cryptohub.online/api/market/ticker/';
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

        return $json;
    }

    private function getIconSrc(string $apiRefKey)
    {
        return null;
    }

    private function getWebPageUrl(string $apiRefKey)
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
