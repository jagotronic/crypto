<?php

namespace App\Services\Wallets;

use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\WalletService;
use Illuminate\Database\Eloquent\Model;

class TuneWallet extends ApiService implements WalletService {

	public $name = 'Tune wallet';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

	public function handle (Model $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'https://altmix.org/coins/31-TUNE/explorer/address/'. $address;

        $ch = $this->initCurl($uri);
        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

		$dom = \phpQuery::newDocument($result);
		$h1 = $dom->find('.h2.pageTitle');
		$balanceTd = $dom->find('table:eq(0)')->find('tr:eq(1)')->find('td:last');

		if (!preg_match('#'.$address.'#', $h1->html()) || !count($balanceTd)) {
            $this->throwException(__CLASS__, 'Invalid html for : ' . $uri, $result, $info);
		}

		$symbol = 'TUN';
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