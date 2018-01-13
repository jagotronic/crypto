<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Currency;
use App\Factories\CurrencyFactory;

class Balance extends Model
{

    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }

    public function save(array $options = [])
    {
    	parent::save();

    	CurrencyFactory::createIfNotExists($this->symbol);
    }
}
