<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\ExchangeService;
use Illuminate\Database\Eloquent\Model;

class CryptopiaExchange extends ApiService implements ExchangeService {

	public $name = 'Cryptopia';
	protected $fields = [
        'apikey' => 'text',
        'apisecret' => 'text',
	];
    public $validation = [
        'apikey' => 'required|string|min:1|max:100',
        'apisecret' => 'required|string|min:1|max:100'
    ];

	public function handle(Model $wallet)
	{
		$apikey = $wallet->raw_data['apikey'];
		$api_secret = $wallet->raw_data['apisecret'];

		try {
		   $ct = New \Cryptopia($api_secret, $apikey);
		   $balances = $ct->getBalance();
		} catch(Exception $e) {
            $this->throwException(__CLASS__, $e->getMessage());
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
