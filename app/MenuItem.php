<?php

namespace App;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'action_type',
        'menu_item_description',
        'menu_categories_id',
        'menu_product_types_id',
        'menu_transaction_types_id',
        'menu_types_id',
        'menu_selling_price',
        'original_concept',
        'available_concepts',
        'status',
        'approval_status',
        'created_by'
    ];

    public function scopeGetItems($query){
        return $query->leftjoin('menu_categories','menu_items.menu_categories_id','menu_categories.id')
        ->leftjoin('menu_subcategories','menu_items.menu_subcategories_id','menu_subcategories.id')
        ->leftjoin('menu_types','menu_items.menu_types_id','menu_types.id')
        ->select(
            'menu_items.tasteless_menu_code as itemcode',
            'menu_items.tasteless_menu_code as barcode',
            'menu_items.menu_item_description as item_description',
            'menu_items.menu_item_description as item_pos_receipt_description',
            'menu_categories.category_description as department',
            'menu_subcategories.subcategory_description as category',
            DB::raw("(select '') as subcategory"),
             DB::raw("(select '') as brand"),
            DB::raw("(select '') as color"),
            DB::raw("(select '') as size"),
            DB::raw("(select '') as supplier"),
            'menu_items.status as item_status',
            'menu_types.menu_type_description as item_type',
            DB::raw("(select '') as cost_price"),
            'menu_items.menu_price_dine as selling_price_dine',
            'menu_items.menu_price_take as selling_price_takeout',
            'menu_items.menu_price_dlv as selling_price_deliver',
            DB::raw("(select '') as unit"),
            DB::raw("(select '') as calories"),
            DB::raw("(select '') as nutrifacts")
        );
    }

    public function scopeGetUpdatedItems($query){
        return $query->select(
            'menu_items.tasteless_menu_code as barcode',
            'menu_items.menu_item_description as item_description',
            'menu_items.menu_price_dine as selling_price_dine',
            'menu_items.menu_price_take as selling_price_takeout',
            'menu_items.menu_price_dlv as selling_price_deliver'
        );
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
