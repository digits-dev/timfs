<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ProdutionItems implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {

       return DB::table('production_items')
        ->leftJoin('production_item_lines', 'production_items.reference_number', '=', 'production_item_lines.production_item_id')
        ->leftJoin('production_locations', 'production_items.production_location', '=', 'production_locations.id')
        ->leftJoin('production_item_categories', 'production_items.production_category', '=', 'production_item_categories.id')
        ->select(
            'production_items.reference_number',
            'production_items.description as product_description',
            'production_item_categories.category_description',
            'production_locations.production_location_description',
            'production_items.labor_cost',
            'production_items.gas_cost',
            'production_items.utilities',
            'production_items.storage_cost',
            'production_items.total_storage_cost',
            'production_items.depreciation',
            'production_items.raw_mast_provision',
            'production_items.markup_percentage',
            'production_items.final_value_vatex',
            'production_items.final_value_vatinc',
            'production_item_lines.item_code',
            'production_item_lines.description as ingredient_description',
            'production_item_lines.quantity',
            'production_item_lines.landed_cost',
            'production_item_lines.is_alternative',
            'production_items.created_by',
            'production_items.updated_by',
            'production_items.created_at',
            'production_items.updated_at'
        )
        ->orderBy('production_items.id', 'asc');

    }

    public function map($row): array
    {
        return [
            $row->reference_number,
            $row->product_description,
            $row->category_description,
            $row->production_location_description,
            $row->labor_cost,
            $row->gas_cost,
            $row->utilities,
            $row->storage_cost,
            $row->total_storage_cost,
            $row->depreciation,
            $row->raw_mast_provision,
            $row->markup_percentage,
            $row->final_value_vatex,
            $row->final_value_vatinc,
            $row->item_code,
            $row->ingredient_description,
            $row->quantity,
            $row->landed_cost,
            $row->is_alternative,
            $row->created_by,
            $row->updated_by,
            $row->created_at,
            $row->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Reference Number',
            'Productionion Description',
            'Productionion Category',
            'Productionion Location',
            'Labor Cost',
            'Gas Cost',
            'Utilities',
            'Storage Cost',
            'Total Storage Cost',
            'Depreciation',
            'Raw Mast Provision',
            'Markup Percentage',
            'Final Value (VAT Excluded)',
            'Final Value (VAT Included)',
            'Item Code',
            'Ingredient Description',
            'Quantity',
            'Landed Cost',
            'Is Alternative',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];
    }
}
