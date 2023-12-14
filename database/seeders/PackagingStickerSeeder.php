<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingStickerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_stickers')->updateOrInsert([
            'description' => 'SATIN',
            ],
            [
                'description' => 'SATIN',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_stickers')->updateOrInsert([
            'description' => 'MATTE',
            ],
            [
                'description' => 'MATTE',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_stickers')->updateOrInsert([
            'description' => 'LAMINATED',
            ],
            [
                'description' => 'LAMINATED',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_stickers')->updateOrInsert([
            'description' => 'VINYL',
            ],
            [
                'description' => 'VINYL',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_stickers')->updateOrInsert([
            'description' => 'OTHERS',
            ],
            [
                'description' => 'OTHERS',
                'status' => 'ACTIVE'
            ]);
    }
}
