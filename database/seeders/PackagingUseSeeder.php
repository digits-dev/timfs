<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingUseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_uses')->updateOrInsert([
            'description' => 'FOOD',
            ],
            [
                'description' => 'FOOD',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uses')->updateOrInsert([
            'description' => 'BEVERAGE',
            ],
            [
                'description' => 'BEVERAGE',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uses')->updateOrInsert([
            'description' => 'TAKEOUT PACKAGING',
            ],
            [
                'description' => 'TAKEOUT PACKAGING',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uses')->updateOrInsert([
            'description' => 'MARKETING COLLATERALS',
            ],
            [
                'description' => 'MARKETING COLLATERALS',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uses')->updateOrInsert([
            'description' => 'MERCHANDISE',
            ],
            [
                'description' => 'MERCHANDISE',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uses')->updateOrInsert([
            'description' => 'OTHERS',
            ],
            [
                'description' => 'OTHERS',
                'status' => 'ACTIVE'
            ]);
    }
}
