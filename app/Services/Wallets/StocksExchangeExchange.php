<?php

namespace App\Services\Wallets;

use Cache;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\ExchangeService;
use Illuminate\Database\Eloquent\Model;

class StocksExchangeExchange extends ApiService implements ExchangeService {

    public $name = 'Stocks.Exchange';
    protected $fields = [
        'apikey' => 'text',
        'apisecret' => 'text',
    ];
    public $validation = [
        'apikey' => 'required|string|min:10|max:100',
        'apisecret' => 'required|string|min:10|max:100'
    ];
    private $_base_url = 'https://stocks.exchange/api2';

    public function handle (Model $wallet)
    {
        $apikey = $wallet->raw_data['apikey'];
        $apisecret = $wallet->raw_data['apisecret'];

        $this->_api_key = $apikey;
        $this->_api_secret = $apisecret;

        $info = $this->request('GetInfo', array());

        foreach ($info->funds as $symbol => $fund) {
            $fund = (float)$info->funds->$symbol;
            $hold_fund = (float)$info->hold_funds->$symbol;
            $value = $fund + $hold_fund;

            $balance = $wallet->balancesOfSymbol($symbol);

            if (is_null($balance)) {

                if ($value == 0) {
                    continue;
                }

                $balance = new Balance();
                $balance->wallet_id = $wallet->id;
                $balance->symbol = $symbol;
            }

            $balance->value = $value;
            $balance->save();
        }
    }

    /* Make a call to the StockExange API. */
    public function request($method, $params, $sign=true, $post=true) {
        $key = 'StocksExchangeExchange::'.$method;
        $json = null;

        if (Cache::has($key)) {
            $cache = Cache::get($key);

            if (!empty($cache)) {
                $json = unserialize($cache);
            }
        }


        if (empty($json)) {
            $headers = array();

            if ($sign) {
                $params['nonce'] = gen_nonce();
                $params['method'] = $method;
                // generate the POST data string
                $post_data = http_build_query($params, '', '&');
                $headers[] = 'Key: ' .$this->_api_key;
                $sign = hash_hmac('sha512', $post_data, $this->_api_secret);
                $headers[] = 'Sign: ' .$sign;
            } else {
                // generate the POST data string
                $post_data = http_build_query($params, '', '&');
            }

            // our curl handle (initialize if required)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->_base_url);

            if ($post){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, $post);
            }

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            /**
             * Need help
             * just copied cookie from adddress: 
             * https://stocks.exchange/api2/currencies
             */
            curl_setopt($ch, CURLOPT_COOKIE, '__cfduid=dc003189e85ce5478e0f104d6f8f1f86d1516252797; _ga=GA1.2.694882882.1516252802; _gid=GA1.2.348702545.1517026093; cf_clearance=070744d7037548fc9a07dfd4b290fcff253f3f1d-1517370192-1800; session=eyJpdiI6Ik5UUFdsMjJvVEI4a2lDRmdmb2pYTWM0bmt3V0ZlRVJ2UVwvTTNlM0VJemV3PSIsInZhbHVlIjoiSkRKUFgxQkpBXC8wMzRwWmJpZ0lZbGJvZU9cLzZKRk1zOWVEajVLalwvYTVqc1ltbjBRZ1R2UU0zeThIWGg4amVcLzRvT3pPZ3JpNDhoTVlTTVppeFFISjd3PT0iLCJtYWMiOiI5Zjk0YWNhMjQ5ZmMwN2I2ZTdmNjhlMGFhNGRmOWJiYjMwNGRjZmViMzAzNmVhN2ZmYmUyNDMyNmI0MWMyYWI2In0%3D; _gat=1');
            curl_setopt($ch, CURLOPT_USERAGENT,
                // 'Mozilla/4.0 (compatible; Kucoin Bot; '.php_uname('a').'; PHP/'.phpversion().')'
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36'
            );
            // run the query
            $result = curl_exec($ch);
            $info = curl_getinfo($ch);

            if ($result === false) {
                $this->throwException(__CLASS__, 'Could not get reply: '.curl_error($ch), $result, $info);
            }

            curl_close($ch);

            $json = json_decode($result);

            if (!$json){
                $this->throwException(__CLASS__, 'Invalid data received, please make sure connection is working and requested API exists', $result, $info);
            }

            Cache::put($key, serialize($json), 10);
        }

        return $json->data;
    }
}

/* Auxiliary function for sending signed requests to StockExange. */
function gen_nonce($length=9) {
    $b58 = "123456789";
    $nonce = '';

    for ($i = 0; $i < $length; $i++) {
        $char = $b58[mt_rand(0, 8)];
        $nonce = $nonce . $char;
    }

    return $nonce;
}
