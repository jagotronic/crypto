<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CurrenciesUpdater;

class Currency extends Model
{
    public function scopeOfSymbol($query, $symbol)
    {
        return $query->where('symbol', $symbol);
    }

    public function refresh()
    {
    	CurrenciesUpdater::update($this);
    }
}
