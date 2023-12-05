<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemApprovalStatus extends Model
{
    use HasFactory;
    protected $table = 'item_approval_statuses';
}
