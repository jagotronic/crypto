<?php

namespace App\Helpers;

use App\Currency;

class CurrenciesUpdater {
	private static $currencies = [];
	private static $all_currencies = null;

	public static function updateAll() {
		$response = [];

		foreach (Currency::all() as $currency) {
			$status = [
				'wallet' => $currency->symbol,
				'id' => $currency->id,
			];
			$start = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

			try {
				CurrenciesUpdater::update($currency);
				$status['success'] = true;
			} catch (\Exception $e) {
				$status['success'] = false;
				$status['message'] = $currency->symbol . ' -- ' . $e->getMessage();
				$status['trace'] = $e->getTraceAsString();
			}

			$status['execution time'] = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $start;
			$response[] = $status;
		}

		return $response;
	}

	public static function update(Currency $currency) {
		$currencyData = self::fetchCurrency($currency);

		$currency->usd_value = $currencyData['price_usd'];
		$currency->cad_value = $currencyData['price_cad'];
		$currency->btc_value = $currencyData['price_btc'];
		$currency->percent_change_1h = $currencyData['percent_change_1h'];
		$currency->percent_change_24h = $currencyData['percent_change_24h'];
		$currency->percent_change_7d = $currencyData['percent_change_7d'];
		$currency->save();
	}

	public static function fetchCurrency(Currency $currency)
	{
		if (!array_key_exists($currency->symbol, self::$currencies)) {
			$json = self::apiCall('v1/ticker/'.$currency->api_path.'/?convert=CAD');

			if (count($json) !== 1) {
				throw new \Exception('INVALID RESPONSE');
			}

			self::$currencies[$currency->symbol] = array_pop($json);
		}

		return self::$currencies[$currency->symbol];
	}

	private static function apiCall($endpoint)
	{
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL, 'https://api.coinmarketcap.com/' . $endpoint);
		// Execute
		$result = curl_exec($ch);
		// Closing
		curl_close($ch);

		if ($result === false) {
			throw new \Exception('SERVER NOT RESPONDING');
		}

		$json = json_decode($result, true);

		if (!is_array($json)) {
			throw new \Exception('INVALID RESPONSE');
		}

		return $json;
	}

	public static function findCurrency(string $symbol)
	{
		if (is_null(self::$all_currencies)) {
			$json = self::apiCall('v1/ticker/?limit=10000&convert=CAD');

			self::$all_currencies = $json;
		}

		foreach (self::$all_currencies as $currency) {
			if ($currency['symbol'] === $symbol) {
				return $currency;
			}
		}

		return null;
	}
}
