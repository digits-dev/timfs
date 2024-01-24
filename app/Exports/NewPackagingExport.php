<?php

namespace App\Exports;

use App\NewPackaging;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NewPackagingExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function headings():array {
        $heading = [
            'TASTELESS CODE',
            'NWP CODE',
            'ITEM DESCRIPTION',
            'PACKAGING SIZE',
            'UOM',
            'TTP',
            'TARGET DATE',
            'SOURCING CATEGORY',
            'STICKER MATERIAL',
            'UNIFORM TYPE',
            'MATERIAL TYPE',
            'SOURCING USAGE',
            'PAPER TYPE',
            'DESIGN TYPE',
            'SIZE',
            'BUDGET RANGE',
            'REFERENCE LINK',
            'INITIAL QTY NEEDED',
            'FORECAST QTY NEEDED',
            'CREATED BY',
            'CREATED DATE',
            'UPDATED BY',
            'UPDATED DATE',
            'APPROVAL STATUS',
            'SOURCING STATUS',
        ];

        return $heading;
    } 

    public function map($row): array {
        $row = $row->toArray();
        return [...array_values($row)];
    }

    public function query() {
        $rows = (new NewPackaging())->getExportDetails();
        return $rows;
    }
}