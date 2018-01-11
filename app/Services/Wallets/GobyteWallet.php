<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class GobyteWallet extends WalletService {

	public $name = 'Gobyte wallet';
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
		$uri = 'http://gobyte.ezmine.io/ext/getbalance/'. $address;

        $ch = $this->initCurl($uri);
        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

		$value = json_decode($result);
		$symbol = 'GBX';
		$balance = $wallet->balancesOfSymbol($symbol);

		if (!is_numeric($value) || empty($value)) {
			$value = 0;
		}

		if (is_null($balance)) {
			$balance = new Balance();
			$balance->wallet_id = $wallet->id;
			$balance->symbol = $symbol;
		}

		$balance->value = $value;
		$balance->save();
	}
}