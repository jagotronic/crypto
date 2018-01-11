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
        $this->includePersonalSeeds();
    }

    public function includePersonalSeeds()
    {
        if (file_exists(__DIR__ . '/mySeeds/users.php')) {
            include(__DIR__ . '/mySeeds/users.php');
        }
    }
}
