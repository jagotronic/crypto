<?php

namespace App\Helpers;

use App\Wallet;
use App\Factories\WalletServiceFactory;
use URL;
use Asset;

class WalletsUpdater {

    public static function updateAll() {
        $response = [];
        $start_updates_at = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        foreach (Wallet::all() as $wallet) {
            try {
                self::update($wallet);
            } catch (\Exception $e) {
                $response[] = $e->getMessage();
            }
        }

        $response['update_all_time'] = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $start_updates_at;

        return $response;
    }

    public static function update(Wallet $wallet) {
        return $wallet->refresh();
    }
}
