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

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Item Masterfile FA',
            ],
            [
                'name'              => 'Item Masterfile FA',
                'type'              => 'Route',
                'path'              => 'AdminItemMastersFasControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 13
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Item FA For Approval',
            ],
            [
                'name'              => 'Item FA For Approval',
                'type'              => 'Route',
                'path'              => 'AdminItemMastersFasApprovalControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-thumbs-o-up',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 6
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Brands Assets',
            ],
            [
                'name'              => 'Brands Assets',
                'type'              => 'Route',
                'path'              => 'AdminBrandsAssetsControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-thumbs-o-up',
                'parent_id'         => 1,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 3
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'History Assets Masterfile',
            ],
            [
                'name'              => 'History Assets Masterfile',
                'type'              => 'Route',
                'path'              => 'AdminHistoryAssetsMasterfilesControllerGetIndex',
                'color'             => NULL,
                'icon'              => 'fa fa-history',
                'parent_id'         => 30,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 3
            ]
        );

    }
}
