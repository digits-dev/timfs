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
<<<<<<< HEAD
            // dd($rows);
            $brand = DB::table('brands_assets')->where(DB::raw('LOWER(TRIM(brand_description))'),strtolower(trim($row['brand_name'])))->first();
        			
=======
            //$brand = DB::table('brands_assets')->where(DB::raw('LOWER(TRIM(brand_description))'),strtolower(trim($row['brand_name'])))->first();
            $coa   = DB::table('fa_coa_categories')->where(DB::raw('LOWER(TRIM(description))'),strtolower(trim($row['coa'])))->first();
            $sub_category   = DB::table('fa_sub_categories')->where(DB::raw('LOWER(TRIM(description))'),strtolower(trim($row['sub_category'])))->first();
>>>>>>> master
            ItemMastersFa::where(['tasteless_code'=>$row['tasteless_code']])
            ->update([
                // 'item_description'    => $row['item_description'],
                'categories_id'       => $coa->id,
                'subcategories_id'    => $sub_category->id,
                'approved_at'         => date('Y-m-d H:i:s')
            ]); 

            ItemMastersFasApprovals::where(['tasteless_code'=>$row['tasteless_code']])
            ->update([
                // 'item_description'    => $row['item_description'],
                'categories_id'       => $coa->id,
                'subcategories_id'    => $sub_category->id,
                'approved_at'         => date('Y-m-d H:i:s')
            ]);
        }
    }
}