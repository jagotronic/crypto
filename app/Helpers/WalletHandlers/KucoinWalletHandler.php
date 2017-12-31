<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Balance;

class KucoinWalletHandler extends WalletHandler {

	public $name = 'Kucoin';
	protected $fields = [
        'apikey' => 'text',
        'apisecret' => 'text',
	];
    public $validation = [
        'apikey' => 'required|string|min:24|max:24',
        'apisecret' => 'required|string|min:36|max:36'
    ];

	public function handle (Wallet $wallet)
	{
		$ku_key = $wallet->raw_data['apikey'];
		$ku_secret = $wallet->raw_data['apisecret'];

		$host = 'https://api.kucoin.com';

		$nonce = round(microtime(true) * 1000);
		$endpoint = '/v1/account/balance';
		$querystring = '';
		$signstring = $endpoint.'/'.$nonce.'/'.$querystring;
		$hash = hash_hmac('sha256',  base64_encode($signstring) , $ku_secret);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $host . $endpoint);

		$headers = [
		  'KC-API-SIGNATURE:' . $hash,
		  'KC-API-KEY:' . $ku_key,
		  'KC-API-NONCE:' . $nonce,
		  'Content-Type:application/json'
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT,
		    'Mozilla/4.0 (compatible; Kucoin Bot; '.php_uname('a').'; PHP/'.phpversion().')'
		);
		curl_setopt($ch, CURLOPT_URL, $host . $endpoint);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$outpout = curl_exec($ch);
		curl_close($ch);

		if ($outpout === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$json = json_decode($outpout);
		if (!empty($json->error)) {
			throw new \Exception($json->error);
		}

		foreach ($json->data as $JsonBalance) {
			$balance = $wallet->balancesOfSymbol($JsonBalance->coinType);

			if (is_null($balance)) {

				if ($JsonBalance->balance == 0) {
					continue;
				}

				$balance = new Balance();
				$balance->wallet_id = $wallet->id;
				$balance->symbol = $JsonBalance->coinType;
			}

			$balance->value = $JsonBalance->balance;
			$balance->save();
		}
	}
}


/**
 * INVALID API CALL
 {
  "timestamp": 1514654640200
  "status": 404
  "error": "Not Found"
  "message": "No message available"
  "path": "/account/balasnce"
}
 * BAD ACCESS
 {
  "code": "UNAUTH"
  "msg": "Invalid API Key"
  "success": false
  "timestamp": 1514654625422
}
 * SERVER NOT RESPONDING
 NULL