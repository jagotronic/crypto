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
    }
}
