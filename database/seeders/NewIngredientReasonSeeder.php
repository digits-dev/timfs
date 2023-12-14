<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class NewIngredientReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('new_ingredient_reasons')->updateOrInsert([
            'description' => 'NEW MENU',
            ],
            [
                'description' => 'NEW MENU',
                'status' => 'ACTIVE'
            ]);

        DB::table('new_ingredient_reasons')->updateOrInsert([
            'description' => 'REPLACEMENT OF INGREDIENT',
            ],
            [
                'description' => 'REPLACEMENT OF INGREDIENT',
                'status' => 'ACTIVE'
            ]);
        DB::table('new_ingredient_reasons')->updateOrInsert([
            'description' => 'NEW CONCEPT',
            ],
            [
                'description' => 'NEW CONCEPT',
                'status' => 'ACTIVE'
            ]);

        DB::table('new_ingredient_reasons')->updateOrInsert([
            'description' => 'RND',
            ],
            [
                'description' => 'RND',
                'status' => 'ACTIVE'
            ]);

        DB::table('new_ingredient_reasons')->updateOrInsert([
            'description' => 'OTHERS',
            ],
            [
                'description' => 'OTHERS',
                'status' => 'ACTIVE'
            ]);
    }
}
