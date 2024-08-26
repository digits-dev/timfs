<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandsAssets extends Model
{
    use HasFactory;

    protected $table = 'brands_assets';

    protected $guarded = [];

    protected $fillable = [
        'brand_code', 
        'brand_description', 
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
