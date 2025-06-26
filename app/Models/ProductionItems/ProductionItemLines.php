<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemLines extends Model
{
    use HasFactory;


    protected $table = 'production_item_lines';

    protected $fillable = [
        'production_item_id',
        'item_code',
        'description',
        'quantity',
        'landed_cost',
        'yield',
        'ingredient_qty',
        'is_alternative',
        'created_at',	
        'updated_at'
    ];
}
