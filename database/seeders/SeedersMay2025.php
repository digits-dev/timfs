<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SeedersMay2025 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        self::indexMenu();
        self::productionItems();
        self::submasterMenu();
    }

    public function indexMenu() {
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Production Items',
            ],
            [
                'name'              => 'Production Items',
                'type'              => 'URL',
                'path'              => '#',
                'color'             => 'normal',
                'icon'              => 'fa fa-bookmark-o',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 15
            ]
        );
    }

    public function productionItems() {
        //MODULES
        $modules = [
            [
                'name'         => 'Production Items list',
                'icon'         => 'fa fa-bookmark-o',
                'path'         => 'production_items',
                'table_name'   => 'production_items',
                'controller'   => 'ProductionItems\AdminProductionItemsController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

        //MENUS
        $menus = [
                [
                    'name'              => 'Production Items list',
                    'type'              => 'Route',
                    'path'              => 'ProductionItems\AdminProductionItemsControllerGetIndex',
                    'color'             => NULL,
                    'icon'              => 'fa fa-bookmark-o',
                    'parent_id'         => 115,
                    'is_active'         => 1,
                    'is_dashboard'      => 0,
                    'id_cms_privileges' => 1,
                    'sorting'           => 1
                ]
        ];

        foreach ($menus as $menu) {
            DB::table('cms_menus')->updateOrInsert(['name' => $menu['name']], $menu);
        }

    }

    public function submasterMenu() {
        //MODULES
        $modules = [
            [
                'name'         => 'Item POS',
                'icon'         => 'fa fa-briefcase',
                'path'         => 'item_pos_transactions_backend',
                'table_name'   => 'item_pos_transactions_backend',
                'controller'   => 'ItemPos\AdminItemPosTransactionsBackendController',
                'is_protected' => 0,
                'is_active'    => 0
            ],
            
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }

        //MENUS
        $menus = [
                [
                    'name'              => 'Counter',
                    'type'              => 'Route',
                    'path'              => 'Submaster\AdminCountersControllerGetIndex',
                    'color'             => NULL,
                    'icon'              => 'fa fa-circle-o',
                    'parent_id'         => 4,
                    'is_active'         => 1,
                    'is_dashboard'      => 0,
                    'id_cms_privileges' => 1,
                    'sorting'           => 1
                ]
        ];

        foreach ($menus as $menu) {
            DB::table('cms_menus')->updateOrInsert(['name' => $menu['name']], $menu);
        }

    }
}