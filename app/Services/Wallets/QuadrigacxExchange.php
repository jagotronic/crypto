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

	public function handle (Wallet $wallet)
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

        $ch = curl_init($host . $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string))
        );
        $output = curl_exec($ch);
		curl_close($ch);

		if ($output === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$json = json_decode($output);
		if (!empty($json->error)) {
			throw new \Exception($json->error);
		}

		return $json;
	}
}
