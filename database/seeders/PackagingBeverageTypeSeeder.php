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
            'description' => 'CUP',
            ],
            [
                'description' => 'CUP',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_beverage_types')->updateOrInsert([
            'description' => 'CUP WITH LID',
            ],
            [
                'description' => 'CUP WITH LID',
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
