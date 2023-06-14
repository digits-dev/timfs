<?php

namespace App\Exports;

use Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use App\HistoryPurchasePrice;


class SalesPrice implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array() : array
    {
        $data_ttpHistory = DB::table('history_item_masterfile');
        $data_ttpHistory->select(
            'history_item_masterfile.tasteless_code', 
            'item_masters.full_item_description', 
            'groups.group_description', 
            'history_item_masterfile.ttp', 
            'history_item_masterfile.ttp_percentage',
            'history_item_masterfile.old_ttp', 
            'history_item_masterfile.old_ttp_percentage',
            'cms_users.name as updatedby',
            'history_item_masterfile.updated_at as updateddate')
        ->join('item_masters','history_item_masterfile.item_id','=','item_masters.id')
        ->leftJoin('groups','history_item_masterfile.group_id','=','groups.id')
        ->leftJoin('cms_users','history_item_masterfile.updated_by','=','cms_users.id')
        ->whereNotNull('history_item_masterfile.ttp');

        if (Request::get('filter_column')) {
	
            $filter_column = Request::get('filter_column');
            $data_ttpHistory->where(function($w) use ($filter_column,$fc) {
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
                        $data_ttpHistory->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type=='between') {
                    if($key && $value) $data_ttpHistory->whereBetween($key,$value);
                }

                else {
                    continue;
                }
            }
        }
            
        return $data_ttpHistory->get()->toArray();
    }

    public function headings(): array
    {
        return array(
            'Tasteless Code', 
            'Item Description', 
            'Group',
            'Old TTP',
            'Old TTP Percentage',
            'TTP',
            'TTP Percentage',
            'Updated By',
            'Updated Date'
        );
    }
}
