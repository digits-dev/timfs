<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CRUDBooster;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuType extends Model
{
    use SoftDeletes;
    protected $table = 'menu_types';

    protected $fillable = [
        'menu_type_description',
        'status',
        'created_by'
    ];

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
       static::deleting(function($model)
       {
           $model->status = 'INACTIVE';
       });
   }
}
