<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name' => 'Item Sourcing Matrix', 
                'path' => 'AdminItemSourcingMatricesController',
                'modul' => [
                    'path' => 'item_sourcing_matrices',
                    'table_name' => 'item_sourcing_matrices',
                ]
            ],
            [
                'name' => 'Packaging Beverage Types', 
                'path' => 'AdminPackagingBeverageTypesController',
                'modul' => [
                    'path' => 'packaging_beverage_types',
                    'table_name' => 'packaging_beverage_types',
                ]
            ],
            [
                'name' => 'Packaging Designs', 
                'path' => 'AdminPackagingDesignsController',
                'modul' => [
                    'path' => 'packaging_designs',
                    'table_name' => 'packaging_designs',
                ]
            ],
            [
                'name' => 'Packaging Material Types', 
                'path' => 'AdminPackagingMaterialTypesController',
                'modul' => [
                    'path' => 'packaging_material_types',
                    'table_name' => 'packaging_material_types',
                ]
            ],
            [
                'name' => 'Packaging Paper Types', 
                'path' => 'AdminPackagingPaperTypesController',
                'modul' => [
                    'path' => 'packaging_paper_types',
                    'table_name' => 'packaging_paper_types',
                ]
            ],
            [
                'name' => 'Packaging Stickers', 
                'path' => 'AdminPackagingStickersController',
                'modul' => [
                    'path' => 'packaging_stickers',
                    'table_name' => 'packaging_stickers',
                ]
            ],
            [
                'name' => 'Packaging Types', 
                'path' => 'AdminPackagingTypesController',
                'modul' => [
                    'path' => 'packaging_types',
                    'table_name' => 'packaging_types',
                ]
            ],
            [
                'name' => 'Packaging Uses', 
                'path' => 'AdminPackagingUsesController',
                'modul' => [
                    'path' => 'packaging_uses',
                    'table_name' => 'packaging_uses',
                ]
            ],
            [
                'name' => 'Reasons', 
                'path' => 'AdminNewIngredientReasonsController',
                'modul' => [
                    'path' => 'new_ingredient_reasons',
                    'table_name' => 'new_ingredient_reasons',
                ]
            ],
            [
                'name' => 'Terms', 
                'path' => 'AdminNewIngredientTermsController',
                'modul' => [
                    'path' => 'new_ingredient_terms',
                    'table_name' => 'new_ingredient_terms',
                ]
            ],
        ];

        $parent_id = DB::table('cms_menus')
            ->where('name', 'Item Sourcing Submaster')
            ->pluck('id')
            ->first();

        if (!$parent_id) {
            $last_sorting = DB::table('cms_menus')
                ->where('parent_id', 0)
                ->max('sorting');

            $parent_id = DB::table('cms_menus')->insertGetId([
                'name' => 'Item Sourcing Submaster',
                'type' => 'URL',
                'path' => '#',
                'color' => 'normal',
                'icon' => 'fa fa-navicon',
                'parent_id' => 0,
                'is_active' => 1,
                'is_dashboard' => 0,
                'id_cms_privileges' => 1,
                'sorting' => $last_sorting + 1,
            ]);
        }

        foreach ($menus as $key => $menu) {
            DB::table('cms_menus')->updateOrInsert(['name' => $menu['name']], [
                'name' => $menu['name'],
                'type' => 'Route',
                'path' => $menu['path'] . 'GetIndex',
                'color' => null,
                'icon' => 'fa fa-circle-o',
                'parent_id' => $parent_id,
                'is_active' => 1,
                'is_dashboard' => 0,
                'id_cms_privileges' => 1,
                'sorting' => $key,
            ]);

            $id_cms_menus = DB::table('cms_menus')
                ->where('name', $menu['name'])
                ->pluck('id')
                ->first();

            DB::table('cms_menus_privileges')->updateOrInsert(['id_cms_menus' => $id_cms_menus, 'id_cms_privileges' => 1], [
                'id_cms_menus' => $id_cms_menus, 
                'id_cms_privileges' => 1
            ]);

            DB::table('cms_moduls')->updateOrInsert(['name' => $menu['name']], [
                'name' => $menu['name'],
                'icon' => 'fa fa-circle-o',
                'path' => $menu['modul']['path'],
                'table_name' => $menu['modul']['table_name'],
                'controller' => $menu['path'],
                'is_protected' => 0,
                'is_active' => 0
            ]);
        }

        self::addHistoryModule();
    }

    public function addHistoryModule() {
        $history_module_id = DB::table('cms_menus')
            ->where('name', 'History Module')
            ->pluck('id')
            ->first();

        $menu_name = 'Sales Price Change History';
        $menu_controller = 'AdminSalesPriceChangeHistoriesController';
        $path = 'sales_price_change_histories';
        $table_name = 'sales_price_change_histories';
        $sorting = DB::table('cms_menus')
            ->where('parent_id', $history_module_id)
            ->max('sorting');

        DB::table('cms_menus')->updateOrInsert(['name' => $menu_name], [
            'name' => $menu_name,
            'type' => 'Route',
            'path' => $menu_controller . 'GetIndex',
            'color' => null,
            'icon' => 'fa fa-circle-o',
            'parent_id' => $history_module_id,
            'is_active' => 1,
            'is_dashboard' => 0,
            'id_cms_privileges' => 1,
            'sorting' => $sorting + 1,
        ]);

        $id_cms_menus = DB::table('cms_menus')
            ->where('name', $menu_name)
            ->pluck('id')
            ->first();

        DB::table('cms_menus_privileges')->updateOrInsert(['id_cms_menus' => $id_cms_menus, 'id_cms_privileges' => 1], [
            'id_cms_menus' => $id_cms_menus, 
            'id_cms_privileges' => 1
        ]);

        DB::table('cms_moduls')->updateOrInsert(['name' => $menu_name], [
            'name' => $menu_name,
            'icon' => 'fa fa-circle-o',
            'path' => $path,
            'table_name' => $table_name,
            'controller' => $menu_controller,
            'is_protected' => 0,
            'is_active' => 0,
        ]);

    }
}
