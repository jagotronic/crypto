<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class ZecNanopoolPool extends WalletService {

	public $name = 'zec.nanopool.org pool';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:35|max:35',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'https://api.nanopool.org/v1/zec/user/'. $address;

		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$execResult = curl_exec($ch);
		curl_close($ch);

		if ($execResult === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$json = json_decode($execResult);
		if (!empty($json->error)) {
			throw new \Exception($json->error);
		}

		$symbol = 'ZEC';
		$balance = $wallet->balancesOfSymbol($symbol);
		$value = (float)$json->data->unconfirmed_balance += (float)$json->data->balance;

		if (is_null($balance)) {
			$balance = new Balance();
			$balance->wallet_id = $wallet->id;
			$balance->symbol = $symbol;
		}

		$balance->value = $value;
		$balance->save();
	}
}
