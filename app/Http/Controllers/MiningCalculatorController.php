<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Currencies\Coinmarketcap;
use App\Factories\CurrencyFactory;
use App\Helpers\PHPQuery;

class MiningCalculatorController extends Controller
{
    
    public function index()
    {
//        $hashrate = 22000000;
//
//        $current_block_reward = 9.9;
//
//        $blocks_per_day = 720;
//
//        $network_hashrate = 20708500000;
//
//        dd( $hashrate* $current_block_reward*$blocks_per_day/$network_hashrate);

//        $hashrate = 22000000;
//
//        $current_block_reward = 7.5;
//
//        $blocks_per_day = 576;
//
//
//        $network_hashrate = 33700600000;
//
//        dd( $hashrate* $current_block_reward*$blocks_per_day/$network_hashrate);

        $ct = New \Cryptopia();
        $currencies = $ct->getCurrencies();
//        $trade_pairs = $ct->getTradePairs();

        $coinMarketCapApi = new Coinmarketcap();

        foreach($currencies as $currency) {
//            $btc = $coinMarketCapApi->find($currency['Symbol']);
//            dd($currency);
//            print_r($currency['Symbol'].PHP_EOL);
//            CurrencyFactory::createIfNotExists($currency['Symbol']);

            $uri = 'https://coinmarketcap.com/currencies/'. $currency['Name'];

            $ch = $this->initCurl($uri);
            $result = $this->execute($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            if (empty($result)) {
                $this->throwException(__CLASS__, 'SERVER NOT RESPONDING', $result, $info);
            }

            $dom = \phpQuery::newDocument($result);
            $tags = $dom->find('title="Tags"');
//            $balanceTd = $dom->find('table:eq(0)')->find('tr:eq(1)')->find('td:last');
            dd($tags->html());

        }

        return view('mining-calculator.index', [

        ]);
    }

    protected function initCurl(string $url = null, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return $ch;
    }

    protected function execute($ch, $timeout = 5, $try = 5)
    {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $result = null;

        do {
            $result = curl_exec($ch);
            $try--;
        } while (empty($result) && $try > 0);

        return trim($result);
    }
}
