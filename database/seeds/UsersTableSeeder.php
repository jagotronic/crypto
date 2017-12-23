<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Sebastien Rodrigue',
            'email' => 'sebastien@srodrigue.com',
            'password' => bcrypt('pastouche')
        ]);
    }
}
