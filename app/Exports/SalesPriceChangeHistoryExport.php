<?php

namespace App\Exports;

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
        return DB::table('sales_price_change_histories')
            ->leftJoin('cms_users as creator', 'creator.id', '=','sales_price_change_histories.created_by')
            ->leftJoin('cms_users as approver', 'approver.id', '=','sales_price_change_histories.approved_by')
            ->leftJoin('item_masters', 'item_masters.tasteless_code', 'sales_price_change_histories.tasteless_code')
            ->select(
                'sales_price_change_histories.status',
                'sales_price_change_histories.tasteless_code',
                'item_masters.full_item_description',
                'sales_price_change_histories.sales_price',
                'sales_price_change_histories.sales_price_change',
                'sales_price_change_histories.effective_date',
                'creator.name as creator_name',
                'sales_price_change_histories.created_at',
                'approver.name as approver_name',
                'sales_price_change_histories.approved_at',
            )->get();
    }
}
