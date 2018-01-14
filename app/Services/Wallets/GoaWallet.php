<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use Illuminate\Database\Eloquent\Model;

class GoaWallet extends ApiService {

	public $name = 'Goa wallet';
	protected $fields = [
        'address' => 'text',
    ];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

	public function handle (Model $wallet)
	{
		$address = $wallet->raw_data['address'];

		$uri = 'http://goacoin.be/ext/getaddress/'. $address;

        $ch = $this->initCurl($uri);
        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

        $json = json_decode($result);
        $value = json_decode($json->balance);
		$symbol = 'GOA';
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