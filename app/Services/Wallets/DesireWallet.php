<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class DesireWallet extends WalletService {

	public $name = 'Desire wallet';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'https://altmix.org/coins/13-Desire/explorer/address/'. $address;

		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$execResult = curl_exec($ch);
		curl_close($ch);

		$dom = \phpQuery::newDocument($execResult);
		$h1 = $dom->find('h1.pageTitle');
		$balanceTd = $dom->find('table:eq(0)')->find('tr:eq(1)')->find('td:last');

		if (!preg_match('#'.$address.'#', $h1->html()) || !count($balanceTd)) {
			throw new \Exception('Invalid html for : ' . $uri);
		}

		$symbol = 'DSR';
		$balance = $wallet->balancesOfSymbol($symbol);
		$value = (float)trim($balanceTd->html());

		if (is_null($balance)) {
			$balance = new Balance();
			$balance->wallet_id = $wallet->id;
			$balance->symbol = $symbol;
		}

		$balance->value = $value;
		$balance->save();
	}
}