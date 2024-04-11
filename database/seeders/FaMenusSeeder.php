<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class FaMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::faSubmasterMenu();
    }

    public function faSubmasterMenu() {
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'FA Coa',
            ],
            [
                'name'              => 'FA Coa',
                'type'              => 'Route',
                'path'              => 'AdminFaCoaCategoriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-object-group',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 30
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'FA Sub Categories',
            ],
            [
                'name'              => 'FA Sub Categories',
                'type'              => 'Route',
                'path'              => 'AdminFaCoaSubCategoriesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 31
            ]
        );

    }
}
