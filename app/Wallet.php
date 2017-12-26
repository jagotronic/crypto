<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get the amounts of Wallet.
     */
    public function amounts()
    {
        return $this->hasMany('App\Amounts');
    }

    static function getHandlers()
    {
        return array_map(function($classname) {
            $handler = new $classname();
            $reflectionClass = new \ReflectionClass($handler);

            return [
                'id' => $reflectionClass->getShortName(),
                'name' => $handler->name,
                'params' => array_keys($handler->params)
            ];
        }, config('wallethandlers'));
    }
}
