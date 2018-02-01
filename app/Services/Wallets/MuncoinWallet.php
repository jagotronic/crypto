<?php

namespace App\Services\Wallets;

use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\WalletService;
use Illuminate\Database\Eloquent\Model;

class MuncoinWallet extends ApiService implements WalletService {

	public $name = 'Muncoin wallet';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:10|max:100',
    ];

	public function handle (Model $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'http://explorer.muncoin.info/ext/getbalance/'. $address;

        $ch = $this->initCurl($uri);
        $value = (float)$this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (!is_numeric($value)) {
            $this->throwException(__CLASS__, 'INVALID VALUE', $value, $info);
        }

		$symbol = 'MUN';
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