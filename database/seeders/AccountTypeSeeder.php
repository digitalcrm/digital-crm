<?php

namespace Database\Seeders;

use App\Tbl_accounttypes;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'account_type' => 'Manufacturer',
            ],
            [
                'account_type' => 'Exporter',
            ],
            [
                'account_type' => 'Wholesaler',
            ],
            [
                'account_type' => 'Dealer',
            ],
            [
                'account_type' => 'Retailer',
            ],
            [
                'account_type' => 'Service',
            ],
            [
                'account_type' => 'Franchise',
            ],
            [
                'account_type' => 'Other',
            ],
        ];

        Tbl_accounttypes::insert($data);
    }
}
