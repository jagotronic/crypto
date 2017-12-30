<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'name' => 'Desire',
            'symbol' => 'DSR',
            'api_path' => 'desire',
            'description' => ''
        ]);
        DB::table('currencies')->insert([
            'name' => 'GoByte',
            'symbol' => 'GBX',
            'api_path' => 'gobyte',
            'description' => ''
        ]);
        DB::table('currencies')->insert([
            'name' => 'zCash',
            'symbol' => 'ZEC',
            'api_path' => 'zcash',
            'description' => ''
        ]);
        DB::table('currencies')->insert([
            'name' => 'AltCommunity Coin',
            'symbol' => 'ALTCOM',
            'api_path' => 'altcommunity-coin',
            'description' => ''
        ]);
        DB::table('currencies')->insert([
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
            'api_path' => 'bitcoin',
            'description' => ''
        ]);
        DB::table('currencies')->insert([
            'name' => 'Ethereum',
            'symbol' => 'ETH',
            'api_path' => 'ethereum',
            'description' => ''
        ]);
    }
}
