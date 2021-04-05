<?php

namespace Database\Seeders;

use App\Tbl_units;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
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
                'name' => 'Metres',
                'sortname' => 'm',
            ],
            [
                'name' => 'kilograms',
                'sortname' => 'kg',
            ],
            [
                'name' => 'Grams',
                'sortname' => 'g',
            ],
            [
                'name' => 'Feets',
                'sortname' => 'ft',
            ],
            [
                'name' => 'Hour',
                'sortname' => 'hour',
            ],
            [
                'name' => 'Package',
                'sortname' => 'pkg',
            ],
            [
                'name' => 'cm',
                'sortname' => 'cm',
            ],
            [
                'name' => 'Set',
                'sortname' => 'set',
            ],
            [
                'name' => 'Piece',
                'sortname' => 'pcs',
            ],
            [
                'name' => 'Pound',
                'sortname' => 'pound',
            ],
            [
                'name' => 'Sq Mt',
                'sortname' => 'sq mt',
            ],
            [
                'name' => 'Sq Ft',
                'sortname' => 'sq ft',
            ],
            [
                'name' => 'Item',
                'sortname' => 'item',
            ],
            [
                'name' => 'Unit',
                'sortname' => 'unit',
            ],
            [
                'name' => 'SKU',
                'sortname' => 'sku',
            ],
            [
                'name' => 'Other',
                'sortname' => 'other',
            ],
        ];

        Tbl_units::insert($data);
    }
}
