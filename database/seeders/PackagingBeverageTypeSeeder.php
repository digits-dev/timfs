<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingBeverageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_beverage_types')->updateOrInsert([
            'description' => 'CAP',
            ],
            [
                'description' => 'CAP',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_beverage_types')->updateOrInsert([
            'description' => 'CAP WITH LID',
            ],
            [
                'description' => 'CAP WITH LID',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_beverage_types')->updateOrInsert([
            'description' => 'STRAW',
            ],
            [
                'description' => 'STRAW',
                'status' => 'ACTIVE'
            ]);
    }
}
