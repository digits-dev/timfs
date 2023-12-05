<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingMaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_material_types')->updateOrInsert([
            'description' => 'PAPER',
            ],
            [
                'description' => 'PAPER',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_material_types')->updateOrInsert([
            'description' => 'PLASTIC',
            ],
            [
                'description' => 'PLASTIC',
                'status' => 'ACTIVE'
            ]);
    }
}
