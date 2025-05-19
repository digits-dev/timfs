<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemCategory extends Model
{
    use HasFactory;
    protected $table = 'production_item_categories';

    public function scopeActive($query){
        return $query->where('status','ACTIVE')->get();
    }
}
