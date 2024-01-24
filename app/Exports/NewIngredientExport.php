<?php

namespace App\Exports;

use App\NewIngredient;
use App\Segmentation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NewIngredientExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
    public $segmentations;

    public function __construct() {
        $this->segmentations = Segmentation::all();
    }

    public function headings():array {
        $heading = [
            'TASTELESS CODE',
            'NWP CODE',
            'ITEM DESCRIPTION',
            'PACKAGING SIZE',
            'UOM',
            'TTP',
            'TARGET DATE',
            'SEGMENTATIONS',
            'REASON',
            'RECOMMENDED BRAND 1',
            'RECOMMENDED BRAND 2',
            'RECOMMENDED BRAND 3',
            'INITIAL QTY NEEDED',
            'FORECAST QTY NEEDED',
            'BUDGET RANGE',
            'REFERENCE LINK',
            'TERM',
            'DURATION',
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
        $segmentations = $this->segmentations
            ->whereIn('segment_column_name', explode(',', $row['segmentations']))
            ->pluck('segment_column_description')
            ->toArray();
        $row['segmentations'] = implode(',', $segmentations);
        return [...array_values($row)];
    }

    public function query() {
        $rows = (new NewIngredient())->getExportDetails();
        return $rows;
    }
}
