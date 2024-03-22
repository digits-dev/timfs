<?php

namespace App\Exports;

use DateTime;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesPriceChangeHistoryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array {
        return [
            'STATUS',
            'TASTELESS CODE',
            'ITEM DESCRIPTION',
            'SALES PRICE',
            'SALES PRICE CHANGE',
            'EFFECTIVE DATE',
            'CREATED BY',
            'CREATED DATE',
            'APPROVED BY',
            'APPROVED DATE',
        ];
    }

    public function collection()
    {
        $query = DB::table('sales_price_change_histories')
            ->leftJoin('cms_users as cms_users', 'cms_users.id', '=','sales_price_change_histories.created_by')
            ->leftJoin('cms_users as cms_users1', 'cms_users1.id', '=','sales_price_change_histories.approved_by')
            ->leftJoin('item_masters', 'item_masters.tasteless_code', 'sales_price_change_histories.tasteless_code')
            ->select(
                'sales_price_change_histories.status',
                'sales_price_change_histories.tasteless_code',
                'item_masters.full_item_description',
                'sales_price_change_histories.sales_price',
                'sales_price_change_histories.sales_price_change',
                'sales_price_change_histories.effective_date',
                'cms_users.name as creator_name',
                'sales_price_change_histories.created_at',
                'cms_users1.name as approver_name',
                'sales_price_change_histories.approved_at',
            );

        if (request()->has('filter_column')) {
            $filter_column = request()->filter_column;

            $query->where(function($w) use ($filter_column) {
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
                        $query->orderby($key,$sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    if ($key && $value) {
                        foreach ($value as $index => $date) {
                            $converted_date = self::convertToYMDFormat($date);
                            if ($converted_date) {
                                $value[$index] = $converted_date;
                            }
                        }
                        $query->whereBetween($key, $value);
                    }
                } else {
                    continue;
                }
            }
        }
        return $query->get();
    }

    function convertToYMDFormat($date) {
        $dateTime = DateTime::createFromFormat('m/d/Y', $date);
        
        if ($dateTime) {
            return $dateTime->format('Y-m-d');
        } else {
            return false; 
        }
    }
}
