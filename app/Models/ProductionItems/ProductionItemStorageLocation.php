<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemStorageLocation extends Model
{
    use HasFactory;

    protected $table = 'production_item_storage_locations';

    public function scopeActive($query){
        return $query->where('status','ACTIVE')->get();
    }
}
