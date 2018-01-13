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

    public function refresh()
    {
    	CurrenciesUpdater::update($this);
    }

    static function getHandlers()
    {
        $handlers = [];

        foreach (config('currencyservices') as $classname) {
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
