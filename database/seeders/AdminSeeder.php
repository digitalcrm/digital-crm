<?php

namespace Database\Seeders;

use App\Admin;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            [
                'name' => 'Administrator',
                'email' => 'admin@bigindia.com',
                'password' => Hash::make('Bigindia@Aynsoft#321.com'), //password
                'remember_token' => Str::random(10),
                'user_type' => 2,
                'cr_id' => 2,
                'verified' => 1,
                'user_status' => 2,
            ],
        ];
        Admin::insert($admin);
    }
}
