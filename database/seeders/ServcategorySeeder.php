<?php

namespace Database\Seeders;

use App\Servcategory;
use Illuminate\Database\Seeder;

class ServcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Automotive',
                // 'slug' => 'automotive',
            ],
            [
                'name' => 'Business Services',
                // 'slug' => 'business-services',
            ],
            [
                'name' => 'Computer, Telecom & IT Services',
                // 'slug' => 'computer-telecom-&-it-services',
            ],
            [
                'name' => 'Education & Training',
                // 'slug' => 'education-&-training',
            ],
            [
                'name' => 'Finance',
                // 'slug' => 'finance',
            ],
            [
                'name' => 'Hospitals, Clinic, Medical',
                // 'slug' => 'hospitals-clinic-medical',
            ],
            [
                'name' => 'Real Estate, Construction, Property',
                // 'slug' => 'real-estate-construction-property',
            ],
            [
                'name' => 'Travel,Toursim & Hotels',
                // 'slug' => 'travel-toursim-&-hotels',
            ],
        ];

        // Servcategory::createMany($categories);
        collect($categories)->each(function ($category) { Servcategory::create($category); });
    }
}
