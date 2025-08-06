<?php namespace App\Http\Controllers\ProductionItems;

use App\CodeCounter;
use Session;
use Illuminate\Support\Str; 
	use Maatwebsite\Excel\HeadingRowImport;
	use Maatwebsite\Excel\Imports\HeadingRowFormatter; 
	use App\Brand;  
	use App\ApprovalWorkflowSetting; 
	use App\Group;
	use App\SalesPriceChangeHistory;
	use App\Segmentation;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Redirect;
	use Carbon\Carbon;
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Support\Facades\Storage;
	use Intervention\Image\Facades\Image;
	use Spatie\ImageOptimizer\OptimizerChainFactory; 



	use Maatwebsite\Excel\Facades\Excel;

	use Illuminate\Http\Request;
	use App\Exports\ExcelTemplate;
	use App\Exports\BartenderExport;
	use App\Exports\ItemExport;
	use App\Exports\POSExport;
	use App\Exports\QBExport;
	use App\Exports\ProdutionItems;
	use DB;
	use CRUDBooster;
	use App\Models\ProductionItems\ProductionItemCategory;
	use App\Models\ProductionItems\ProductionItemStorageLocation;
	use App\Models\ProductionItems\ProductionLocation;
	use App\ItemMaster;
use App\Models\ProductionItems\ProductionItemLines;
use App\Models\ProductionItems\ProductionItemLinesModelApproval;
use App\Models\ProductionItems\ProductionItems;
use App\Models\ProductionItems\ProductionItemsApproval as ProductionItemsProductionItemsApproval;
use App\Models\ProductionItems\ProductionItemsComments;
use App\Models\ProductionItems\ProductionItemsModelApproval;
use App\NewPackaging;
use ProductionItemsApproval;

	class AdminProductionItemsController extends \crocodicstudio\crudbooster\controllers\CBController {
 
	 
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "production_items";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = []; 
			$this->col[] = ["label"=>"Original Markup %","name"=>"transfer_price_category.transfer_price_category_markup"]; 
			$this->col[] = ["label"=>"Tasteless code","name"=>"production_items.reference_number"];
			$this->col[] = ["label"=>"Description","name"=>"production_items.full_item_description"];
			$this->col[] = ["label"=>"Production Category","name"=>"production_items.production_category","join"=>"production_item_categories,category_description" ];
			$this->col[] = ["label"=>"Production Location","name"=>"production_items.production_location","join"=>"production_locations,production_location_description"];
			$this->col[] = ["label"=>"Labor Cost","name"=>"production_items.labor_cost"];	
			$this->col[] = ["label"=>"Markup %","name"=>"production_items.markup_percentage","callback"=>function($row){ 
				return ($row->markup_percentage * 100) . '%';
			}];
			$this->col[] = ["label"=>"FC Landed cost","name"=>"production_items.landed_cost"];
			$this->col[] = ["label"=>"OPEX","name"=>"production_items.opex"];
			$this->col[] = ["label"=>"PM / Store Supplies", "name" => "production_items.packaging_cost","callback"=>function($row){
				return round($row->packaging_cost , 2);
			}];
			$this->col[] = ["label"=>"TP (Existing)","name"=>"production_items.final_value_existing"];
			$this->col[] = ["label"=>"TP Vat Ex (Revised Price)","name"=>"production_items.final_value_vatex"];
			$this->col[] = ["label"=>"TP Vat Inc (Updated)","name"=>"production_items.final_value_vatinc"];
			$this->col[] = ["label"=>"Created By","name"=>"production_items.created_by","join"=>"cms_users,name" ];
			$this->col[] = ["label"=>"Updated By","name"=>"production_items.updated_by","join"=>"cms_users,name" ];
			$this->col[] = ["label"=>"Created At","name"=>"production_items.created_at"];
			$this->col[] = ["label"=>"Updated At","name"=>"production_items.updated_at"]; 
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			# END FORM DO NOT REMOVE THIS LINE

	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();
			$my_privilege = CRUDBooster::myPrivilegeId();

			$this->addaction[] = [
				'title'=>'Edit',
				'url'=>CRUDBooster::mainpath('edit/[id]'),
				'icon'=>'fa fa-pencil',
				'color' => ' ',
				"showIf"=>"[status_of_approval] != '202'",
			];
		 

			 $this->index_button = array();
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ['label' => 'Export Items', "url" => "javascript:showItemExport()", "icon" => "fa fa-download"]; 
			
			}


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = [
				['condition' => "[transfer_price_category_markup] / 100 > [markup_percentage]", 'color' => 'danger'],
				['condition' => "[transfer_price_category_markup] / 100 < [markup_percentage]", 'color' => 'success'] 
			];


	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;
			$this->script_js = "
				function showItemExport() {
					$('#modal-items-export').modal('show');
				}

			";

            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
			$this->post_index_html = "
				<div class='modal fade' tabindex='-1' role='dialog' id='modal-items-export'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
									<span aria-hidden='true'>×</span></button>
								<h4 class='modal-title'><i class='fa fa-download'></i> Export Items</h4>
							</div>

							<form method='post' target='_blank' action=".CRUDBooster::mainpath("item-export").">
							<input type='hidden' name='_token' value=".csrf_token().">
							".CRUDBooster::getUrlParameters()."
							<div class='modal-body'>
								<div class='form-group'>
									<label>File Name</label>
									<input type='text' name='filename' class='form-control' required value='Export Assets Items - ".date('Y-m-d H:i:s')."'/>
								</div>
							</div>
							<div class='modal-footer' align='right'>
								<button class='btn btn-default' type='button' data-dismiss='modal'>Close</button>
								<button class='btn btn-primary btn-submit' type='submit'>Submit</button>
							</div>
						</form>
						</div>
					</div>
				</div>
			";

	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	         


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */ 	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();


 


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	       
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	     
			

	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }
	 

	 


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	         $query->leftJoin('transfer_price_category', 'transfer_price_category.id', '=', 'production_items.transfer_price_category')
          		->addSelect('transfer_price_category.transfer_price_category_markup'); 
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		
 

 
	public function generateChangesTableFields(array $differences, $reference_number, $created_at): string {
				//return null string if no changes
				if (empty($differences)) {
					return 'null';
				}

				$html = '<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;"> 
						<thead> 
						<tr style="background-color: #f5f5f5; border-bottom: 2px solid #ddd;"> 
						<th style="text-align: left; padding: 8px 12px; border-right: 1px solid #ddd;">Key</th>
						<th style="text-align: left; padding: 8px 12px; border-right: 1px solid #ddd;">Old Fields Value</th>
						<th style="text-align: left; padding: 8px 12px;">New Fields Value</th>
						</tr>
						</thead><tbody>';

				foreach ($differences as $key => $change) {
					$old = htmlspecialchars((string)$change['old']);
					$new = htmlspecialchars((string)$change['new']);
					

					DB::table('production_items_history')->insert([
					'reference' => $reference_number,
					'item_code' =>  '',
					'action' => 'Update Production Item lines', 
					'description' => 'User Production Item Creation',
					'key_old_value' => $key, // .': '. $safe_value,
					'description_old_value' => $old,	
					'key_new_value' => $key,
					'description_new_value' => $new,  
					'updated_by' => CRUDBooster::myId() ?: 1, 
					'details'  =>  'new update for production with reference number ' . $reference_number,
					'created_at' => $created_at,  
					'updated_at' => now(),
					]);

					$html .= '<tr style="border-bottom: 1px solid #eee;">';
					$html .= "<td style='padding: 8px 12px; border-right: 1px solid #ddd; color: #333;'>{$key}</td>";
					$html .= "<td style='padding: 8px 12px; border-right: 1px solid #ddd; color: #555;'>{$old}</td>";
					$html .= "<td style='padding: 8px 12px; color: #555;'>{$new}</td>";
					$html .= '</tr>';
				}

				$html .= '</tbody></table>';

				return $html;
	}

	

	public function getItemLastDetails($id) {
		$item = DB::table('production_items') 
			->select( 
				'*'
			)
			->where('production_items.reference_number', $id)
			->limit(1) 
			->first();


		return $item;
	}
		
		 

	public function addProductionItemsToDB(Request $request){
 
		$message = '';
		$time_stamp_now = date('Y-m-d H:i:s');  
		$data = $request->all(); 

		if ($request['id']) {
			// Fetch old data
			 
			
			$item = ProductionItems::select('reference_number', 'created_at')->find($request->id);

			$message = "✔️ Item reference number " . $item->reference_number . " updated successfully.";

			// Update data meta
			$data['updated_at'] = $time_stamp_now;
			$data['action_type'] = "UPDATE";
			$data['approval_status'] = 204;
			$data['updated_by'] = CRUDBooster::myId();
			$data['reference_number'] = $item->reference_number;

		} else {
			// Handle create new production item
			$nextId = CodeCounter::where('type', 'PRODUCTION ITEMS')->value('code_7');
			CodeCounter::where('type', 'PRODUCTION ITEMS')->increment('code_7');

			$ref = $nextId;
 

			$message = "✔️ Successfully added pending item with Item code " . $ref;
		 
			$data['reference_number'] = $ref;
			$data['created_by'] = CRUDBooster::myId();
			$data['updated_by'] = CRUDBooster::myId();
			$data['approval_status'] = 204;
			$data['action_type'] = "CREATE";

			DB::table('cms_logs')->insert([
				'ipaddress' => request()->ip(),
				'useragent' => request()->userAgent(),
				'url' => request()->fullUrl(),
				'description' => 'User Production Item Creation',
				'details' => 'New production item has added to pending item with Item code ' . $ref,
				'id_cms_users' => CRUDBooster::myId(),
				'created_at' => now(),
				'updated_at' => now(),
			]);

			DB::table('production_items_history')->insert([
				'reference' => $ref,
				'action' => 'Create',
				'description' => 'User Production Item Creation',
				'key_old_value' => '',
				'description_old_value' => '',
				'key_new_value' => '',
				'description_new_value' => '',
				'updated_by' => CRUDBooster::myId() ?: 1,
				'details' => 'New production item has added to pending item with Item code ' . $ref,
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
 
		if (!empty($data['item_photo'])) {
			$filenameFiller = $data['tasteless_code'] ?? 'new_item';
			$randomString = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', Str::random(10));
			$imgFile = $data['item_photo'];
			$filename = date('Y-m-d') . "-{$filenameFiller}-{$randomString}." . $imgFile->getClientOriginalExtension();

			$image = Image::make($imgFile);
			$image->resize(1024, 768, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});
			$image->save(public_path('img/production-items/' . $filename));

			$optimizerChain = OptimizerChainFactory::create();
			$optimizerChain->optimize(public_path('img/production-items/' . $filename));

			$data['image_filename'] = $filename;
		}

		// Handle segmentation columns
		$segmentColumns = DB::table('segmentations')
			->where('status', 'ACTIVE')
			->pluck('segment_column_name')
			->toArray();

		$segmentations = (array) json_decode($data['segmentations'] ?? '[]');

		// Initialize all segmentation columns with default 'X'
		foreach ($segmentColumns as $segmentColumn) {
			$data[$segmentColumn] = 'X';
		}

		// Overwrite with actual segmentations from input
		foreach ($segmentations as $value => $columns) {
			foreach ($columns as $columnName) {
				$data[$columnName] = $value;
			}
		}

		// Process ingredients and labor lines
		$productionItemId = $data['reference_number'];
		$ingredients = $request->input('produtionlines', []);
		$laborLines = $request->input('LaborLines', []);

		$newItemCodesID = [];
		$newId = 0;  
		// Save ingredient lines 

		if($ingredients)
		{
		foreach ($ingredients as $ingredientGroup) {
			$parentId = ++$newId;
			foreach ($ingredientGroup as $ingredient) {
				ProductionItemLinesModelApproval::updateOrCreate(
					[
						'production_item_id' => $productionItemId,
						'production_item_line_id' => $newId,
					],
					[
						'production_item_id' => $productionItemId,
						'item_code' => $ingredient['tasteless_code'],
						'cost_contribution' => self::removePercent($ingredient['costparent-contribution'] ?? $ingredient['costparent-contribution-pack'] ?? null),
						'qty_contribution' => self::removePercent($ingredient['qty-contribution'] ?? $ingredient['qty-contribution-pack'] ?? null),
						'actual_pack_uom' => $ingredient['actual_pack_uom'],
						'description' => $ingredient['itemDesc'],
						'quantity' => $ingredient['quantity'],
						'yield' => $ingredient['yield'],
						'preparations' => $ingredient['preparations'],
						'landed_cost' => $ingredient['ttp'] ?? $ingredient['cost'],
						'packaging_id' => $parentId,
						'production_item_line_id' => $newId,
						'production_item_line_type' => $ingredient['production_type'],
						'approval_status' => 204,
					]
				);
				$newItemCodesID[] = $newId++;
			}
		}
	}
	if($laborLines)
	{
		// Save labor lines
		foreach ($laborLines as $laborLine) {
			$newId++;
			$newItemCodesID[] = $newId;
			ProductionItemLinesModelApproval::updateOrCreate(
				[
					'production_item_id' => $productionItemId,
					'production_item_line_id' => $newId,
				],
				[
					'production_item_id' => $productionItemId,
					'time_labor' => $laborLine['time-labor'],
					'labor_yield_uom' => $laborLine['labor_yield_uom'],
					'duration' => $laborLine['duration'],
					'yield' => $laborLine['yiel'],
					'preparations' => $laborLine['preparations'],
					'production_item_line_type' => $laborLine['production_item_line_type'],
					'approval_status' => 204,
					'production_item_line_id' => $newId,
				]
			);
		}
	}


		// Delete removed ingredients
		ProductionItemLinesModelApproval::where('production_item_id', $productionItemId)
			->whereNotIn('production_item_line_id', $newItemCodesID)
			->delete();

		

		// Retrieve existing final value or fallback
		$data['final_value_existing'] = DB::table('production_items_approvals')
			->where('reference_number', $data['reference_number'])
			->value('final_value_vatex') ?? $data['final_value_vatex'];

		// Update or create main production item
		ProductionItemsModelApproval::updateOrCreate(
			['reference_number' => $data['reference_number']],
			$data
		);

		// Redirect with success message
		return redirect(CRUDBooster::mainpath())
			->with([
				'message_type' => 'success',
				'message' => $message,
			])->send();

		
	}
	
	 function removePercent($value) {
		$cleanValue = str_replace('%', '', $value);
		return floatval($cleanValue);
	}
	 	public function getAdd() {
			if (!CRUDBooster::isCreate())
				CRUDBooster::redirect(
				CRUDBooster::adminPath(),
				trans('crudbooster.denied_access')
			);

			return self::getEdit(null, 'add');
		}

		public function getEdit($id, $action = 'edit', $approval_id = null) {
			
				if ($action == 'edit') {
					if (!CRUDBooster::isUpdate())
						CRUDBooster::redirect(
						CRUDBooster::adminPath(),
						trans('crudbooster.denied_access')
					);
				}
			
				$data = []; 
				$data['isAddPage'] = $action;
				/*
				$data['production_category'] = ProductionItemCategory::active();
				$data['storage_location'] = ProductionItemStorageLocation::active();
				$data['production_location'] = ProductionLocation::active();
				*/


			if ($id) { 
				$data['item'] = self::getItemDetails($id); 
				 // dd($data);
				/*
				if ($data['item']->approval_status == 202) {
					return redirect(CRUDBooster::mainpath())->with([
						'message_type' => 'danger',
						'message' => '✖️ You cannot edit a pending item...',
					]);
				}
			 	*/  
				} 
			
				$costings = self::costing(self::getItemDetails($id)->reference_number);
				 
			 

			 	 $data = array_merge($data, $costings);
			 	//dd($data);
				 
				return $this->view('production-items/add-production-item',   $data);
			}

	
	public function getDetail($id)
	{
		$data = []; 
		$data['isAddPage'] = "detail"; 
		$data['item'] = self::getItemDetails($id);  
		$costings = self::costing(self::getItemDetails($id)->reference_number);
		$data['view'] = 'true'; 
			

		$data = array_merge($data, $costings);  
		return $this->view('production-items/add-production-item', $data);
	}


	


	public function ingredientsSearch($id)
	{
		

			$item = DB::table('production_item_lines')
			->leftjoin('production_items', 'production_items.reference_number', '=', 'production_item_lines.production_item_id')
			->where('production_items.id', $id)
			->select('production_item_lines.*') 
			->get();
	
		 	
			return response()->json([
 				'produtionlines' => $item 
			]);
	}







	public function getItemDetails($id) { 
			$item = DB::table('production_items') 
				->select(
					 'production_items.*',
					 'brands.brand_description',
					 'suppliers.last_name'
				)
				->join('brands', 'production_items.brands_id','=','brands.id')
				->join('suppliers', 'production_items.suppliers_id','=','suppliers.id')
				->where('production_items.id', $id) 
				->limit(1)
				->first();


 			return $item;
		}


	public function costing($ref) {
	 
			$data = [];
			$data['tax_codes'] = DB::table('tax_codes')
				->where('status', 'ACTIVE')
				->orderBy('tax_description')
				->get()
				->toArray();

			$data['accounts'] = DB::table('accounts')
				->where('status', 'ACTIVE')
				->orderBy('group_description')
				->get()
				->toArray();

			$data['cogs_accounts'] = DB::table('cogs_accounts')
				->where('status', 'ACTIVE')
				->orderBy('group_description')
				->get()
				->toArray();

			$data['asset_accounts'] = DB::table('asset_accounts')
				->where('status', 'ACTIVE')
				->orderBy('group_description')
				->get()
				->toArray();

			$data['fulfillment_types'] = DB::table('fulfillment_methods')
				->where('status', 'ACTIVE')
				->orderBy('fulfillment_method')
				->get()
				->toArray();

			$data['uoms'] = DB::table('uoms')
				->where('status', 'ACTIVE')
				->orderBy('uom_description')
				->get()
				->toArray();

			$data['uom_sets'] = DB::table('uoms_set')
				->where('status', 'ACTIVE')
				->orderBy('uom_description')
				->get()
				->toArray();

			$data['currencies'] = DB::table('currencies')
				->where('status', 'ACTIVE')
				->orderBy('currency_code')
				->get()
				->toArray();

			$data['groups'] = DB::table('groups')
				->where('status', 'ACTIVE')
				->orderBy('group_description')
				->get()
				->toArray();

			$data['categories'] = DB::table('categories')
				->where('status', 'ACTIVE')
				->orderBy('category_description')
				->get()
				->toArray();

			$data['subcategories'] = DB::table('subcategories')
				->select('id', 'subcategory_description', 'categories_id')
				->where('status', 'ACTIVE')
				->orderBy('subcategory_description')
				->get()
				->toArray();

			$data['packagings'] = DB::table('packagings')
				->where('status', 'ACTIVE')
				->orderBy('packaging_description')
				->get()
				->toArray();
			
			$data['transfer_price_category'] = DB::table('transfer_price_category')
				->where('status', 'ACTIVE')
				->orderBy('transfer_price_category_description')
				->get()
				->toArray();

			$data['segmentations'] = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->orderBy('segment_column_description')
				->get()
				->toArray();

			$data['sku_legends'] = DB::table('sku_legends')
				->where('status', 'ACTIVE')
				->where('sku_legend', '!=', 'X')
				->get()
				->toArray();

			$data['sku_statuses'] = DB::table('sku_statuses')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			// EDIT ITEM
			$data['types'] = DB::table('types')
				->where('status', 'ACTIVE')
				->orderBy('type_description')
				->get()
				->toArray();
			$data['sku_legends'] = DB::table('sku_legends')
			->where('status', 'ACTIVE')
			->where('sku_legend', '!=', 'X')
			->get()
			->toArray();
			$data['segmentations'] = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->orderBy('segment_column_description')
				->get()
				->toArray();
			$data['production_category'] = DB::table('production_item_categories')
				->where('status', 'ACTIVE') 
				->get()
				->toArray();
			$data['storage_location'] = DB::table('production_item_storage_locations')
				->where('status', 'ACTIVE') 
				->get()
				->toArray();
			$data['production_location'] = DB::table('production_locations')
				->where('status', 'ACTIVE') 
				->get()
				->toArray(); 
			$data['production_items_comments'] = DB::table('production_items_comments')
				->select('production_items_comments.production_items_id',
				'production_items_comments.comment_content',
				'production_items_comments.comment_id',
				'production_items_comments.parent_id',
				'cms_users.name as created_by',
				'cms_users.photo as profile_pic',
				'production_items_comments.created_at',
				'production_items_comments.updated_at')
				->where('production_items_id', $ref) 
				->leftjoin('cms_users', 'production_items_comments.created_by', '=', 'cms_users.id')
				->get()
				->toArray(); 


			$data['production_item_lines'] =  DB::table('production_item_lines')
												->select('production_item_lines.*', 
												DB::raw('
													case WHEN item_masters.landed_cost is null
													THEN new_packagings.ttp
													ELSE item_masters.landed_cost
													END as default_cost
												'), 
												DB::raw('
													case WHEN item_masters.landed_cost is null
													THEN new_packagings.packaging_size 
													ELSE item_masters.packaging_size
													END as packaging_size
												'), 
												'production_item_lines.production_item_line_id',
												'item_masters.ttp',  
												'menu_ingredients_preparations.preparation_desc')
												->leftjoin('item_masters', 'production_item_lines.item_code', '=', 'item_masters.tasteless_code')
												->leftjoin('new_packagings', 'production_item_lines.item_code', '=', 'new_packagings.nwp_code')
												->leftjoin('menu_ingredients_preparations', 'production_item_lines.preparations', '=', 'menu_ingredients_preparations.id')
												->where('production_item_lines.production_item_id', $ref) 
												->orderBy('production_item_lines.production_item_line_id' , 'asc')
												->get()
												->toArray();  

			$data['menu_ingredients_preparations'] = DB::table('menu_ingredients_preparations') 
				->where('status', 'ACTIVE') 
				->get()
				->toArray(); 

			$data['comment_id'] = DB::table('production_items_comments')
				->select(DB::raw('MAX(ROUND(comment_id)) as max_comment_id'))
				->value('max_comment_id');
	
 

			return $data;
		}

	
			public function getProductionItemsSubmaster(Request $request, $table, $status, $status_value, $description) {
						$searchTerm = $request->input('search');
					  if (!Schema::hasColumn($table, $status) || !Schema::hasColumn($table, $description)) {
							return response()->json(['status_no' => 0, 'message' => 'Invalid columns'], 400);
						}
									
						$items = DB::table($table)
						->where($status, 'NOT LIKE', '%' . $status_value .'%')
						->where($description, 'LIKE', '%' . $searchTerm .'%')
						->select('id', "{$description} as description")
						->limit(50)
						->get()
						->toArray();
					
					return response()->json([
						'status_no' => 1,
						'items' => $items 
					]);
			}




		public function itemSearch(Request $request){
			$searchTerm = $request->input('search');
			$existing = $request->input('values');
			if (!$searchTerm) {
				return response()->json([
					'status_no' => 0,
					'message' => 'No search term provided.',
					'items' => null
				]);
			} 
 

			$items = ItemMaster::select(
				'id',
				'tasteless_code',
				'full_item_description',
				'landed_cost',
				'packaging_size') 
			->whereNotIn('tasteless_code', $existing)
			->whereRaw('(tasteless_code LIKE ? OR full_item_description LIKE ?)', ["%{$searchTerm}%", "%{$searchTerm}%"])
			->take(50)
			->get();

			if ($items->isEmpty()) {
				return response()->json([
					'status_no' => 0,
					'message' => 'Item not found.',
					'items' => null
				]);
			}

			$formattedItems = $items->map(function ($item) {
				return [
					'id' => $item->id,
					'tasteless_code' => $item->tasteless_code,
					'item_description' => $item->full_item_description,
					'cost' => $item->landed_cost, 
					'packaging_size' => $item->packaging_size, 
				];
			});
			
			return response()->json([
				'status_no' => 1,
				'items' => $formattedItems,
				'values' => $existing
			]);
		}




		public function PackageSearch(Request $request){
			$searchTerm = $request->input('search');
			$existing = $request->input('values');

			if (!$searchTerm) {
				return response()->json([
					'status_no' => 0,
					'message' => 'No search term provided.',
					'items' => null
				]);
			}

			/*
			$query1 = NewPackaging::select(
				DB::raw('id'),
				DB::raw('nwp_code as tasteless_code'),
				DB::raw('item_description')
			)
			->where('item_description', 'LIKE', '%' . $searchTerm . '%')
			->orWhere('nwp_code', 'LIKE', '%' . $searchTerm . '%')
			->take(100);

			$query2 = ItemMaster::select(
				DB::raw('id'),
				DB::raw('tasteless_code'),
				DB::raw('full_item_description  as item_description')
			)
			->where('full_item_description', 'LIKE', '%' . $searchTerm . '%')
			->orWhere('tasteless_code', 'LIKE', '%' . $searchTerm . '%')
			->take(100);
				
			$unionquery = $query1->unionAll($query2)->get();
			*/
			
 

			$query1 = NewPackaging::select('id',
				'nwp_code as tasteless_code',
				'item_description as full_item_description',
				'packaging_size',
				DB::raw('1 as db'),
				'ttp')
				->whereNotIn('nwp_code', $existing)
				->where(function($q) use ($searchTerm) {
					$q->where('item_description', 'LIKE', '%' . $searchTerm . '%')
					->orWhere('nwp_code', 'LIKE', '%' . $searchTerm . '%');
				})
				->take(100);

			$query2 = ItemMaster::select('id',
				'tasteless_code',
				'full_item_description',
				'packaging_size',
				DB::raw('2 as db'), 
				'landed_cost')
				->whereNotIn('tasteless_code', $existing)
				->where(function($q) use ($searchTerm) {
					$q->where('full_item_description', 'LIKE', '%' . $searchTerm . '%')
					->orWhere('tasteless_code', 'LIKE', '%' . $searchTerm . '%');
				})
				->take(100);

			$unionquery = $query1->unionAll($query2)->get(); 
 
			if ($unionquery->isEmpty()) {
				return response()->json([
					'status_no' => 0,
					'message' => 'Item not found.',
					'items' => null
				]);
			}
			
			$formattedItems = $unionquery->map(function ($items) {
				return [
					'id' => $items->id,
					'tasteless_code' => $items->tasteless_code, 
					'item_description' => $items->full_item_description,
					'from_db' => $items->db,
					'cost' => $items->ttp,
					'packaging_size' =>  $items->packaging_size
				];
			});
			

			return response()->json([
				'status_no' => 1,
				'items' => $formattedItems,
				'values' => $existing
			]);
		}


		public function SendComment(Request $request)
		{ 		
		
				$data =  $request->all();
				$data['created_by'] = CRUDBooster::myId();
				$data['created_at'] = now();
				$data['updated_at'] = now();
				 
				ProductionItemsComments::updateOrCreate(
					[
						'production_items_id' => $data['production_items_id'],
						'comment_id' => $data['comment_id'] 	
					], 
					$data
				); 
				 
			 	
				
			return response()->json($data);
		}

	 	public function exportItems(Request $request) {
				$filename = $request->input('filename') . '.csv';

				$callback = function () {
    			$handle = fopen('php://output', 'w');

    			// Header row (adjusted to match your new query columns)
				fputcsv($handle, [
					'Reference Number',
					'Item Code',
					'Description',
					'Quantity',
					'Landed Cost',
					'Yield',
					'Packaging ID',
					'Approved By (Line)',
					'Production Item Line ID',
					'Approval Status (Line)',
					'Production Item Line Type',
					'Preparation Description',
					'Time Labor',
					'Cost Contribution',
					'Qty Contribution',
					'Duration',
					'Actual Pack UOM',
					'Labor Yield UOM',
					'Category Description',
					'Production Location Description',
					'Labor Cost',
					'Labor Cost Per Minute',
					'Total Minutes Per Pack',
					'Labor Cost Value',
					'Gas Cost',
					'Gas Cost X FC',
					'Transfer Price Category',
					'Packaging Cost',
					'Storage Cost',
					'Storage Cost X FC',
					'Meralco',
					'Meralco X FC',
					'Water',
					'Water X FC',
					'Storage Multiplier',
					'Total Storage Cost',
					'Storage Location',
					'Raw Mast Provision',
					'Markup Percentage',
					'Final Value Existing',
					'Final Value VAT Excluded',
					'Final Value VAT Included',
					'Action Type',
					'Full Item Description',
					'Approved By (Item)',
					'Approval Status (Item)',
					'Approved At',
					'Created By',
					'Updated By',
					'Created At',
					'Updated At',
					'OPEX'
				]);

				DB::table('production_items')
					->leftJoin('production_item_lines', 'production_items.reference_number', '=', 'production_item_lines.production_item_id')
					->leftJoin('production_locations', 'production_items.production_location', '=', 'production_locations.id')
					->leftJoin('production_item_categories', 'production_items.production_category', '=', 'production_item_categories.id')
					->leftJoin('menu_ingredients_preparations', 'production_item_lines.preparations', '=', 'menu_ingredients_preparations.id')
					->select(
						'production_items.reference_number',
						'production_item_lines.item_code',
						'production_item_lines.description',
						'production_item_lines.quantity',
						'production_item_lines.landed_cost',
						'production_item_lines.yield',
						'production_item_lines.packaging_id',
						'production_item_lines.approved_by',
						'production_item_lines.production_item_line_id',
						'production_item_lines.approval_status',
						'production_item_lines.production_item_line_type',
						'menu_ingredients_preparations.preparation_desc',
						'production_item_lines.time_labor',
						'production_item_lines.cost_contribution',
						'production_item_lines.qty_contribution',
						'production_item_lines.duration',
						'production_item_lines.actual_pack_uom',
						'production_item_lines.labor_yield_uom',
						'production_item_categories.category_description',
						'production_locations.production_location_description',
						'production_items.labor_cost',
						'production_items.labor_cost_per_minute',
						'production_items.total_minutes_per_pack',
						'production_items.labor_cost_val',
						'production_items.gas_cost',
						'production_items.gas_costxfc',
						'production_items.transfer_price_category',
						'production_items.packaging_cost',
						'production_items.storage_cost',
						'production_items.storage_costxfc',
						'production_items.meralco',
						'production_items.meralcoxfc',
						'production_items.water',
						'production_items.waterxfc',
						'production_items.storage_multiplier',
						'production_items.total_storage_cost',
						'production_items.storage_location',
						'production_items.raw_mast_provision',
						'production_items.markup_percentage',
						'production_items.final_value_existing',
						'production_items.final_value_vatex',
						'production_items.final_value_vatinc',
						'production_items.action_type',
						'production_items.full_item_description',
						'production_items.approved_by',
						'production_items.approval_status',
						'production_items.approved_at',
						'production_items.created_by',
						'production_items.updated_by',
						'production_items.created_at',
						'production_items.updated_at',
						'production_items.opex'
					)
					->cursor()
					->each(function ($row) use ($handle) {
						fputcsv($handle, [
							$row->reference_number,
							$row->item_code,
							$row->description,
							$row->quantity,
							$row->landed_cost,
							$row->yield,
							$row->packaging_id,
							$row->approved_by,
							$row->production_item_line_id,
							$row->approval_status,
							$row->production_item_line_type,
							$row->preparation_desc,
							$row->time_labor,
							$row->cost_contribution,
							$row->qty_contribution,
							$row->duration,
							$row->actual_pack_uom,
							$row->labor_yield_uom,
							$row->category_description,
							$row->production_location_description,
							$row->labor_cost,
							$row->labor_cost_per_minute,
							$row->total_minutes_per_pack,
							$row->labor_cost_val,
							$row->gas_cost,
							$row->gas_costxfc,
							$row->transfer_price_category,
							$row->packaging_cost,
							$row->storage_cost,
							$row->storage_costxfc,
							$row->meralco,
							$row->meralcoxfc,
							$row->water,
							$row->waterxfc,
							$row->storage_multiplier,
							$row->total_storage_cost,
							$row->storage_location,
							$row->raw_mast_provision,
							$row->markup_percentage,
							$row->final_value_existing,
							$row->final_value_vatex,
							$row->final_value_vatinc,
							$row->action_type,
							$row->full_item_description,
							$row->approved_by,
							$row->approval_status,
							$row->approved_at,
							$row->created_by,
							$row->updated_by,
							$row->created_at,
							$row->updated_at,
							$row->opex
						]);
					});

				fclose($handle);
			};

			return response()->streamDownload($callback, $filename, [
				'Content-Type' => 'text/csv',
			]);
 

		}
 
	}