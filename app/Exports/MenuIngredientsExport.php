<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
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
        return DB::table('menu_primary_ingredients')
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
            ->orderBy('menu_items.menu_item_description')
            ->get()
            ->toArray();
    }
}
