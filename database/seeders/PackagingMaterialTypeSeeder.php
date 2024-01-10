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
            
        DB::table('packaging_material_types')->updateOrInsert([
            'description' => 'COTTON',
            ],
            [
                'description' => 'COTTON',
                'status' => 'ACTIVE'
            ]);
        DB::table('packaging_material_types')->updateOrInsert([
            'description' => 'LINEN',
            ],
            [
                'description' => 'LINEN',
                'status' => 'ACTIVE'
            ]);
        DB::table('packaging_material_types')->updateOrInsert([
            'description' => 'DENIM',
            ],
            [
                'description' => 'DENIM',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_material_types')->updateOrInsert([
            'description' => 'OTHERS',
            ],
            [
                'description' => 'OTHERS',
                'status' => 'ACTIVE'
            ]);
    }
}
