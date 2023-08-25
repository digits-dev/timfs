<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use CRUDBooster;
use DB;

class BatchingIngredientsExport implements FromArray, WithHeadings
{
    use Exportable;

    public function headings(): array {
        return [
            'BATCHING ITEM CODE',
            'BATCHING ITEM DESCRIPTION',
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
        return DB::table('batching_primary_ingredients')
            ->where('batching_ingredients.status', 'ACTIVE')
            ->select(
                'batching_primary_ingredients.bi_code',
                'batching_primary_ingredients.ingredient_description',
                DB::raw('COALESCE(batching_primary_ingredients.tasteless_code, batching_as_ingredient.bi_code, new_ingredients.nwi_code)'),
                'batching_primary_ingredients.ingredient',
                'batching_ingredients_auto_compute.prep_qty',
                'batching_ingredients_auto_compute.ingredient_qty',
                'batching_primary_ingredients.uom',
                'batching_primary_ingredients.cost',
            )
            ->leftJoin('batching_ingredients', 'batching_ingredients.id', 'batching_primary_ingredients.batching_ingredients_id')
            ->leftJoin('batching_ingredients_auto_compute', 'batching_ingredients_auto_compute.id', 'batching_primary_ingredients.batching_ingredients_details_id')
            ->leftJoin('new_ingredients', 'new_ingredients.id', 'batching_ingredients_auto_compute.new_ingredients_id')
            ->leftJoin('batching_ingredients as batching_as_ingredient', 'batching_as_ingredient.id', 'batching_ingredients_auto_compute.batching_as_ingredient_id')
            ->orderBy('batching_ingredients.ingredient_description')
            ->get()
            ->toArray();
    }
}
