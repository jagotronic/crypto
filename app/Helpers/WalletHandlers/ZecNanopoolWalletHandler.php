<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Balance;

class ZecNanopoolWalletHandler extends WalletHandler {

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

		/**
		    +"account": "{address}"
		    +"unconfirmed_balance": "0.00000000"
		    +"balance": "0.00373500"
		    +"hashrate": "0.0"
		    +"avgHashrate": {#313 ▶}
		    +"workers": []
		 */

		$symbol = 'ZEC';
		/** @var App\Balance */
		$balance = $wallet->balancesOfSymbol($symbol);
		$value = (float)$json->data->unconfirmed_balance += (float)$json->data->balance;

		if (is_null($balance)) {

			// if ($value == 0) {
			// 	return;
			// }

			$balance = new Balance();
			$balance->wallet_id = $wallet->id;
			$balance->symbol = $symbol;
		}

		$balance->value = $value;
		$balance->save();
	}
}
