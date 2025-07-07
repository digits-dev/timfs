<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemsModelApproval extends Model
{
    use HasFactory;

    protected $table = 'production_items_approvals';

    protected $fillable = [
        'reference_number',
        'description', 
        'production_category',
        'production_location', 
        'labor_cost',
        'gas_cost',
        'storage_cost',
        'storage_multiplier',
        'total_storage_cost',
        'storage_location',
        'depreciation',
        'raw_mast_provision',
        'markup_percentage',
        'final_value_vatex',
        'final_value_vatinc',
        'approved_by',
        'approval_status',
        'approved_at',
        'created_by',
        'updated_by',
    ];
}
