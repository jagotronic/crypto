<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    /**
     * Get wallet balances
     */
    public function balances()
    {
        return $this->hasMany('App\Balance');
    }

    /**
     * Get wallet balances of specific currency
     */
    public function balancesOfSymbol(string $symbol)
    {
        return $this->balances->where('symbol', $symbol)->first();
    }

    public function getRawDataAttribute()
    {
        return json_decode($this->attributes['data'], true);
    }

    public function getDataAttribute()
    {
        return [
            $this->attributes['handler'] => json_decode($this->attributes['data'], true)
        ];
    }

    public function setDataAttribute($data)
    {
        if (!empty($data[$this->attributes['handler']])) {
            $this->attributes['data'] = json_encode($data[$this->attributes['handler']]);
        } else {
            $this->attributes['data'] = json_encode($data);
        }
    }

    static function getHandlers()
    {
        $handlers = [];

        foreach (config('walletservices') as $classname) {
            $handler = new $classname();
            $reflectionClass = new \ReflectionClass($handler);

            $handlers[$reflectionClass->getShortName()] = [
                'id' => $reflectionClass->getShortName(),
                'name' => $handler->getName(),
                'fields' => $handler->getFields(),
                'validation' => $handler->validation,
            ];
        }

        return $handlers;
    }
}
