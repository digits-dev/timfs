<?php

namespace App\Imports;
use DB;
use CRUDBooster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Storage;
use App\Models\ItemMastersFa;
use App\Models\ItemMastersFasApprovals;
use App\CodeCounter;
class UploadAssetsMasterfile implements ToCollection, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    public function __construct() {
     
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows){
        foreach ($rows->toArray() as $row) {
            $brand = DB::table('brands_assets')->where(DB::raw('LOWER(TRIM(brand_description))'),strtolower(trim($row['brand_name'])))->first();
            $coa   = DB::table('fa_coa_categories')->where(DB::raw('LOWER(TRIM(description))'),strtolower(trim($row['coa'])))->first();
            $sub_category   = DB::table('fa_sub_categories')->where(DB::raw('LOWER(TRIM(description))'),strtolower(trim($row['sub_category'])))->first();
            $currency   = DB::table('currencies')->where(DB::raw('LOWER(TRIM(currency_code))'),strtolower(trim($row['currency'])))->first();
            
            $tasteless_code = CodeCounter::where('id', 4)->where('type', 'ASSET MASTERFILE')->value('code_1');
					
            ItemMastersFa::create([
                'action_type'         => 'CREATE',
                'tasteless_code'      => $tasteless_code,
                'item_description'    => $brand->brand_code ." ". $row['item_description'],
                'upc_code'            => $row['upc_code'],
                'supplier_item_code'  => $row['supplier_item_code'],
                'brand_id'            => $brand->id,
                'categories_id'       => $coa->id,
                'subcategories_id'    => $sub_category->id,
                'vendor1_id'          => $row['vendor1_name'],
                'vendor2_id'          => $row['vendor2_name'],
                'vendor3_id'          => $row['vendor3_name'],
                'vendor4_id'          => $row['vendor4_name'],
                'vendor5_id'          => $row['vendor5_name'],
                'cost'                => $row['cost'],
                'currency_id'         => $currency->id,
                'model'               => $row['model'],
                'size'                => $row['measurement'],
                'color'               => $row['color'],
                //'approval_status'     => 202,
                'approval_status'     => 200,
                'sku_statuses_id'     => 1,
                'created_by'          => CRUDBooster::myId(),
                'created_at'          => date('Y-m-d H:i:s')
            ]); 

            ItemMastersFasApprovals::create([
                'action_type'         => 'CREATE',
                'tasteless_code'      => $tasteless_code,
                'item_description'    => $brand->brand_code ." ". $row['item_description'],
                'upc_code'            => $row['upc_code'],
                'supplier_item_code'  => $row['supplier_item_code'],
                'brand_id'            => $brand->id,
                'categories_id'       => $coa->id,
                'subcategories_id'    => $sub_category->id,
                'vendor1_id'          => $row['vendor1_name'],
                'vendor2_id'          => $row['vendor2_name'],
                'vendor3_id'          => $row['vendor3_name'],
                'vendor4_id'          => $row['vendor4_name'],
                'vendor5_id'          => $row['vendor5_name'],
                'cost'                => $row['cost'],
                'currency_id'         => $currency->id,
                'model'               => $row['model'],
                'size'                => $row['measurement'],
                'color'               => $row['color'],
                //'approval_status'     => 202,
                'approval_status'     => 200,
                'sku_statuses_id'     => 1,
                'created_by'          => CRUDBooster::myId(),
                'created_at'          => date('Y-m-d H:i:s')
            ]); 

            CodeCounter::where('type', 'ASSET MASTERFILE')->where('id', 4)->increment('code_1');
        }
    }

    public function prepareForValidation($data, $index){
        //COA CODE
        $data['coa_exist']['check'] = false;
        $checkRowDb = DB::table('fa_coa_categories')->select(DB::raw("LOWER(TRIM(description)) AS description"))->get()->toArray();
        $checkRowDbColumn = array_column($checkRowDb, 'description');
    
        if(!empty($data['coa'])){
            if(in_array(strtolower(trim($data['coa'])), $checkRowDbColumn)){
                $data['coa_exist']['check'] = true;
            }
        }else{
            $data['coa_exist']['check'] = true;
        }
       
        //SUB CATEGORY
        $data['subCat_exist']['check'] = false;
        $checkRowDb = DB::table('fa_sub_categories')->select(DB::raw("LOWER(TRIM(description)) AS description"))->get()->toArray();
        $checkRowDbColumn = array_column($checkRowDb, 'description');
    
        if(!empty($data['sub_category'])){
            if(in_array(strtolower(trim($data['sub_category'])), $checkRowDbColumn)){
                $data['subCat_exist']['check'] = true;
            }
        }else{
            $data['subCat_exist']['check'] = true;
        }

        //BRANDS
        $data['brand_exist']['check'] = false;
        $checkRowDb = DB::table('brands_assets')->select(DB::raw("LOWER(TRIM(brand_description)) AS brand_description"))->get()->toArray();
        $checkRowDbColumn = array_column($checkRowDb, 'brand_description');
    
        if(!empty($data['brand_name'])){
            if(in_array(strtolower(trim($data['brand_name'])), $checkRowDbColumn)){
                $data['brand_exist']['check'] = true;
            }
        }else{
            $data['brand_exist']['check'] = true;
        }

         //CURRENCY
         $data['currency_exist']['check'] = false;
         $checkRowDb = DB::table('currencies')->select(DB::raw("LOWER(TRIM(currency_code)) AS code"))->get()->toArray();
         $checkRowDbColumn = array_column($checkRowDb, 'code');
     
         if(!empty($data['currency'])){
             if(in_array(strtolower(trim($data['currency'])), $checkRowDbColumn)){
                 $data['currency_exist']['check'] = true;
             }
         }else{
             $data['currency_exist']['check'] = true;
         }

        return $data;
    }

    public function rules(): array{
        return [
            '*.coa_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('COA not exist in COA Submaster!');
                }
            },
            '*.subCat_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('Sub Category not exist in Sub Category Submaster!');
                }
            },
            '*.brand_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('Brand not exist in Brand Submaster!');
                }
            },
            '*.currency_exist' => function($attribute, $value, $onFailure) {
                if ($value['check'] === false) {
                    $onFailure('Currency not exist in Brand Submaster!');
                }
            },
        ];
    }

    public function customValidationMessages(){
        return [
            '*.upc_code.required'           => 'UPC Code Required!',
            '*.item_description.required'   => 'Item Description Required!',
            '*.coa.required'                => 'COA Required!',
            '*.sub_category.required'       => 'Sub Category Required!',
            '*.cost.required'               => 'Cost Required!',
            '*.currency.required'           => 'Currency Required!',
            '*.brand_name.required'         => 'Brand Name Required!',
            '*.vendor1_id.required'         => 'Vendor 1 Name Required!',
            '*.measurement.required'        => 'Measurement Required!',
            '*.color.required'              => 'Color Required!',
        ];
    }
}
