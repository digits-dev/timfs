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
        return DB::table('menu_primary_ingredient')
            ->where('menu_items.status', 'ACTIVE')
            ->select(
                'menu_primary_ingredient.tasteless_menu_code',
                'menu_primary_ingredient.menu_item_description',
                'menu_primary_ingredient.tasteless_code',
                'menu_primary_ingredient.ingredient',
                'menu_primary_ingredient.quantity',
                'menu_primary_ingredient.uom',
                'menu_primary_ingredient.cost',
            )
            ->get()
            ->toArray();
    }
}
