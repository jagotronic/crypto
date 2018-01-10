<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;

class QuadrigacxExchange extends WalletService {

	public $name = 'Quadrigacx';
	protected $fields = [
        'apikey' => 'text',
        'clientId' => 'text',
        'apisecret' => 'password',
	];
    public $validation = [
        'apikey' => 'required|string|min:10|max:10',
        'clientId' => 'required|string|min:7|max:7',
        'apisecret' => 'required|string|min:32|max:32'
    ];

	public function handle(Wallet $wallet)
	{
		$response = self::getBalances($wallet);

		foreach (['btc', 'eth', 'ltc', 'etc', 'btg', 'bch'] as $symbol) {
            $balance_key = $symbol.'_balance';
            $value = (float)$response->$balance_key;
			$balance = $wallet->balancesOfSymbol(strtoupper($symbol));

            if (is_null($balance)) {

				if ($value == 0) {
					continue;
				}

				$balance = new Balance();
				$balance->wallet_id = $wallet->id;
				$balance->symbol = strtoupper($symbol);
			}

			$balance->value = $value;
			$balance->save();
		}
	}

	private function getBalances(Wallet $wallet)
	{
		return self::apiCall($wallet, 'balance');
	}

	private function apiCall(Wallet $wallet, $endpoint, $querystring = '')
	{
        $host = 'https://api.quadrigacx.com/v2/';

        $nonce     = time(); // Unix timestamp
        $key       = $wallet->raw_data['apikey']; // My API Key
        $client    = $wallet->raw_data['clientId'];; // My Client ID
        $secret    = $wallet->raw_data['apisecret']; // My secret
        $signature = hash_hmac('sha256', $nonce . $client . $key, $secret); // Hashing it
        $data = array(
            'key'       => $key,
            'nonce'     => $nonce,
            'signature' => $signature
        );
        $data_string = json_encode($data);

        $ch = $this->initCurl($host . $endpoint, [
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_string)
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

		$json = json_decode($result);

		if (!empty($json->error)) {
            $this->throwException(__CLASS__, $json->error, $result, $info);
		}

		return $json;
	}
}
