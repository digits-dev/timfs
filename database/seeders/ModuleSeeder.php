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
            ],
            [
                'name'         => 'Asset Masterfile',
                'icon'         => 'fa fa-bookmark-o',
                'path'         => 'item_masters_fas',
                'table_name'   => 'item_masters_fas',
                'controller'   => 'AdminItemMastersFasController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Asset Masterfile For Approval',
                'icon'         => 'fa fa-thumbs-o-up',
                'path'         => 'item_masters_fas_approvals',
                'table_name'   => 'item_masters_fas_approvals',
                'controller'   => 'AdminItemMastersFasApprovalController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'Brands Assets',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'brands_assets',
                'table_name'   => 'brands_assets',
                'controller'   => 'AdminBrandsAssetsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            [
                'name'         => 'History Assets Masterfile',
                'icon'         => 'fa fa-history',
                'path'         => 'history_assets_masterfiles',
                'table_name'   => 'history_assets_masterfiles',
                'controller'   => 'AdminHistoryAssetsMasterfilesController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }
    }
}
