<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\PoolService;
use Illuminate\Database\Eloquent\Model;

class CryptohubPool extends ApiService implements PoolService {

	public $name = 'Cryptohub pool';
	protected $fields = [
        'read_key' => 'text',
	];
    public $validation = [
        'read_key' => 'required|string|min:37|max:37',
    ];

	public function handle (Model $wallet)
	{
		$read_key = $wallet->raw_data['read_key'];

		$url = 'https://cryptohub.online/api/pools_info/?read_key=' . $read_key;

        $ch = $this->initCurl($url);
        $result = $this->execute($ch, 10);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

		$json = json_decode($result);

		if (!empty($json->error)) {
            $this->throwException(__CLASS__, $json->error, $result, $info);
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
