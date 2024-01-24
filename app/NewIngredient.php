<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewIngredient extends Model
{
    use HasFactory;
    protected $table = 'new_ingredients';
    protected $guarded = [];

    public function getExportDetails($id = null) {
        if (isset($id)) {
            $this->where('new_ingredients.id', $id);
        }

        return $this
            ->where('new_ingredients.status', 'ACTIVE')
            ->select(
                'item.tasteless_code',
                'new_ingredients.nwi_code',
                'new_ingredients.item_description',
                'new_ingredients.packaging_size',
                'item_uom.uom_description',
                'new_ingredients.ttp',
                'new_ingredients.target_date',
                'new_ingredients.segmentations',
                'new_ingredient_reasons.description as reasons_description',
                'new_ingredients.recommended_brand_one',
                'new_ingredients.recommended_brand_two',
                'new_ingredients.recommended_brand_three',
                'new_ingredients.initial_qty_needed',
                'new_ingredients.forecast_qty_needed',
                'new_ingredients.budget_range',
                'new_ingredients.reference_link', 
                'new_ingredient_terms.description as ingredient_terms',
                'new_ingredients.duration', 
                'new_ingredients.created_at',
                'creator.name as creator_name',
                'new_ingredients.updated_at',
                'updator.name as updator_name',
                'approval_statuses.status_description as approval_status',
                'sourcing_statuses.status_description as sourcing_status',
            )
            ->leftJoin('uoms as item_uom', 'item_uom.id', '=', 'new_ingredients.uoms_id')
            ->leftJoin('cms_users as creator', 'creator.id', '=', 'new_ingredients.created_by')
            ->leftJoin('cms_users as updator', 'updator.id', '=', 'new_ingredients.updated_by')
            ->leftJoin('cms_users as tagger', 'tagger.id', '=', 'new_ingredients.tagged_by')
            ->leftJoin('cms_users as approver', 'approver.id', '=', 'new_ingredients.approval_status_updated_by')
            ->leftJoin('cms_users as sourcer', 'sourcer.id', '=', 'new_ingredients.sourcing_status_updated_by')
            ->leftJoin('item_masters as item', 'item.id', '=', 'new_ingredients.item_masters_id')
            ->leftJoin('new_item_types', 'new_item_types.id', '=', 'new_ingredients.new_item_types_id')
            ->leftJoin('new_ingredient_reasons', 'new_ingredient_reasons.id', '=', 'new_ingredients.new_ingredient_reasons_id')
            ->leftJoin('uoms as initial_uoms', 'initial_uoms.id', '=', 'new_ingredients.initial_qty_uoms_id')
            ->leftJoin('uoms as forecast_uoms', 'forecast_uoms.id', '=', 'new_ingredients.forecast_qty_uoms_id')
            ->leftJoin('new_ingredient_terms', 'new_ingredient_terms.id', '=', 'new_ingredients.new_ingredient_terms_id')
            ->leftJoin('item_masters as existing', 'existing.tasteless_code', '=', 'new_ingredients.existing_ingredient')
            ->leftJoin('item_approval_statuses as approval_statuses', 'approval_statuses.id', 'new_ingredients.item_approval_statuses_id')
            ->leftJoin('item_sourcing_statuses as sourcing_statuses', 'sourcing_statuses.id', 'new_ingredients.item_sourcing_statuses_id');
    }
}
