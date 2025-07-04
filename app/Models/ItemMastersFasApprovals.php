<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMastersFasApprovals extends Model
{
    use HasFactory;
    protected $table = 'item_masters_fas_approvals';
    protected $fillable = [
        'action_type', 
        'tasteless_code', 
        'upc_code',
        'supplier_item_code',
        'brand_id',
        'vendor1_id',
        'vendor2_id',
        'vendor3_id',
        'vendor4_id',
        'vendor5_id',
        'item_description',
        'model',
        'size',
        'color',
        'asset_type',
        'categories_id',
        'subcategories_id',
        'cost',
        'currency_id',
        'image_filename',
        'approval_status',
        'sku_statuses_id',
        'created_by',
        'created_at',
        'approved_by',
        'approved_at',
        'updated_by',
        'updated_at'
    ];
}
