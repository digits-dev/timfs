<?php

namespace App\Imports;

use App\Models\FaSubCategories;
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
class UploadSubCategory implements ToCollection, SkipsEmptyRows, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Users|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row){
            $coa   = DB::table('fa_coa_categories')->where(DB::raw('LOWER(TRIM(description))'),strtolower(trim($row['coa'])))->first();
            if(!$coa){
                return CRUDBooster::redirect(CRUDBooster::adminpath('fa_coa_sub_categories'),"Coa not exist: ".($key+2),"danger");
            }
            FaSubCategories::updateOrcreate([
                'description'          => trim($row['description']) 
            ],
            [
                'coa_id'               => $coa->id,
                'description'          => trim($row['description']),
                'updated_at'           => date('Y-m-d h:i:s'),
                'updated_by'           => CRUDBooster::myId()
            ]);
        }
    }
}