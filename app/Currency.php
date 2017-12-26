<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public function scopeOfSymbol($query, $symbol)
    {
        return $query->where('symbol', $symbol);
    }
}
