<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Balance;

class YobitWalletHandler extends WalletHandler {

	public $name = 'Yobit';
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
		$apikey = $wallet->raw_data['apikey'];
		$api_secret = $wallet->raw_data['apisecret'];

		$post_data = http_build_query([
			'method' => 'getInfo',
			'nonce' => time(),
		], '', '&');
		$sign = hash_hmac("sha512", $post_data, $api_secret);
		$headers = array(
		    'Sign: '. $sign,
		    'Key: '. $apikey,
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; SMART_API PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
		curl_setopt($ch, CURLOPT_URL, 'https://yobit.net/tapi/');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING , 'gzip');
		$outpout = curl_exec($ch);
		curl_close($ch);

		if ($outpout === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$json = json_decode($outpout);
		if (!empty($json->error)) {
			throw new \Exception($json->error);
		}

		foreach ($json->return->funds_incl_orders as $symbol => $value) {
			$symbol = strtoupper($symbol);
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
}
