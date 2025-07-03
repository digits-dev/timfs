<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExcelTemplate;
use Maatwebsite\Excel\Facades\Excel;
use CRUDBooster;
use DB;
use App\ItemMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class ItemUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Update Items';
        $data['uploadRoute'] = route('update.imfs');
        $data['uploadTemplate'] = route('downloadItemTemplate');
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
        dd($request->all());
    }

    public function downloadItemTemplate() 
    {
        $header = array("TASTELESS CODE","FULFILLMENT TYPE");
        
        $export = new ExcelTemplate([$header]);
        return Excel::download($export, 'item-fulfillment-type-'.date("Ymd").'-'.date("h.i.sa").'.csv');
    }
    
    public function imfsUpdate(Request $request) 
    {
        set_time_limit(-1);
        $file = $request->file('import_file');
            
        $validator = \Validator::make(
            [
                'file' => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:csv',
            ]
        );
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
            CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_format_failed"), 'danger');
        }
        
        
        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            
            $csv = array_map('str_getcsv', file($path));
            
            $dataExcel = Excel::load($path, function($reader) {
            })->get();
            
            //get all tasteless_code
            $in_db = array();
            $tasteless_code = DB::table('item_masters')->select('tasteless_code')->where('tasteless_code', '!=', 0)->get()->toArray();
            // dd(count($tasteless_code));
            for($i = 0; $i < count($tasteless_code); $i++)
            {
                    array_push($in_db,$tasteless_code[$i]->tasteless_code);
            }
        
            // $unMatch = [];
            // $header = array(
            //     "Active Status",
            //     "Type",
            //     "Item",
            //     "Description",
            //     "Sales Tax Code",
            //     "Account",
            //     "COGS Account",
            //     "Asset Account",
            //     "Accumulated Depreciation",
            //     "Purchase Description",
            //     "Quantity On Hand",
            //     "U/M",
            //     "U/M Set",
            //     "Cost",
            //     "Preferred Vendor",
            //     "Tax Agency",
            //     "Price",
            //     "Reorder Pt (Min)",
            //     "MPN",
            //     "GROUP",
            //     "BARCODE",
            //     "DIMENSION",
            //     "PACKAGING SIZE",
            //     "PACKAGING UOM",
            //     "TAX STATUS",
            //     "SUPPLIERS ITEM CODE");
            
            // for ($i=0; $i < sizeof($csv[0]); $i++) {
            // 	if (!in_array($csv[0][$i], $header)) {
            // 		$unMatch[] = $csv[0][$i];
            // 	}
            // }

            // if(!empty($unMatch)) {
                
            // 	return response()->json(['errors' => trans("crudbooster.alert_upload_price_format_failed")]);
            // 	CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_format_failed"), 'danger');
            // }
            
            if(!empty($dataExcel) && $dataExcel->count() <= 2000) 
            {	
                $cnt_fail = 0;
                DB::connection()->disableQueryLog();
            
                $new_item = [];

                foreach ($dataExcel as $key => $value) 
                {	
                    $check_upload = false;
                    // if($value->item ==''){
                    if(count($value) <= 0){
                        $cnt_fail++; 
                    }else{

                        // fulfillment type
                        $fulfillment_types_id = DB::table('fulfillment_types')->where('fulfillment_type',$value->fulfillment_type)->value('id');

                        $remove_comma = str_replace(",", "",$value->ttp);//TTP
                        $ttp = floatval($remove_comma);
                        
                        $remove_comma2 = str_replace(",", "",$value->price);//price
                        $sales_price = floatval($remove_comma2);
        
                        $tax_code_id = 0;
                        if($value->sales_tax_code == "TAX")
                        {
                            $tax_code_id = 1;
                        }else{
                            $tax_code_id = 2;
                        }
                        $account = strtoupper($value->account);
                        $cogs_account = strtoupper($value->cogs_account);
                        $asset_account = strtoupper($value->asset_account);
                        $uom = strtoupper($value->um);
                        $uom_set = strtoupper($value->um_set);
                    
                        $account_id = DB::table('accounts')->where('group_description',$account)->select('id')->first();
                        $cogs_account_id = DB::table('cogs_accounts')->where('group_description',$cogs_account)->select('id')->first();
                        $asset_account_id = DB::table('asset_accounts')->where('group_description',$asset_account)->select('id')->first();
                        $uom_id = DB::table('uoms')->where('uom_description',$uom)->select('id')->first();
                        $uom_set_id = DB::table('uoms_set')->where('uom_description',$uom_set)->select('id')->first();
                        $preferred_vendor_id = DB::table('suppliers')->where('last_name', 'LIKE', '%' . $value->preferred_vendor . '%')->select('id')->first();
                        $group_id = DB::table('groups')->where('group_description',$value->group)->select('id')->first();
                        // $packagings_id = DB::table('packagings')->where('packaging_code',$value->packaging_uom)->select('id')->first();

                        if(!in_array($value->tasteless_code,$in_db))// if new tasteless_code
                        {
                            array_push($new_item, $value);
                        }
                        
                        $data = [
                            'fulfillment_types_id' =>  intval($fulfillment_types_id)
                                
                            ];
                            
                        DB::beginTransaction();			
                        try {
                            
                            DB::table('item_masters')->where('tasteless_code', $value->tasteless_code)->update($data);
                            DB::connection('mysql_trs')->table('items')->where('tasteless_code', '=', (string)$value->tasteless_code)
                            ->update([ 
                                'fulfillment_types_id' => intval($fulfillment_types_id) 
                            ]);

                            DB::commit();
                        } catch (\Exception $e) {
                            return response()->json(['errors' => $e]);
                            DB::rollback();
                        }
                    }
                }
                
                if($cnt_fail == 0)
                {    
                    if(!empty($new_item))
                    {
                        $str = '';
                                
                        foreach($new_item as $key=>$ni){
                            if(count($new_item) == $key+1){
                                $str .= $ni->tasteless_code.' ';
                            }else{
                                $str .= $ni->tasteless_code.', ';
                            }
                        }
                        

                        CRUDBooster::redirect(CRUDBooster::mainpath(), 'Upload success!. New items found: '. $str . ' please manual add these items.', 'success');
                        
                        
                    }else{
                        CRUDBooster::redirect(CRUDBooster::mainpath(), 'Update items success!', 'success');
                        
                    }
                    
                }
                else{
                    
                    CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_failed"), 'danger');
                }

                
            }else{
                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_more_than_2k_lines"), 'danger');
                return response()->json(['errors' => trans("crudbooster.alert_upload_inventory_beyond_total")]);
                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_price_failed"), 'danger');
            }
            unset($in_db);
            unset($new_item);
        }
    }
    
}
