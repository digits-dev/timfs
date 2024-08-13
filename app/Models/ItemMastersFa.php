<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CRUDBooster;
class ItemMastersFa extends Model
{
    protected $table = 'item_masters_fas';

    protected $guarded = [];

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

    public function scopeGetItemByTastelessCode($query,$tasteless_code) {
        return $query->where('tasteless_code', $tasteless_code)->first();
    }

    public function scopeGetItemById($query,$id) {
        return $query->where('id', $id)->first();
    }

    public function scopeGetItemDetails($query,$id) {
        return $query->where('item_masters_fas.id', $id)
        ->leftJoin('brands_assets','item_masters_fas.brand_id','=','brands_assets.id')
        // ->join('suppliers','item_masters_fas.suppliers_id','=','suppliers.id')
        // ->join('subcategories','item_masters_fas.subcategories_id','=','subcategories.id')
        ->select('item_masters_fas.*',
            'brands_assets.brand_description',
            // 'suppliers.last_name',
            // 'subcategories.subcategory_description'
        )->get();
    }

    public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
           $model->created_by = CRUDBooster::myId();
       });
       static::updating(function($model)
       {
           $model->updated_by = CRUDBooster::myId();
       });
   }
}
