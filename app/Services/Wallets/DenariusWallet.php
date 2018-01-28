<?php

namespace App\Services\Wallets;

use App\Wallet;
use App\Balance;
use App\Services\ApiService;
use App\Services\Wallets\Type\WalletService;
use Illuminate\Database\Eloquent\Model;

class DenariusWallet extends ApiService implements WalletService {

    public $name = 'Denarius wallet';
    protected $fields = [
        'address' => 'text',
    ];
    public $validation = [
        'address' => 'required|string|min:30|max:36',
    ];

    public function handle (Model $wallet)
    {
        $address = $wallet->raw_data['address'];
        $uri = 'https://denariusexplorer.org/ext/getbalance/'. $address; //&apikey=YourApiKeyToken

        $ch = $this->initCurl($uri);
        $value = (float)$this->execute($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if (!is_numeric($value)) {
            $this->throwException(__CLASS__, 'INVALID VALUE', $value, $info);
        }

        $symbol = 'DNR';
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