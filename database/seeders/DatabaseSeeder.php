<?php

use Database\Seeders\AccountTypeSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ServcategorySeeder;
use Database\Seeders\ServSubcategorySeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // AdminSeeder::class,
            // AccountTypeSeeder::class,
            // UnitSeeder::class,
            ServcategorySeeder::class,
            // ServSubcategorySeeder::class,
        ]);
    }
}
