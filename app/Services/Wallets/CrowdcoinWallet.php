<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\WalletService;
use Illuminate\Database\Eloquent\Model;

class CrowdcoinWallet extends ApiService implements WalletService {

    public $name = 'CrowdCoin wallet';
    protected $fields = [
        'address' => 'text',
    ];
    public $validation = [
        'address' => 'required|string|min:34|max:34',
    ];

    public function handle (Model $wallet)
    {
        $address = $wallet->raw_data['address'];
        // $uri = 'http://crowdcoin.site:3001/ext/getbalance/'. $address; //&apikey=YourApiKeyToken
        $uri = 'http://explorer.cryptopros.us/ext/getbalance/'. $address; //&apikey=YourApiKeyToken

        $ch = $this->initCurl($uri);
        $value = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($value)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $value, $info);
        }

        $symbol = 'CRC';
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