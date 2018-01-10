<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class MineprojectPool extends WalletService {

	public $name = 'pool.mineproject.ru';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:30|max:36',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$nonce = time();
		$uri = 'http://pool.mineproject.ru/api/wallet?address='. $address;

        $ch = $this->initCurl($uri);
		$execResult = curl_exec($ch);
		/** Fix unimining.cs json error */
		$execResult = preg_replace('#:[\s]*,#', ': 0,', $execResult);
		curl_close($ch);

		if ($execResult === false) {
			throw new \Exception(__CLASS__ . ' -- SERVER NOT RESPONDING');
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
