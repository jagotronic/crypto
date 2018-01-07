<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class UniminingPool extends WalletService {

	public $name = 'unimining.ca pool';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$nonce = time();
		$uri = 'https://www.unimining.net/api/wallet?address='. $address;
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$execResult = curl_exec($ch);
		/** Fix unimining.cs json error */
		$execResult = preg_replace('#:[\s]*,#', ': 0,', $execResult);
		curl_close($ch);

		if ($execResult === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}
		$json = json_decode($execResult);

		$symbol = $json->currency;
		$balance = $wallet->balancesOfSymbol($symbol);
		$value = $json->unpaid;

		if (is_null($balance)) {

			if ($value == 0) {
				return;
			}

			$balance = new Balance();
			$balance->wallet_id = $wallet->id;
			$balance->symbol = $symbol;
		}

		$balance->value = $value;
		$balance->save();
	}
}
