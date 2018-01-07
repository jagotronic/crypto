<?php

namespace App\Factories;
use App\Factories\Factory;

class WalletHandlerFactory extends Factory
{
	
	static function get(string $handler)
	{
		$valid_handler = config('wallethandlers');
	    $classPath = 'App\\Services\\Wallets\\' . $handler;

	    if (in_array($classPath, $valid_handler)) {
	    	return new $classPath();
	    }

	    return null;
	}
}