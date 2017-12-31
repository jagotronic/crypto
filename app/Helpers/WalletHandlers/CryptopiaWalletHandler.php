<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Balance;

class CryptopiaWalletHandler extends WalletHandler {

	public $name = 'Cryptopia';
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

		try {
		   $ct = New \Cryptopia($api_secret, $apikey);
		   $balances = $ct->getBalance();
		} catch(Exception $e) {
			throw new \Exception($e->getMessage());
		}

		foreach ($balances as $balanceArray) {
			$symbol = strtoupper($balanceArray['Symbol']);
			$balance = $wallet->balancesOfSymbol($symbol);
			$value = $balanceArray['Total'];

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
