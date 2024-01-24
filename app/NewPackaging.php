<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewPackaging extends Model
{
    use HasFactory;

    public function getExportDetails($id = null) {
        if (isset($id)) {
            $this->where('new_packagings.id', $id);
        }

        return $this
            ->where('new_packagings.status', 'ACTIVE')
            ->select(
                'item_masters.tasteless_code',
                'new_packagings.nwp_code',
                'item_masters.full_item_description',
                'new_packagings.packaging_size',
                'uoms.uom_description',
                'new_packagings.ttp',
                'new_packagings.target_date',
                'packaging_types.description as packaging_description',
                'packaging_stickers.description as packaging_stickers',
                'packaging_uniform_types.description as packaging_uniform_types',
                'packaging_material_types.description as packaging_material',
                'packaging_uses.description as packaging_uses',
                'packaging_paper_types.description as packaging_paper',
                'packaging_designs.description as packaging_design',
                'new_packagings.size as size',
                'new_packagings.budget_range as budget_range',
                'new_packagings.reference_link', 
                'new_packagings.initial_qty_needed as initial_qty_needed',
                'new_packagings.forecast_qty_needed as forecast_qty_needed',
                'creator.name as creator_name',
                'new_packagings.created_at',
                'updator.name as updator_name',
                'new_packagings.updated_at',
                'approval_statuses.status_description as approval_status',
                'sourcing_statuses.status_description as sourcing_status',
            )
            ->leftJoin('uoms', 'uoms.id', '=', 'new_packagings.uoms_id')
            ->leftJoin('cms_users as creator', 'creator.id', '=', 'new_packagings.created_by')
            ->leftJoin('cms_users as updator', 'updator.id', '=', 'new_packagings.updated_by')
            ->leftJoin('cms_users as tagger', 'tagger.id', '=', 'new_packagings.tagged_by')
            ->leftJoin('cms_users as approver', 'approver.id', '=', 'new_packagings.approval_status_updated_by')
            ->leftJoin('cms_users as sourcer', 'sourcer.id', '=', 'new_packagings.sourcing_status_updated_by')
            ->leftJoin('item_masters', 'item_masters.id', '=', 'new_packagings.item_masters_id')
            ->leftJoin('new_item_types', 'new_item_types.id', '=', 'new_packagings.new_item_types_id')
            ->leftJoin('packaging_types', 'packaging_types.id', '=', 'new_packagings.packaging_types_id')
            ->leftJoin('packaging_stickers', 'packaging_stickers.id', '=', 'new_packagings.sticker_types_id')
            ->leftJoin('packaging_uniform_types', 'packaging_uniform_types.id', '=', 'new_packagings.packaging_uniform_types_id')
            ->leftJoin('packaging_uses', 'packaging_uses.id', '=', 'new_packagings.packaging_uses_id')
            ->leftJoin('packaging_beverage_types', 'packaging_beverage_types.id', '=', 'new_packagings.packaging_beverage_types_id')
            ->leftJoin('packaging_material_types', 'packaging_material_types.id', '=', 'new_packagings.packaging_material_types_id')
            ->leftJoin('packaging_paper_types', 'packaging_paper_types.id', '=', 'new_packagings.packaging_paper_types_id')
            ->leftJoin('packaging_designs', 'packaging_designs.id', '=', 'new_packagings.packaging_design_types_id')
            ->leftJoin('uoms as initial_uoms', 'initial_uoms.id', '=', 'new_packagings.initial_qty_uoms_id')
            ->leftJoin('uoms as forecast_uoms', 'forecast_uoms.id', '=', 'new_packagings.forecast_qty_uoms_id')
            ->leftJoin('item_approval_statuses as approval_statuses', 'approval_statuses.id', 'new_packagings.item_approval_statuses_id')
            ->leftJoin('item_sourcing_statuses as sourcing_statuses', 'sourcing_statuses.id', 'new_packagings.item_sourcing_statuses_id');
    }
}
