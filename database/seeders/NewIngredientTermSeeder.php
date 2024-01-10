<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class NewIngredientTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('new_ingredient_terms')->updateOrInsert([
            'description' => 'ONE-TIME',
            ],
            [
                'description' => 'ONE-TIME',
                'status' => 'ACTIVE'
            ]);

        DB::table('new_ingredient_terms')->updateOrInsert([
            'description' => 'REGULAR',
            ],
            [
                'description' => 'REGULAR',
                'status' => 'ACTIVE'
            ]);
        DB::table('new_ingredient_terms')->updateOrInsert([
            'description' => 'SEASONAL',
            ],
            [
                'description' => 'SEASONAL',
                'status' => 'ACTIVE'
            ]);
    }
}
