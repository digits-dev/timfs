<?php

namespace App\Imports;

use App\Models\ItemMastersFa;
use App\Models\ItemMastersFasApprovals;
use Illuminate\Support\Facades\Hash;
//use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use CRUDBooster;
class UpdateAssetsMasterfile implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
            $brand = DB::table('brands_assets')->where(DB::raw('LOWER(TRIM(brand_description))'),strtolower(trim($row['brand_name'])))->first();
        			
            ItemMastersFa::where(['tasteless_code'=>$row['tasteless_code']])
            ->update([
                'brand_id'            => $brand->id,
            ]); 

            ItemMastersFasApprovals::where(['tasteless_code'=>$row['tasteless_code']])
            ->update([
                'brand_id'            => $brand->id,
            ]);
        }
    }
}