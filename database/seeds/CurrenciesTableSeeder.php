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
        $this->includePersonalSeeds();
    }

    public function includePersonalSeeds()
    {
        if (file_exists(__DIR__ . '/mySeeds/currencies.php')) {
            include(__DIR__ . '/mySeeds/currencies.php');
        }
    }
}
