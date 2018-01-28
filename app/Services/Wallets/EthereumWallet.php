<?php

namespace App\Services\Wallets;

use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\WalletService;
use Illuminate\Database\Eloquent\Model;

class EthereumWallet extends ApiService implements WalletService {

    public $name = 'Ethereum wallet';
    protected $fields = [
        'address' => 'text',
    ];
    public $validation = [
        'address' => 'required|string|min:42|max:42',
    ];

    public function handle (Model $wallet)
    {
        $address = $wallet->raw_data['address'];
        $uri = 'https://api.etherscan.io/api?module=account&action=balance&address='. $address .'&tag=latest'; //&apikey=YourApiKeyToken

        $ch = $this->initCurl($uri);
        $result = $this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (empty($result)) {
            $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
        }

        $json = json_decode($result);

        if ($json->status == '0') {
            $this->throwException(__CLASS__, $json->result, $result, $info);
        }

        $value = (float)$json->result / 1000000000000000000;
        $symbol = 'ETH';
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