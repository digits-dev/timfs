<?php

namespace App\Http\Controllers;

use App\Exports\ExcelTemplate;
use App\Imports\FulfillmentTypeImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use CRUDBooster;
use DB;
use App\ItemMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ItemFulfillmentTypeUploadController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Upload Fulfillment Types';
        $data['uploadRoute'] = route('uploadFulfillmentType');
        $data['uploadTemplate'] = route('downloadFulfillmentTypeTemplate');
        return view("upload.uploader", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        set_time_limit(0);
				
        $errors = array();
        $path_excel = $request->file('import_file')->store('temp');
        $path = storage_path('app').'/'.$path_excel;
        HeadingRowFormatter::default('none');
        $headings = (new HeadingRowImport)->toArray($path);
        HeadingRowFormatter::default('slug');
        $excelData = Excel::toArray(new FulfillmentTypeImport, $path);
        $allowed_values = DB::table('fulfillment_methods')
            ->where('status', 'ACTIVE')
            ->pluck('fulfillment_method')
            ->toArray();
        
        $header = array("TASTELESS CODE","FULFILLMENT TYPE");
        
        for ($i=0; $i < sizeof($headings[0][0]); $i++) {
            if (!in_array($headings[0][0][$i], $header)) {
                $unMatch[] = $headings[0][0][$i];
            }
        }
        
        if(!empty($unMatch)) {
            return redirect()->back()->with(['message_type' => 'danger', 'message' => 'Failed ! Please check template headers, mismatched detected.']);
        }
        
        $items = array_unique(array_column($excelData[0], "tasteless_code"));
        $uploaded_items = array_column($excelData[0], "tasteless_code");
        
        if(count((array)$uploaded_items) != count((array)$items)){
            array_push($errors, 'duplicate item found!');
        }
        
        foreach ($items as $key => $value) {
            
            $itemExist = ItemMaster::where('tasteless_code',(string)$value)->first();
            
            if(is_null($itemExist)){
                array_push($errors, 'no item found!');
            }
        }
        
        foreach ($excelData[0] as $key => $value){
            //check if sale price is null
            if(is_null($value['fulfillment_type'])){
                array_push($errors, 'Item code '.$value['tasteless_code'].' has blank fulfillment type.');
            }

            if (!in_array($value['fulfillment_type'], $allowed_values)) {
                array_push($errors, 'Item code '.$value['tasteless_code'].' has invalid fulfillment type.');
            }
        }
        
        if(!empty($errors)){
            return redirect('admin/item_masters')->with(['message_type' => 'danger', 'message' => 'Failed ! Please check '.implode(", ",$errors)]);
        }
        
        foreach ($excelData[0] as $key => $value)
        {
            $currentItemCode = ItemMaster::where('tasteless_code', (string)$value['tasteless_code'])->first();
            
            
            // History logs for item master
            $currentItemCodeArray = []; 
            $CheckTableColumn = Schema::getColumnListing('item_masters');
            foreach($CheckTableColumn as $keyname){   
                if(!empty($keyname)){
                    
                    if($keyname == "purchase_price"){
                        array_push($currentItemCodeArray, ['name' => ucwords($header[1]), 'old' => $currentItemCode->$keyname, 'new' => $value['cost_price']]);
                    }
                }
            }

        }

        Excel::import(new FulfillmentTypeImport, $path);
        return CRUDBooster::redirect(CRUDBooster::adminPath('item_masters'), 'Upload Complete!', 'success')->send();


    }

    public function downloadFulfillmentTypeTemplate() {
        $header = array("TASTELESS CODE","FULFILLMENT TYPE");
        
        $export = new ExcelTemplate([$header]);
        return Excel::download($export, 'item-fulfillment-type-format-'.date("Ymd").'-'.date("h.i.sa").'.csv');
    }
}
