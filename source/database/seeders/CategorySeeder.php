<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $categories = array(
            'Lights',
            'Brakes',
            'Electrical',
            'Engine',
            'Bodywork',
            'Exhaust',
            'Paint',
            'Heating & AC',
            'MOT & Service',
            'Clutch',
            'Transmission',
            'Tyres',
            'Steering & Suspension',
            'Customisation & Vehicle Enhancement',
            'Pre\'urchase Inspection/Vehicle Inspection',
            'Keys & Central Locking',
            'Breakdown & Recovery',
            'Mobile Valeting & Detailing',
            'Windscreen & Windows',
            'Other',
        );

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
            ]);
        }
    }
}