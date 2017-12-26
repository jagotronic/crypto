<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;

class BittrexWalletHandler {

	public $name = 'Bittrex';
    public $params = [
        'apikey' => 'required|string|min:32|max:32',
        'apisecret' => 'required|string|min:32|max:32',
    ];

	public function handle (Wallet $wallet)
	{
		$apikey='22c8e712693d42f1982ff8830caaf8e9';
		$apisecret='55ed372a44a54e4389737a41872ce346';
		$nonce=time();
		$uri='https://bittrex.com/api/v1.1/account/getbalances?apikey='.$apikey.'&nonce='.$nonce;
		$sign=hash_hmac('sha512', $uri, $apisecret);
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
		$execResult = curl_exec($ch);
		$obj = json_decode($execResult);

		dd($obj);
	}
}