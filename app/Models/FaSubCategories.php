<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaSubCategories extends Model
{
    use HasFactory;
    protected $table = 'fa_sub_categories';

    public function scopeDetail($query, $id){
        return $query
        ->where('coa_id', $id)
        ->where('status', "ACTIVE")
        ->orderby('description', 'ASC')
        ->get();
    }
}
