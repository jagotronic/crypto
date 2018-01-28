<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Factories\CurrencyServiceFactory;
use App\Factories\CurrencyFactory;

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
        $this->message = null;

        try {
            $handler = CurrencyServiceFactory::get($this->handler);

            if (empty($handler)) {
                CurrencyFactory::updateCurrencyService($this);
                $handler = CurrencyServiceFactory::get($this->handler);
            }

            $handler->handle($this);
        } catch (\Exception $e) {
            $message = json_decode($e->getMessage(), true);

            if (is_null($message)) {
                $message = $e->getMessage();
            }

            if (!is_array($message)) {
                $message = ['message' => $message];
            }

            $message['trace'] = $e->getTraceAsString();
            $this->message = json_encode($message);
        }

        if ($this->isDirty()) {
            $this->save();
            $this->fresh();
        }

        return $this;
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
