<?php

namespace App\Exports;

use Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use App\HistoryPurchasePrice;

class PurchasePrice implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function array() : array
    {
        $data_ppHistory = DB::table('history_item_masterfile');
        $data_ppHistory->select(
            'history_item_masterfile.tasteless_code', 
            'item_masters.full_item_description', 
            'groups.group_description', 
            'history_item_masterfile.purchase_price', 
            'history_item_masterfile.old_purchase_price',
            'cms_users.name as updatedby',
            'history_item_masterfile.updated_at as updateddate'
        )
        ->join('item_masters','history_item_masterfile.item_id','=','item_masters.id')
        ->leftJoin('groups','history_item_masterfile.group_id','=','groups.id')
        ->leftJoin('cms_users','history_item_masterfile.updated_by','=','cms_users.id')
        ->whereNotNull('history_item_masterfile.purchase_price')
        ->orderBy('history_item_masterfile.id', 'DESC');

        if (Request::get('filter_column')) {

            $filter_column = Request::get('filter_column');
            $data_ppHistory->where(function($w) use ($filter_column,$fc) {
                foreach($filter_column as $key=>$fc) {

                    $value = @$fc['value'];
                    $type  = @$fc['type'];

                    if($type == 'empty') {
                        $w->whereNull($key)->orWhere($key,'');
                        continue;
                    }

                    if($value=='' || $type=='') continue;

                    if($type == 'between') continue;

                    switch($type) {
                        default:
                            if($key && $type && $value) $w->where($key,$type,$value);
                        break;
                        case 'like':
                        case 'not like':
                            $value = '%'.$value.'%';
                            if($key && $type && $value) $w->where($key,$type,$value);
                        break;
                        case 'in':
                        case 'not in':
                            if($value) {
                                $value = explode(',',$value);
                                if($key && $value) $w->whereIn($key,$value);
                            }
                        break;
                    }
                }
            });

            foreach($filter_column as $key=>$fc) {
                $value = @$fc['value'];
                $type  = @$fc['type'];
                $sorting = @$fc['sorting'];

                if($sorting!='') {
                    if($key) {
                        $data_ppHistory->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type=='between') {
                    if($key && $value) $data_ppHistory->whereBetween($key,$value);
                }

                else {
                    continue;
                }
            }
        }

        return $data_ppHistory->get()->toArray();
    }

    public function headings(): array
    {
        return [
            'Tasteless Code', 
            'Item Description', 
            'Group',
            'Purchase Price',
            'Old Purchase Price',
            'Updated By',
            'Updated Date'
        ];
    }
}
