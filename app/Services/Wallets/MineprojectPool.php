<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use Illuminate\Database\Eloquent\Model;

class MineprojectPool extends ApiService {

	public $name = 'pool.mineproject.ru';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:30|max:36',
    ];

	public function handle (Model $wallet)
	{
		$address = $wallet->raw_data['address'];
		$nonce = time();
		$uri = 'http://pool.mineproject.ru/api/wallet?address='. $address;

        $ch = $this->initCurl($uri);
        $result = $this->fixJsonError($this->execute($ch));
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

		$json = json_decode($result);

        if (!is_object($json)) {
            $this->throwException(__CLASS__, 'INVALID JSON', $result, $info);
        }

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

	/** Fix unimining.cs json error */
	private function fixJsonError(string $result)
	{
		return preg_replace('#:[\s]*,#', ': 0,', $result);
	}
}
