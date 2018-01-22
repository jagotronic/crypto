<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\ExchangeService;
use Illuminate\Database\Eloquent\Model;

class CoinsmarketsExchange extends ApiService implements ExchangeService {

	public $name = 'Coins Markets';
	protected $fields = [
        'username' => 'text',
        'password' => 'password',
        'pin' => 'password',
	];
    public $validation = [
        'username' => 'required|string|min:3|max:191',
        'password' => 'required|string|min:3|max:191',
        'pin' => 'required|numeric',
    ];

	public function handle (Model $wallet)
	{
		$data = http_build_query([
			'username' => $wallet->raw_data['username'],
			'password' => $wallet->raw_data['password'],
			'pin' =>      $wallet->raw_data['pin'],
			'data' =>     'gettradinginfo'
		]);

        $ch = $this->initCurl();
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; coinsmarkets PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, 'https://coinsmarkets.com/apiv1.php');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

/**
 * @todo need API access to continue
 */
dd($result);


		// $apikey = $wallet->raw_data['apikey'];
		// $apisecret = $wallet->raw_data['apisecret'];
		// $nonce = time();
		// $uri = 'https://bittrex.com/api/v1.1/account/getbalances?apikey='. $apikey .'&nonce='. $nonce;
		// $sign = hash_hmac('sha512', $uri, $apisecret);

  //       $ch = $this->initCurl();
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'. $sign));
		// $execResult = curl_exec($ch);
		// curl_close($ch);

		// if ($execResult === false) {
		// 	throw new \Exception(__CLASS__ . ' -- SERVER NOT RESPONDING');
		// }

		// $json = json_decode($execResult);

		// if (empty($json->success)) {
		// 	throw new \Exception($json->message);
		// }

		// foreach ($json->result as $JsonBalance) {
		// 	$balance = $wallet->balancesOfSymbol($JsonBalance->Currency);

		// 	if (is_null($balance)) {
		// 		$balance = new Balance();
		// 		$balance->wallet_id = $wallet->id;
		// 		$balance->symbol = $JsonBalance->Currency;
		// 	}

		// 	$balance->value = $JsonBalance->Balance;
		// 	$balance->save();
		// }

	}
}