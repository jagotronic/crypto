<?php

namespace App\Helpers;

use App\Wallet;
use App\Factories\WalletHandlerFactory;

class WalletsUpdater {

	public static function updateAll() {
		$response = [];

		foreach (Wallet::all() as $wallet) {
			$status = [
				'wallet' => $wallet->name,
				'id' => $wallet->id,
			];

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
			$response[] = $status;
		}

		return $response;
	}

	public static function update(Wallet $wallet) {
		$handler = WalletHandlerFactory::get($wallet->handler);
		$handler->handle($wallet);
	}
}
