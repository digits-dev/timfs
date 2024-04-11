<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            [
                'name'         => 'FA Coa',
                'icon'         => 'fa fa-object-group',
                'path'         => 'fa_coa_categories',
                'table_name'   => 'fa_coa_categories',
                'controller'   => 'AdminFaCoaCategoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'FA Sub Categories',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'fa_coa_sub_categories',
                'table_name'   => 'fa_coa_sub_categories',
                'controller'   => 'AdminFaCoaSubCategoriesController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }
    }
}
