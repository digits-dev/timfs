<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Controllers\AdminMenuItemsController;
use CRUDBooster;
use DB;

class MenuIngredientsExport  implements FromArray, WithHeadings
{
    use Exportable;

    public function headings(): array {
        return [
            'MENU ITEM CODE',
            'MENU ITEM DESCRIPTION',
            'ITEM CODE',
            'INGREDIENT',
            'PREPARATION QTY',
            'INGREDIENT QTY',
            'UOM',
            'INGREDIENT COST',
        ];
    }

    public function array() : array
    {

        $menu_query =  DB::table('menu_primary_ingredients')
            ->where('menu_items.status', 'ACTIVE')
            ->select(
                'menu_primary_ingredients.tasteless_menu_code',
                'menu_primary_ingredients.menu_item_description',
                DB::raw('COALESCE(menu_primary_ingredients.tasteless_code, batching_ingredients.bi_code, new_ingredients.nwi_code)'),
                'menu_primary_ingredients.ingredient',
                'menu_ingredients_auto_compute.prep_qty',
                'menu_ingredients_auto_compute.ingredient_qty',
                'menu_primary_ingredients.uom',
                'menu_primary_ingredients.cost',
            )
            ->leftJoin('menu_items', 'menu_items.id', 'menu_primary_ingredients.menu_items_id')
            ->leftJoin('menu_ingredients_auto_compute', 'menu_ingredients_auto_compute.id', 'menu_primary_ingredients.menu_ingredients_details_id')
            ->leftJoin('new_ingredients', 'new_ingredients.id', 'menu_ingredients_auto_compute.new_ingredients_id')
            ->leftJoin('batching_ingredients', 'batching_ingredients.id', 'menu_ingredients_auto_compute.batching_ingredients_id')
            ->orderBy('menu_items.menu_item_description');

        if (in_array(CRUDBooster::myPrivilegeName(), ['Chef', 'Chef Assistant'])) {
            $menu_ids = (new AdminMenuItemsController)->getMyMenuIds();
            $menu_query->whereIn('menu_items.id', $menu_ids);
        }

        $menu_items = $menu_query->get()->toArray();
        return $menu_items;
    }
}
