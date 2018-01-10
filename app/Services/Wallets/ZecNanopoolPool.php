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

        $ch = $this->initCurl($uri);
        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

		$json = json_decode($result);

		if (!empty($json->error)) {
            $this->throwException(__CLASS__, $json->error, $result, $info);
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
