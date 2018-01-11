<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\WalletsUpdater;
use App\Helpers\CurrenciesUpdater;
use App\Currency;
use App\Wallet;

class SummaryController extends Controller
{
    
    public function index()
    {
        $currencies = $this->fetchCurrencies();
        $balances = $this->fetchBalances($currencies);
        $totals = $this->computeTotals($balances);

        return view('summary.index', [
            'response' => [],
            'currencies' => $currencies,
            'balances' => $balances,
            'totals' => $totals,
        ]);
    }

    public function refresh()
    {
        CurrenciesUpdater::updateAll();
        return WalletsUpdater::updateAll();
    }

    /** Build Currencies */
    private function fetchCurrencies()
    {
        $currencies = [];

        foreach (Currency::all() as $currency) {
            $currencies[$currency->symbol] = array_only($currency->toArray(), [
                'name', 'symbol', 'api_path',
                'usd_value', 'cad_value', 'btc_value',
                'percent_change_1h', 'percent_change_24h', 'percent_change_7d'
            ]);
            $currencies[$currency->symbol]['icon'] = 'https://digitalcoinprice.com/application/modules/assets/images/coins/64x64/'. $currency->api_path .'.png';
            $currencies[$currency->symbol]['web_page'] = 'https://digitalcoinprice.com/'. $currency->api_path;
        }

        return $currencies;
    }

    /** Build Balances */
    private function fetchBalances(array $currencies)
    {
        $balances = [];

        foreach (Wallet::all() as $wallet) {
            foreach ($wallet->balances as $balance) {
                $balance = array_only($balance->toArray(), ['symbol', 'value']);
                $balance['wallet'] = $wallet->name;
                $balance['values'] = [
                    'USD' => 0,
                    'CAD' => 0,
                    'BTC' => 0
                ];

                if (!isset($balances[$balance['symbol']])) {
                    $balances[$balance['symbol']] = [
                        'balances' => [],
                        'currency' => [
                            'name' => !empty($currencies[$balance['symbol']]) ? 
                                      $currencies[$balance['symbol']]['name'] . ' ('. $balance['symbol'] .')' : 
                                      'Unknown ('. $balance['symbol'] .')',
                            'logo' => !empty($currencies[$balance['symbol']]) ? 
                                      'https://digitalcoinprice.com/application/modules/assets/images/coins/64x64/'. $currencies[$balance['symbol']]['api_path'] .'.png' : 
                                      'http://via.placeholder.com/64?text=LOGO'
                        ]
                    ];
                }

                if (!isset($currencies[$balance['symbol']])) {
                    $balance['error'] = 'unknown currency '. $balance['symbol'];
                } else {
                    $balance['values'] = [
                        'USD' => $balance['value'] * $currencies[$balance['symbol']]['usd_value'],
                        'CAD' => $balance['value'] * $currencies[$balance['symbol']]['cad_value'],
                        'BTC' => $balance['value'] * $currencies[$balance['symbol']]['btc_value']
                    ];
                }

                $balances[$balance['symbol']]['balances'][] = $balance;
            }
        }

        return $balances;
    }

    private function computeTotals($balances)
    {
        $totals = [
            'USD' => 0,
            'CAD' => 0,
            'BTC' => 0
        ];
        $currencies = [];

        foreach ($balances as $symbol => $items) {
            $currencies[$symbol] = [
                'USD' => 0,
                'CAD' => 0,
                'BTC' => 0,
                'value' => 0
            ];

            if (empty($items['balances'])) {
                continue;
            }

            foreach ($items['balances'] as $balance) {
                $currencies[$symbol]['USD'] += $balance['values']['USD'];
                $totals['USD'] += $balance['values']['USD'];
                $currencies[$symbol]['CAD'] += $balance['values']['CAD'];
                $totals['CAD'] += $balance['values']['CAD'];
                $currencies[$symbol]['BTC'] += $balance['values']['BTC'];
                $totals['BTC'] += $balance['values']['BTC'];
                $currencies[$symbol]['value'] += $balance['value'];
            }
        }

        return [
            'summary' => $totals,
            'currencies' => $currencies
        ];
    }
}
