<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class UniminingPool extends WalletService {

	public $name = 'unimining.ca pool';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'https://www.unimining.net/api/wallet?address='. $address;
        $headers = array(
            'Content-type: text/xml;charset=UTF-8', 
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8', 
            'Cache-Control: no-cache', 
            'Pragma: no-cache', 
        );

        $ch = $this->initCurl($uri, $headers);
        $result = $this->fixJsonError($this->execute($ch));
        $info = curl_getinfo($ch);
        curl_close($ch);

		if (empty($result)) {
			$this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
		}

		$json = json_decode($result);

		if (!is_object($json)) {
			$this->throwException(__CLASS__, 'INVALID JSON', $result);
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
