<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_designs')->updateOrInsert([
            'description' => 'GENERIC',
            ],
            [
                'description' => 'GENERIC',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_designs')->updateOrInsert([
            'description' => 'CUSTOMIZED',
            ],
            [
                'description' => 'CUSTOMIZED',
                'status' => 'ACTIVE'
            ]);
    }
}
