<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemLines extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'production_item_id',
        'item_code',
        'description',
        'quantity',
        'landed_cost',
        'is_alternative'
    ];
}
