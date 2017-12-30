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
				'apikey' => '39b9169c47bc486ab590fd364c099794',
				'apisecret' => 'ce2cd1aba22b45c5b13a54f829bb9b26'
            ]),
            'description' => ''
        ]);
    }
}
