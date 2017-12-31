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
            'handler' => 'BittrexWalletHandler',
            'data' => json_encode([
                'apikey' => env('BITTREX_APIKEY', null),
                'apisecret' => env('BITTREX_APISECRET', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'My Kucoin account',
            'handler' => 'KucoinWalletHandler',
            'data' => json_encode([
                'apikey' => env('KUCOIN_APIKEY', null),
                'apisecret' => env('KUCOIN_APISECRET', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'My yobit account',
            'handler' => 'YobitWalletHandler',
            'data' => json_encode([
                'apikey' => env('YOBIT_APIKEY', null),
                'apisecret' => env('YOBIT_APISECRET', null),
            ]),
            'description' => ''
        ]);
        // Coinsmarkets API down
        // DB::table('wallets')->insert([
        //     'name' => 'My Coinsmarkets account',
        //     'handler' => 'CoinsmarketsWalletHandler',
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
            'handler' => 'GobyteWalletHandler',
            'data' => json_encode([
                'address' => env('GOBYTE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Solaris wallet',
            'handler' => 'SolarisWalletHandler',
            'data' => json_encode([
                'address' => env('SOLARIS_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'zCash Jaxx wallet (ZEC)',
            'handler' => 'ZcashWalletHandler',
            'data' => json_encode([
                'address' => env('ZCASH_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Desire wallet (DSR)',
            'handler' => 'DesireWalletHandler',
            'data' => json_encode([
                'address' => env('DESIRE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);

        // Pools
        DB::table('wallets')->insert([
            'name' => 'Unimining.ca pool (GBX)',
            'handler' => 'UniminingWalletHandler',
            'data' => json_encode([
                'address' => env('GOBYTE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Unimining.ca pool (DSR)',
            'handler' => 'UniminingWalletHandler',
            'data' => json_encode([
                'address' => env('DESIRE_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'Cryptohub pool (all type)',
            'handler' => 'CryptohubWalletHandler',
            'data' => json_encode([
                'read_key' => env('CRYPTOHUB_READ_KEY', null),
            ]),
            'description' => ''
        ]);
        DB::table('wallets')->insert([
            'name' => 'zec.nanopool.org (ZEC)',
            'handler' => 'ZecNanopoolWalletHandler',
            'data' => json_encode([
                'address' => env('ZCASH_WALLET_ADDRESS', null),
            ]),
            'description' => ''
        ]);


        // MANUAL
        DB::table('wallets')->insert([
            'name' => 'mercatox (ETH) tmp',
            'handler' => 'ManualWalletHandler',
            'data' => json_encode([
                'value' => 0.43887129,
                'currency' => 'ETH'
            ]),
            'description' => ''
        ]);

    }
}
