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

    private static function getSegmentationChecks()
    {
        $segmentationColumns = DB::table('information_schema.columns')
            ->select('COLUMN_NAME')
            ->where('TABLE_SCHEMA', env('DB_DATABASE')) // Your database name
            ->where('TABLE_NAME', 'menu_items') // Your table name
            ->where('COLUMN_NAME', 'LIKE', 'segmentation%')
            ->pluck('COLUMN_NAME')
            ->toArray();

        return collect($segmentationColumns)
            ->map(function ($column) {
                return "IF(`menu_items`.`{$column}` = 1, '{$column}', NULL)";
            })
            ->implode(', ');
    }


    public function scopeGetItems($query){

        $segmentationChecks = self::getSegmentationChecks();

        return $query->leftjoin('menu_categories','menu_items.menu_categories_id','menu_categories.id')
        ->leftjoin('menu_subcategories','menu_items.menu_subcategories_id','menu_subcategories.id')
        ->leftjoin('menu_types','menu_items.menu_types_id','menu_types.id')
        ->select(
            'menu_items.tasteless_menu_code as itemcode',
            'menu_items.tasteless_menu_code as barcode',
            'menu_items.menu_item_description as item_description',
            'menu_items.menu_item_description as item_pos_receipt_description',
            DB::raw("(SELECT GROUP_CONCAT(menu_segmentations.menu_segment_column_description SEPARATOR ', ')
            FROM menu_segmentations
            WHERE FIND_IN_SET(menu_segmentations.menu_segment_column_name, CONCAT_WS(',', {$segmentationChecks}))
            ) AS `group`"),
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
            DB::raw("(select '') as nutrifacts"),
            DB::raw("
            CASE
                WHEN menu_items.status = 'ACTIVE' THEN 'Y'
                WHEN menu_items.status = 'INACTIVE' THEN 'F'
                ELSE 'F'
            END as with_button")
        );
    }

    public function scopeGetUpdatedItems($query){

        $segmentationChecks = self::getSegmentationChecks();

        return $query->leftjoin('menu_categories','menu_items.menu_categories_id','menu_categories.id')
            ->leftjoin('menu_subcategories','menu_items.menu_subcategories_id','menu_subcategories.id')
            ->select(
                'menu_items.tasteless_menu_code as barcode',
                'menu_items.menu_item_description as item_description',
                DB::raw("(SELECT GROUP_CONCAT(menu_segmentations.menu_segment_column_description SEPARATOR ', ')
                FROM menu_segmentations
                WHERE FIND_IN_SET(menu_segmentations.menu_segment_column_name, CONCAT_WS(',', {$segmentationChecks}))
               ) AS `group`"
                ),
                'menu_categories.category_description as department',
                'menu_subcategories.subcategory_description as category',
                'menu_items.menu_price_dine as selling_price_dine',
                'menu_items.menu_price_take as selling_price_takeout',
                'menu_items.menu_price_dlv as selling_price_deliver',
                'menu_items.status as item_status',
                DB::raw("
                CASE
                    WHEN menu_items.status = 'ACTIVE' THEN 'Y'
                    WHEN menu_items.status = 'INACTIVE' THEN 'F'
                    ELSE 'F'
                END as with_button")
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