<?php

use Illuminate\Database\Seeder;

class WalletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // EXCHANGES
        DB::table('wallets')->insert([
            'name' => 'My Bittrex account',
            'handler' => 'BittrexExchange',
            'data' => json_encode([
                'apikey' => env('BITTREX_APIKEY', null),
                'apisecret' => env('BITTREX_APISECRET', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'My Kucoin account',
            'handler' => 'KucoinExchange',
            'data' => json_encode([
                'apikey' => env('KUCOIN_APIKEY', null),
                'apisecret' => env('KUCOIN_APISECRET', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'My yobit account',
            'handler' => 'YobitExchange',
            'data' => json_encode([
                'apikey' => env('YOBIT_APIKEY', null),
                'apisecret' => env('YOBIT_APISECRET', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'My Cryptopia account',
            'handler' => 'CryptopiaExchange',
            'data' => json_encode([
                'apikey' => env('CRYPTOPIA_APIKEY', null),
                'apisecret' => env('CRYPTOPIA_APISECRET', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Quadrigacx',
            'handler' => 'QuadrigacxExchange',
            'data' => json_encode([
                'apikey' => env('QUADRIGACX_APIKEY', null),
                'apisecret' => env('QUADRIGACX_APISECRET', null),
                'clientId' => env('QUADRIGACX_CLIENT_ID', null),
            ]),
            'description' => ''
        ]);
        // Coinsmarkets API down
        // DB::table('wallets')->insert([
        //     'name' => 'My Coinsmarkets account',
        //     'handler' => 'CoinsmarketsExchange',
        //     'data' => json_encode([
        //         'username' => env('COINSMARKETS_USERNAME', null),
        //         'password' => env('COINSMARKETS_PASSWORD', null),
        //         'pin' => env('COINSMARKETS_PIN', null),
        //     ]),
        //     'description' => ''
        // ]);

        // WALLETS
        DB::table('wallets')->insert([
            'name' => 'Gobyte wallet',
            'handler' => 'GobyteWallet',
            'data' => json_encode([
                'address' => env('GOBYTE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Solaris wallet',
            'handler' => 'SolarisWallet',
            'data' => json_encode([
                'address' => env('SOLARIS_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'zCash Jaxx wallet (ZEC)',
            'handler' => 'ZcashWallet',
            'data' => json_encode([
                'address' => env('ZCASH_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Desire wallet (DSR)',
            'handler' => 'DesireWallet',
            'data' => json_encode([
                'address' => env('DESIRE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);

        // Pools
        DB::table('wallets')->insert([
            'name' => 'Unimining.ca pool (GBX)',
            'handler' => 'UniminingPool',
            'data' => json_encode([
                'address' => env('GOBYTE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Unimining.ca pool (DSR)',
            'handler' => 'UniminingPool',
            'data' => json_encode([
                'address' => env('DESIRE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Mineproject.ru (DSR)',
            'handler' => 'MineprojectPool',
            'data' => json_encode([
                'address' => env('GOA_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Cryptohub pool (all type)',
            'handler' => 'CryptohubPool',
            'data' => json_encode([
                'read_key' => env('CRYPTOHUB_READ_KEY', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'zec.nanopool.org (ZEC)',
            'handler' => 'ZecNanopoolPool',
            'data' => json_encode([
                'address' => env('ZCASH_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);


        // Others
        DB::table('wallets')->insert([
            'name' => 'mercatox (ETH) tmp',
            'handler' => 'Manual',
            'data' => json_encode([
                'value' => 0.20887129,
                'currency' => 'ETH'
            ]),
            'description' => ''
        ]);
    }
}
