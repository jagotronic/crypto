<?php

namespace App\Helpers\WalletHandlers;
use App\Wallet;
use App\Balance;

class BitcoinWalletHandler extends WalletHandler {

	public $name = 'Bitcoin wallet';
	protected $fields = [
        'address' => 'text',
	];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

	public function handle (Wallet $wallet)
	{
		$address = $wallet->raw_data['address'];
		$uri = 'https://blockexplorer.com/api/addr/'. $address;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $execResult = curl_exec($ch);
        curl_close($ch);

        if ($execResult === false) {
            throw new \Exception('SERVER NOT RESPONDING');
        }

        $json = json_decode($execResult);
        $value = (float)$json->balance;
        $symbol = 'BTC';
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