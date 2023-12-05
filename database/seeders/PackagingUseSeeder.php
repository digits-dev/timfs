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
            'description' => 'NA',
            ],
            [
                'description' => 'NA',
                'status' => 'ACTIVE'
            ]);
    }
}
