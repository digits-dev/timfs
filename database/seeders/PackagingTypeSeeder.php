<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PackagingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packaging_types')->updateOrInsert([
            'description' => 'TAKEOUT CONTAINER',
            ],
            [
                'description' => 'TAKEOUT CONTAINER',
                'status' => 'ACTIVE'
            ]);

        DB::table('packaging_types')->updateOrInsert([
            'description' => 'STICKER LABEL',
            ],
            [
                'description' => 'STICKER LABEL',
                'status' => 'ACTIVE'
            ]);
    }
}
