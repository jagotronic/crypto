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
        $mh = curl_multi_init();
        $curl_requests = [];

        foreach (Wallet::all() as $wallet) {
            $url = Asset(URL::route('wallets.refresh', ['id' => $wallet->id], false));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            curl_multi_add_handle($mh, $ch);
        }

        $active = null;
        //execute the handles
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        
        $response['update_all_time'] = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $start_updates_at;

        return $response;
    }

    public static function update(Wallet $wallet) {
        $handler = WalletServiceFactory::get($wallet->handler);
        $handler->handle($wallet);
    }
}
