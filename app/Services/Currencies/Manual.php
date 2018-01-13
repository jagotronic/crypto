<?php

namespace App\Services\Currencies;

use App\Currency;
use Illuminate\Database\Eloquent\Model;

class Manual extends CurrencyService {

    public $name = 'Manual';
    protected $fields = [];
    public $validation = [];

    public function handle(Model $currency)
    {
        // nothing to do - changes are manual
        return $currency;
    }

    public function find(string $symbol)
    {
        $currencyData = $this->getCurrencyDataModel($symbol, __CLASS__);

        $currencyData['name'] = $symbol;

        return $currencyData;
    }
}
