<?php

namespace App\Helpers;

use App\Wallet;
use App\Currency;
use App\Factories\WalletHandlerFactory;
use App\Helpers\CurrenciesUpdater;

class WalletsUpdater {

	public static function updateAll() {
		$response = [];
		$symbols = [];
		$start_updates_at = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

		foreach (Wallet::all() as $wallet) {
			$status = [
				'wallet' => $wallet->name,
				'id' => $wallet->id,
			];
			$start = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

			$wallet->message = null;

			try {
				WalletsUpdater::update($wallet);
				$status['success'] = true;
			} catch (\Exception $e) {
				$status['success'] = false;
				$status['message'] = $wallet->message = ($wallet->name . ' -- ' . $e->getMessage());
				$status['trace'] = $e->getTraceAsString();
			}

			$wallet->save();

			$symbols = array_unique(
				array_merge(
					$symbols,
					array_pluck($wallet->fresh()->balances->toArray(), 'symbol')
				)
			);

			$status['execution_time'] = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $start;
			$status['symbols'] = $symbols;

			$response[] = $status;
		}

		// $start = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

		// self::createCurrenciesIfNotExists($symbols);

		// $response['MissingCurrency'] = [
		// 	'symbols' => $symbols,
		// 	'execution_time' => (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $start
		// ];
		
		$response['update_all_time'] = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $start_updates_at;

		return $response;
	}

	public static function update(Wallet $wallet) {
		$handler = WalletHandlerFactory::get($wallet->handler);
		$handler->handle($wallet);
	}

	// private static function createCurrenciesIfNotExists($symbols)
	// {
	// 	foreach ($symbols as $symbol) {

	// 		if (Currency::ofSymbol($symbol)->first()) {
	// 			continue;
	// 		}

	// 		$data = CurrenciesUpdater::findCurrency($symbol);

	//         $currency = new Currency();
	//         $currency->name = $data['name'];
	//         $currency->symbol = $data['symbol'];
	//         $currency->api_path = $data['id'];
	//         $currency->usd_value = $data['price_usd'];
	//         $currency->cad_value = $data['price_cad'];
	//         $currency->btc_value = $data['price_btc'];
	// 		$currency->percent_change_1h = $data['percent_change_1h'];
	// 		$currency->percent_change_24h = $data['percent_change_24h'];
	// 		$currency->percent_change_7d = $data['percent_change_7d'];
	//         $currency->description = '';
	//         $currency->save();
	// 	}
	// }
}
