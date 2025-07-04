<?php

namespace App\Exports;

use App\Models\ItemMastersFa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use CRUDBooster;
use DB;

class ItemAssetsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array {

        $header = [
            'UPC Code',
            'Tasteless Code',
            'Item Description',
            'Coa',
            'Sub Category',
            'Cost',
            'Brand Name',
            'Supplier Item Code',
            'Model',
            'Size',
            'Color',
            'Asset Type',
            'Vendor Name 1',
            'Vendor Name 2',
            'Vendor Name 3',
            'Vendor Name 4',
            'Vendor Name 5',
            'Approved By',
            'Approved At',
            'Created By',
            'Created At',
            'Updated By',
            'Updated At'
        ];

        return $header;
    }

    public function map($data_item): array {
        
        $item_mapping = [
            $data_item->upc_code, 
            $data_item->tasteless_code, 
            $data_item->item_description, 
            $data_item->coa_desc, 
            $data_item->sub_coa_desc, 
            $data_item->cost, 
            $data_item->brand_description, 
            $data_item->supplier_item_code, 
            $data_item->model, 
            $data_item->size, 
            $data_item->color, 
            $data_item->asset_type_description, 
            $data_item->vendor1_id, 
            $data_item->vendor2_id, 
            $data_item->vendor3_id, 
            $data_item->vendor4_id, 
            $data_item->vendor5_id, 
            $data_item->approver_name, 
            $data_item->approved_at, 
            $data_item->creator_name, 
            $data_item->created_at, 
            $data_item->updater_name, 
            $data_item->updated_at, 
        ];

        return $item_mapping;
    }

    public function query() {        
        $items = ItemMastersFa::query() 
        ->join('asset_types','item_masters_fas.asset_type','=','asset_types.id')
        ->leftJoin('brands_assets','item_masters_fas.brand_id','=','brands_assets.id')
        ->join('fa_coa_categories','item_masters_fas.categories_id','=','fa_coa_categories.id')
        ->join('fa_sub_categories','item_masters_fas.subcategories_id','=','fa_sub_categories.id')
        ->leftJoin('cms_users as creator','item_masters_fas.created_by','=','creator.id')
        ->leftJoin('cms_users as approver','item_masters_fas.updated_by','=','approver.id')
        ->leftJoin('cms_users as updater','item_masters_fas.updated_by','=','updater.id')
        ->select(
            'item_masters_fas.upc_code', 
            'item_masters_fas.tasteless_code', 
            'item_masters_fas.item_description', 
            'fa_coa_categories.description as coa_desc', 
            'fa_sub_categories.description as sub_coa_desc', 
            'item_masters_fas.cost', 
            'brands_assets.brand_description', 
            'item_masters_fas.supplier_item_code', 
            'item_masters_fas.model', 
            'item_masters_fas.size', 
            'item_masters_fas.color',
            'asset_types.asset_type_description', 
            'item_masters_fas.vendor1_id', 
            'item_masters_fas.vendor2_id', 
            'item_masters_fas.vendor3_id', 
            'item_masters_fas.vendor4_id', 
            'item_masters_fas.vendor5_id', 
            'approver.name as approver_name', 
            'item_masters_fas.approved_at', 
            'creator.name as creator_name', 
            'item_masters_fas.created_at', 
            'updater.name as updater_name', 
            'item_masters_fas.updated_at', 
        );

        return $items;

    }
}
