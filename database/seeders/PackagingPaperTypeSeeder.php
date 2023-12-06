<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingPaperTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_paper_types')->updateOrInsert([
            'description' => 'CRAFT',
            ],
            [
                'description' => 'CRAFT',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_paper_types')->updateOrInsert([
            'description' => 'CORRUGATED',
            ],
            [
                'description' => 'CORRUGATED',
                'status' => 'ACTIVE'
            ]);
    }
}
