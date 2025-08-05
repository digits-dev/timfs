<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaSubCategories extends Model
{
    use HasFactory;
    protected $table = 'fa_sub_categories';
    protected $fillable = [
        'coa_id',
        'description',
        'description',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
    public function scopeDetail($query, $id){
        return $query
        ->where('coa_id', $id)
        ->where('status', "ACTIVE")
        ->orderby('description', 'ASC')
        ->get();
    }
}
