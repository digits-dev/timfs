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
            'TASTELESS CODE',
            'INGREDIENT',
            'QTY',
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
                'menu_primary_ingredients.tasteless_code',
                'menu_primary_ingredients.ingredient',
                'menu_primary_ingredients.quantity',
                'menu_primary_ingredients.uom',
                'menu_primary_ingredients.cost',
            )
            ->leftJoin('menu_items', 'menu_items.id', 'menu_primary_ingredients.menu_items_id')
            ->orderBy('menu_items.menu_item_description');

        if (in_array(CRUDBooster::myPrivilegeName(), ['Chef', 'Chef Assistant'])) {

            $menu_ids = (new AdminMenuItemsController)->getMyMenuIds();

            $menu_query->whereIn('menu_items.id', $menu_ids);

            if (!$menu_ids) {
                $menu_query->where('menu_items.id', null);
            }
        }

        $menu_items = $menu_query->get()->toArray();
        return $menu_items;
    }
}
