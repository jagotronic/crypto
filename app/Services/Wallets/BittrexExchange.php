<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class BittrexExchange extends WalletService {

	public $name = 'Bittrex';
	protected $fields = [
        'apikey' => 'text',
        'apisecret' => 'text',
	];
    public $validation = [
        'apikey' => 'required|string|min:32|max:32',
        'apisecret' => 'required|string|min:32|max:32'
    ];

	public function handle (Wallet $wallet)
	{
		$apikey = $wallet->raw_data['apikey'];
		$apisecret = $wallet->raw_data['apisecret'];
		$nonce = time();
		$uri = 'https://bittrex.com/api/v1.1/account/getbalances?apikey='. $apikey .'&nonce='. $nonce;
		$sign = hash_hmac('sha512', $uri, $apisecret);


        $ch = $this->initCurl($uri);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'. $sign));
		$execResult = curl_exec($ch);
		curl_close($ch);

		if ($execResult === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$json = json_decode($execResult);

		if (empty($json->success)) {
			throw new \Exception($json->message);
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