<?php

namespace App\Factories;

use App\Factories\Factory;

class CurrencyServiceFactory extends Factory
{
    
    public static function get(string $service)
    {
        if (self::isValidService($service)) {
            $classPath = self::getClassPath($service);
            return new $classPath();
        }

        return null;
    }

    public static function isValidService(string $service)
    {
        $valid_handler = config('currencyservices');
        $classPath = self::getClassPath($service);

        return in_array($classPath, $valid_handler);
    }

    public static function getClassPath(string $service)
    {
        return 'App\\Services\\Currencies\\' . $service;
    }
}