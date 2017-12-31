<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Balance;

class CryptohubWalletHandler extends WalletHandler {

	public $name = 'Cryptohub';
	protected $fields = [
        'read_key' => 'text',
	];
    public $validation = [
        'read_key' => 'required|string|min:37|max:37',
    ];

	public function handle (Wallet $wallet)
	{
		$read_key = $wallet->raw_data['read_key'];

		$url = 'https://cryptohub.online/api/pools_info/?read_key=' . $read_key;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$outpout = curl_exec($ch);
		curl_close($ch);

		if ($outpout === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$json = json_decode($outpout);
		if (!empty($json->error)) {
			throw new \Exception($json->error);
		}

		foreach ($json->pools as $JsonBalance) {
			$balance = $wallet->balancesOfSymbol($JsonBalance->code);
			$amount = $JsonBalance->unconfirmed += $JsonBalance->confirmed;

			if (is_null($balance)) {

				if ($amount == 0) {
					continue;
				}

				$balance = new Balance();
				$balance->wallet_id = $wallet->id;
				$balance->symbol = $JsonBalance->code;
			}


			$balance->value = $amount;
			$balance->save();
		}
	}
}
