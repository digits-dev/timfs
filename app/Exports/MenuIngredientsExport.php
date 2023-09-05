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

    public function __construct() {
        $quantities = DB::table('batching_ingredients')
            ->select('id', 'quantity')->get()->toArray();
        $this->batching_quantity = [];
        foreach ($quantities as $quantity) {
            $this->batching_quantity[$quantity->id] = $quantity->quantity;
        }
        $this->final_array = [];
    }

    public function headings(): array {
        return [
            'MENU ITEM CODE',
            'MENU ITEM DESCRIPTION',
            'ITEM CODE',
            'INGREDIENT',
            'INGREDIENT QTY',
            'UOM',
        ];
    }

    public function array() : array
    {
        $menu_query =  DB::table('menu_primary_ingredients')
            ->where('menu_items.status', 'ACTIVE')
            ->whereNotNull('menu_items.tasteless_menu_code')
            ->select(
                'menu_primary_ingredients.tasteless_menu_code',
                'menu_primary_ingredients.menu_item_description',
                DB::raw('COALESCE(menu_primary_ingredients.tasteless_code, batching_ingredients.bi_code, new_ingredients.nwi_code) as item_code'),
                'menu_primary_ingredients.ingredient',
                'menu_ingredients_auto_compute.ingredient_qty',
                'menu_primary_ingredients.uom',
                'batching_ingredients.id as batching_as_ingredient_id',
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

        $menu_ingredients = $menu_query->get()->toArray();
        foreach ($menu_ingredients as $ingredient) {
            if ($ingredient->batching_as_ingredient_id) {
                $menu_code = $ingredient->tasteless_menu_code;
                $menu_description = $ingredient->menu_item_description;
                $batching_id = $ingredient->batching_as_ingredient_id;
                $qty = $ingredient->ingredient_qty;
                self::batching_breakdown($menu_code, $menu_description, $batching_id, $qty);
            } else {
                self::appendToFinalArray($ingredient);
            }
        }

        // dd($this->final_array);

        return array_map(function($obj) {
            unset($obj->batching_as_ingredient_id);
            return $obj;
        }, $this->final_array);
    }

    public function batching_breakdown($menu_code, $menu_description, $batching_id, $ingredient_qty) {
        $batching_total_quantity = $this->batching_quantity[$batching_id];
        $qty_multiplier = (float) ($ingredient_qty) / $batching_total_quantity;
        $ingredients = DB::table('batching_primary_ingredients')
            ->where('batching_primary_ingredients.batching_ingredients_id', $batching_id)
            ->select(
                'batching_primary_ingredients.bi_code',
                'batching_primary_ingredients.ingredient_description',
                DB::raw('COALESCE(batching_primary_ingredients.tasteless_code, batching_as_ingredient.bi_code, new_ingredients.nwi_code) as item_code'),
                'batching_primary_ingredients.ingredient',
                DB::raw("batching_ingredients_auto_compute.ingredient_qty * $qty_multiplier as ingredient_qty"),
                'batching_primary_ingredients.uom',
                'batching_as_ingredient.id as batching_as_ingredient_id',
            )
            ->leftJoin('batching_ingredients', 'batching_ingredients.id', 'batching_primary_ingredients.batching_ingredients_id')
            ->leftJoin('batching_ingredients_auto_compute', 'batching_ingredients_auto_compute.id', 'batching_primary_ingredients.batching_ingredients_details_id')
            ->leftJoin('new_ingredients', 'new_ingredients.id', 'batching_ingredients_auto_compute.new_ingredients_id')
            ->leftJoin('batching_ingredients as batching_as_ingredient', 'batching_as_ingredient.id', 'batching_ingredients_auto_compute.batching_as_ingredient_id')
            ->orderBy('batching_ingredients.ingredient_description')
            ->get()
            ->toArray();

        foreach ($ingredients as $ingredient) {
            $ingredient->tasteless_menu_code = $menu_code;
            $ingredient->menu_item_description = $menu_description;
            if ($ingredient->batching_as_ingredient_id) {
                $batching_id = $ingredient->batching_as_ingredient_id;
                $qty = $ingredient->ingredient_qty;
                self::batching_breakdown($menu_code, $menu_description, $batching_id, $qty);
            } else {
                self::appendToFinalArray($ingredient);
            }
        }
    }

    public function appendToFinalArray($ingredient) {
        $menu_code = $ingredient->tasteless_menu_code;
        $item_code = $ingredient->item_code;
        $qty = $ingredient->ingredient_qty;
        $is_existing = array_filter($this->final_array, function($obj) use ($menu_code, $item_code) {
            return $menu_code == $obj->tasteless_menu_code && $item_code == $obj->item_code;
        });

        if ($is_existing) {
            $this->final_array = array_map(function($obj) use ($menu_code, $item_code, $qty) {
                if ($menu_code == $obj->tasteless_menu_code && $item_code == $obj->item_code) {
                    $obj->ingredient_qty += $qty;
                }
                return $obj;
            }, $this->final_array);
        } else {
            $this->final_array[] = (object) [
                'tasteless_menu_code' => $ingredient->tasteless_menu_code,
                'menu_item_description' => $ingredient->menu_item_description,
                'item_code' => $ingredient->item_code,
                'ingredient' => $ingredient->ingredient,
                'ingredient_qty' => $ingredient->ingredient_qty,
                'uom' => $ingredient->uom
            ];
        }
    }
}
