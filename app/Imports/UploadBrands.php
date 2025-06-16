<?php

namespace App\Imports;

use App\Models\BrandsAssets;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use DB;
use CRUDBooster;
class Uploadbrands implements ToCollection, SkipsEmptyRows, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
         
            BrandsAssets::updateOrcreate([
                'brand_description'    => $row['brand_description'] 
            ],
            [
                'brand_code'           => $row['brand_code'],
                'brand_description'    => $row['brand_description'],
                'item_cost'            => $row['item_cost'],
                'status'               => $row['status'],
                'created_at'           => date('Y-m-d h:i:s'),
                'created_by'           => CRUDBooster::myId()
            ]);
        }
    }
}