<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPriceChangeHistory extends Model
{
    use HasFactory;
    protected $table = 'sales_price_change_histories';
}
