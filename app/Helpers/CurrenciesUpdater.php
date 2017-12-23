<?php

namespace App\Helpers;

use App\Currency;

class CurrenciesUpdater {

	public static function updateAll() {
		foreach (Currency::all() as $currency) {
			CurrenciesUpdater::update($currency);
		}
	}

	public static function update(Currency $currency) {
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL, 'https://api.coinmarketcap.com/v1/ticker/'.$currency->api_path.'/?convert=CAD');
		// Execute
		$result=curl_exec($ch);
		// Closing
		curl_close($ch);

		$json = json_decode($result, true);
		$currency->usd_value = $json[0]['price_usd'];
		$currency->cad_value = $json[0]['price_cad'];
		$currency->btc_value = $json[0]['price_btc'];

		$currency->save();
	}
}
