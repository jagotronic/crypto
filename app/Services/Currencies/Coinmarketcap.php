<?php

namespace App\Services\Currencies;

use App\Currency;
use Illuminate\Database\Eloquent\Model;

class Coinmarketcap extends CurrencyService {

    public $name = 'Coinmarketcap API';
    protected $fields = [
        'api_path' => 'text',
    ];
    public $validation = [
        'api_path' => 'required|string',
    ];
    private static $all_currencies = null;

    public function handle(Model $currency)
    {
        $apiPath = $currency->raw_data['api_path'];
        $json = $this->apiCall('v1/ticker/'.$apiPath.'/?convert=CAD');

        if (count($json) !== 1) {
            $this->throwException(__CLASS__, 'INVALID RESPONSE', $result, $info);
        }

        $currencyData = array_pop($json);
        $currency->name = $currencyData['name'];
        $currency->icon_src = $this->getIconSrc($apiPath);
        $currency->webpage_url = $this->getWebPageUrl($apiPath);
        $currency->usd_value = $currencyData['price_usd'];
        $currency->cad_value = $currencyData['price_cad'];
        $currency->btc_value = $currencyData['price_btc'];
        $currency->percent_change_1h = $currencyData['percent_change_1h'];
        $currency->percent_change_24h = $currencyData['percent_change_24h'];
        $currency->percent_change_7d = $currencyData['percent_change_7d'];
        $currency->save();

        return $currency->fresh();
    }

    private function getIconSrc(string $apiPath)
    {
        return 'https://digitalcoinprice.com/application/modules/assets/images/coins/64x64/'. $apiPath .'.png';
    }

    private function getWebPageUrl(string $apiPath)
    {
        return 'https://digitalcoinprice.com/'. $apiPath;
    }

    public function find(string $symbol)
    {
        if (is_null(self::$all_currencies)) {
            $json = $this->apiCall('v1/ticker/?limit=10000&convert=CAD');

            self::$all_currencies = $json;
        }

        foreach (self::$all_currencies as $currency) {

            if ($currency['symbol'] === $symbol) {
                $currencyData = $this->getCurrencyDataModel($symbol, __CLASS__);

                $currencyData['name'] = $currency['name'];
                $currencyData['icon_src'] = $this->getIconSrc($currency['id']);
                $currencyData['webpage_url'] = $this->getWebPageUrl($currency['id']);
                $currencyData['data'] = [
                    'api_path' => $currency['id']
                ];
                $currencyData['usd_value'] = $currency['price_usd'];
                $currencyData['cad_value'] = $currency['price_cad'];
                $currencyData['btc_value'] = $currency['price_btc'];
                $currencyData['percent_change_1h'] = $currency['percent_change_1h'];
                $currencyData['percent_change_24h'] = $currency['percent_change_24h'];
                $currencyData['percent_change_7d'] = $currency['percent_change_7d'];

                return $currencyData;
            }
        }

        return null;
    }

    private function apiCall($endpoint)
    {
        $url = 'https://api.coinmarketcap.com/' . $endpoint;
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

        return $json;
    }
}
