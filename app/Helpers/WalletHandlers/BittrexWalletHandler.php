<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;

class BittrexWalletHandler extends WalletHandler {

	public $name = 'Bittrex';
	protected $fields = [
        'apikey' => 'text',
        'apisecret' => 'text',
	];
    public $validation = [
        'apikey' => 'required|string|min:32|max:32',
        'apisecret' => 'required|string|min:32|max:32'
    ];

	public function handle (Wallet $wallet)
	{
		$apikey = $wallet->raw_data['apikey'];
		$apisecret = $wallet->raw_data['apisecret'];
		$nonce = time();
		$uri = 'https://bittrex.com/api/v1.1/account/getbalances?apikey='. $apikey .'&nonce='. $nonce;
		$sign = hash_hmac('sha512', $uri, $apisecret);
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'. $sign));
		$execResult = curl_exec($ch);
		$obj = json_decode($execResult);

		foreach ($obj->result as $balance) {
			$currency = $this->findCurrencyBySymbol($balance->Currency);

			// create Balance here
			// dd($currency->toArray());
		}

	}
}