<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemLinesModelApproval extends Model
{
    use HasFactory;
    

     protected $table = 'production_item_lines_approvals';

    protected $fillable = [
       'id',
        'production_item_id',
        'item_code',
        'description',
        'quantity',
        'landed_cost',
        'yield',
        'packaging_id',
        'ingredient_qty',
        'production_item_line_type',
        'preparations',
        'time_labor',
        'is_alternative',
        'approved_by',
        'production_item_line_id',
        'approval_status',
        'approved_at',
        'created_at',	
        'updated_at'
    ];
}
