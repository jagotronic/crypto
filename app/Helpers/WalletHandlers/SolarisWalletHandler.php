<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Balance;

class SolarisWalletHandler extends WalletHandler {

	public $name = 'Solaris wallet';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'https://solaris.blockexplorer.pro/ext/getbalance/'. $address;

		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$execResult = curl_exec($ch);
		curl_close($ch);

		if ($execResult === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$value = json_decode($execResult);
		$symbol = 'XLR';
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