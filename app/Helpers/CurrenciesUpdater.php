<?php

namespace App\Helpers;

use App\Currency;

class CurrenciesUpdater {

	public static function updateAll() {
		echo count(Currency::all());
	}

	public static function update(Currency $currency) {
		echo $currency->id;
	}
}
