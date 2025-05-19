<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'name',
        'description',
        'production_category', 
        'production_location',
        'packaging_id',
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
    ];


    public function itemLines(): HasMany{
        return $this->hasMany(ProductionItemLines::class, 'production_item_id');
    }
}


