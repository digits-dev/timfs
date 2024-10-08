<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CRUDBooster;
use Illuminate\Support\Facades\DB;

class ItemMaster extends Model
{
    protected $table = 'item_masters';

    protected $guarded = [];

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

    public function scopeGetItems($query){
        return $query->leftjoin('brands','item_masters.brands_id','brands.id')
        ->leftjoin('categories','item_masters.categories_id','categories.id')
        ->leftjoin('groups','item_masters.groups_id','groups.id')
        ->select(
            'item_masters.tasteless_code as itemcode',
            'item_masters.tasteless_code as barcode',
            'item_masters.item_description',
            'item_masters.item_description as item_pos_receipt_description',
            'groups.group_description as department',
            'categories.category_description as category',
            DB::raw("(select '') as subcategory"),
            DB::raw("(select '') as brand"),
            DB::raw("(select '') as color"),
            DB::raw("(select '') as size"),
            DB::raw("(select '') as supplier"),
            DB::raw("(select '') as item_status"),
            DB::raw("(select '') as item_type"),
            DB::raw("(select '') as cost_price"),
            'item_masters.ttp as selling_price',
            DB::raw("(select '') as unit"),
            DB::raw("(select '') as calories"),
            DB::raw("(select '') as nutrifacts")
        );
    }

    public function scopeGetUpdatedItems($query){
        return $query->select(
            'item_masters.tasteless_code as barcode',
            'item_masters.item_description',
            'item_masters.ttp as selling_price'
        );
    }
}
