<?php

namespace App\Models\ProductionItems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItemsComments extends Model
{
    use HasFactory;

    protected $table = 'production_items_comments';

    protected $fillable = [
      'production_items_id',
      'comment_content',
      'comment_id',
      'parent_id',
      'created_by',
      'created_at',
      'updated_at'
  ];

      
}
