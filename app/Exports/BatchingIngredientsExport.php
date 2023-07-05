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
            'TASTELESS CODE',
            'INGREDIENT',
            'QTY',
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
                'batching_primary_ingredients.tasteless_code',
                'batching_primary_ingredients.ingredient',
                'batching_primary_ingredients.quantity',
                'batching_primary_ingredients.uom',
                'batching_primary_ingredients.cost',
            )
            ->leftJoin('batching_ingredients', 'batching_ingredients.id', 'batching_primary_ingredients.batching_ingredients_id')
            ->orderBy('batching_ingredients.ingredient_description')
            ->get()
            ->toArray();
    }
}
