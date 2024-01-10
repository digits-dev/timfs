<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingUniformTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_uniform_types')->updateOrInsert([
            'description' => 'APRON',
            ],
            [
                'description' => 'APRON',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uniform_types')->updateOrInsert([
            'description' => 'CAP',
            ],
            [
                'description' => 'CAP',
                'status' => 'ACTIVE'
            ]);
        
        DB::table('packaging_uniform_types')->updateOrInsert([
            'description' => "CHEF'S JACKET",
            ],
            [
                'description' => "CHEF'S JACKET",
                'status' => 'ACTIVE'
            ]);
        
        DB::table('packaging_uniform_types')->updateOrInsert([
            'description' => 'SHORT SLEEVES SHIRT',
            ],
            [
                'description' => 'SHORT SLEEVES SHIRT',
                'status' => 'ACTIVE'
            ]);
        
        DB::table('packaging_uniform_types')->updateOrInsert([
            'description' => 'LONG SLEEVES SHIRT',
            ],
            [
                'description' => 'LONG SLEEVES SHIRT',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uniform_types')->updateOrInsert([
            'description' => 'SHORT SLEEVES POLO SHIRT',
            ],
            [
                'description' => 'SHORT SLEEVES POLO SHIRT',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_uniform_types')->updateOrInsert([
            'description' => 'LONG SLEEVES POLO SHIRT',
            ],
            [
                'description' => 'LONG SLEEVES POLO SHIRT',
                'status' => 'ACTIVE'
            ]);
        
            DB::table('packaging_uniform_types')->updateOrInsert([
                'description' => '3/4 SLEEVE SHIRT',
                ],
                [
                    'description' => '3/4 SLEEVE SHIRT',
                    'status' => 'ACTIVE'
                ]);

            DB::table('packaging_uniform_types')->updateOrInsert([
                'description' => 'NAME PLATE',
                ],
                [
                    'description' => 'NAME PLATE',
                    'status' => 'ACTIVE'
                ]);

            DB::table('packaging_uniform_types')->updateOrInsert([
                'description' => 'FOOTWEAR',
                ],
                [
                    'description' => 'FOOTWEAR',
                    'status' => 'ACTIVE'
                ]);
            
            DB::table('packaging_uniform_types')->updateOrInsert([
                'description' => 'OTHERS',
                ],
                [
                    'description' => 'OTHERS',
                    'status' => 'ACTIVE'
                ]);
    }
}
