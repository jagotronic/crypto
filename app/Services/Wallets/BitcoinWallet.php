<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\WalletService;
use Illuminate\Database\Eloquent\Model;

class BitcoinWallet extends ApiService implements WalletService {

    public $name = 'Bitcoin wallet';
    protected $fields = [
        'address' => 'text',
    ];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

    public function handle (Model $wallet)
    {
        $address = $wallet->raw_data['address'];
        $uri = 'https://blockexplorer.com/api/addr/'. $address;

        $ch = $this->initCurl($uri);
        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

        $json = json_decode($result);

        if (!is_object($json)) {
            $this->throwException(__CLASS__, 'INVALID JSON', $result, $info);
        }

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