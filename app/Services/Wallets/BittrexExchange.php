<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\ExchangeService;
use Illuminate\Database\Eloquent\Model;

class BittrexExchange extends ApiService implements ExchangeService {

	public $name = 'Bittrex';
	protected $fields = [
        'apikey' => 'text',
        'apisecret' => 'text',
	];
    public $validation = [
        'apikey' => 'required|string|min:32|max:32',
        'apisecret' => 'required|string|min:32|max:32'
    ];

	public function handle (Model $wallet)
	{
		$apikey = $wallet->raw_data['apikey'];
		$apisecret = $wallet->raw_data['apisecret'];
		$nonce = time();
		$uri = 'https://bittrex.com/api/v1.1/account/getbalances?apikey='. $apikey .'&nonce='. $nonce;
		$sign = hash_hmac('sha512', $uri, $apisecret);

        $ch = $this->initCurl($uri, ['apisign:'. $sign]);
        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

		if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
		}

		$json = json_decode($result);

		if (empty($json->success)) {
            $this->throwException(__CLASS__, $json->message, $result, $info);
		}

		foreach ($json->result as $JsonBalance) {
			$balance = $wallet->balancesOfSymbol($JsonBalance->Currency);

			if (is_null($balance)) {

				if ($JsonBalance->Balance == 0) {
					continue;
				}

				$balance = new Balance();
				$balance->wallet_id = $wallet->id;
				$balance->symbol = $JsonBalance->Currency;
			}

			$balance->value = $JsonBalance->Balance;
			$balance->save();
		}

	}
}