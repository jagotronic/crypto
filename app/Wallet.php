<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Factories\WalletHandlerFactory;

class Wallet extends Model
{

    /**
     * Get balances of Wallet.
     */
    public function balances()
    {
        return $this->hasMany('App\Balance');
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
        $this->attributes['data'] = json_encode($data[$this->attributes['handler']]);
    }

    static function getHandlers()
    {
        $handlers = [];

        foreach (config('wallethandlers') as $classname) {
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
