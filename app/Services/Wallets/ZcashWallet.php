<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class ZcashWallet extends WalletService {

	public $name = 'Zcash wallet';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:35|max:35',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'https://api.zcha.in/v2/mainnet/accounts/'. $address;

		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$execResult = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($execResult);

		if (is_null($json)) {
			throw new \Exception('SERVER NOT RESPONDING OR INVALID ADDRESS');
		}

		$symbol = 'ZEC';
		$balance = $wallet->balancesOfSymbol($symbol);
		$value = $json->balance;

		if (is_null($balance)) {
			$balance = new Balance();
			$balance->wallet_id = $wallet->id;
			$balance->symbol = $symbol;
		}

		$balance->value = $value;
		$balance->save();
	}
}