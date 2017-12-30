<?php

namespace App\Helpers;

use App\Wallet;
use App\Factories\WalletHandlerFactory;

class WalletsUpdater {

	public static function updateAll() {
		foreach (Wallet::all() as $wallet) {
			WalletsUpdater::update($wallet);
		}
	}

	public static function update(Wallet $wallet) {
		$handler = WalletHandlerFactory::get($wallet->handler);
		$handler->handle($wallet);
	}
}
