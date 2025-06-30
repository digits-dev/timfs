<?php namespace App\Http\Controllers\ProductionItems;

	use Session;

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
use App\Models\ProductionItems\ProductionItems;
use App\NewPackaging;

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
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Description","name"=>"description"];
			$this->col[] = ["label"=>"Production Category","name"=>"production_category","join"=>"production_item_categories,category_description" ];
			$this->col[] = ["label"=>"Production Location","name"=>"production_location","join"=>"production_locations,production_location_description"];
			$this->col[] = ["label"=>"Depreciation","name"=>"depreciation"];
			$this->col[] = ["label"=>"Final Value Vatex","name"=>"final_value_vatex"];
			$this->col[] = ["label"=>"Final Value Vatinc","name"=>"final_value_vatinc"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name" ];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name" ];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated At","name"=>"updated_at"]; 
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
	        $this->table_row_color = array();     	          

	        
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
	        $this->table_row_color = array();     	          

	        
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
	        //Your code here
	            
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

 

		public function getItemLastDetails($id) {
			$item = DB::table('production_items') 
				->select( 
					'production_items.id',
					'production_items.reference_number',
					'production_items.description',
					'production_items.production_category',
					'production_items.production_location', 
					'production_items.labor_cost',
					'production_items.gas_cost',
					'production_items.utilities',
					'production_items.storage_cost',
					'production_items.storage_multiplier',
					'production_items.total_storage_cost',
					'production_items.storage_location',
					'production_items.depreciation',
					'production_items.raw_mast_provision',
					'production_items.markup_percentage',
					'production_items.final_value_vatex',
					'production_items.final_value_vatinc',
					'production_items.created_by',
					'production_items.updated_by',
					'production_items.created_at',
					'production_items.updated_at'
				)
				->where('production_items.id', $id)
				->limit(1)
				->first();


			return $item;
		}

		 

	public function generateChangesTableFields(array $differences): string {
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

					$html .= '<tr style="border-bottom: 1px solid #eee;">';
					$html .= "<td style='padding: 8px 12px; border-right: 1px solid #ddd; color: #333;'>{$key}</td>";
					$html .= "<td style='padding: 8px 12px; border-right: 1px solid #ddd; color: #555;'>{$old}</td>";
					$html .= "<td style='padding: 8px 12px; color: #555;'>{$new}</td>";
					$html .= '</tr>';
				}

				$html .= '</tbody></table>';

				return $html;
			}

	

	public function generateChangesTable($new_data, $old_data, $reference_number, $created_at): string
	{
		 
 
		$html = '
				<style>
				.ingredients-table {
					width: 100%;
					border-collapse: separate;
					border-spacing: 0 10px; /* vertical space between groups */
					font-family: Arial, sans-serif;
					font-size: 14px;
				}
				.ingredients-table thead tr {
					background-color: #f0f0f0;
				}
				.ingredients-table thead th {
					padding: 10px 15px;
					border-bottom: 2px solid #ccc;
					text-align: left;
				}
				.ingredients-table tbody tr.group-row td {
					padding: 8px 15px;
					border: none; /* remove inner horizontal borders */
					background-color: #fff; /* white background */
					vertical-align: middle;
				}
				.ingredients-table tbody tr.added td {
					background-color: #e6f4ea; /* soft green */
				}
				.ingredients-table tbody tr.deleted td {
					background-color: #fdecea; /* soft red */
				}
				.ingredients-table tbody tr.updated td {
					background-color: #fff9e6; /* soft yellow */
				}
				/* Remove border bottom from all rows except last in group */
				.ingredients-table tbody tr:not(.group-row) td {
					border-bottom: none !important;
				}
				/* Optional: add subtle border or shadow around groups by adding it to last row of each group */
				/* You can keep your existing logic to add classes like added, deleted, updated */
				.label {
					display: inline-block;
					padding: 0.25em 0.6em;
					font-size: 75%;
					font-weight: 600;
					line-height: 1;
					color: #fff;
					text-align: center;
					white-space: nowrap;
					vertical-align: baseline;
					border-radius: 0.375rem;
					user-select: none;
					margin-right: 5px;
				}
				.label-info {
					background-color: #17a2b8; /* blue */
				}
				.label-danger {
					background-color: #dc3545; /* red */
				}

					.ingredients-table {
				border-collapse: separate;
				border-spacing: 0 10px; /* space between groups */
				}

				/* Remove borders from all rows first */
				.ingredients-table tbody tr td {
				border: none;
				padding: 8px 15px;
				}

				/* Add border only after every 4 rows (1 group of description, qty, cost, yield) */
				.ingredients-table tbody tr:nth-child(5n) td {
				border-bottom: 2px solid #ccc; /* solid border after every group */
				}
				.label-warning {
					background-color: #ffc107; /* yellow */
					color: #212529; /* dark text for contrast */
				}
				</style>

				<table class="ingredients-table">
					<thead>
						<tr>
							<th>Key</th>
							<th>Old Ingredients</th>
							<th>New Ingredients</th>
						</tr>
					</thead>
					<tbody>
				';

	 
		$newdatacount = count($new_data);
		$olddatacount = count($old_data);
		 
 
		//clean the keys of ingredients to match index on old datas
		$newArray = [];
		foreach ($new_data as $item) {
			$newArray[] = $item;   
		}
 
		if($newdatacount >= $olddatacount)
		{
			for ($i = 0; $i < $newdatacount; $i++) {
				 
				foreach ($newArray[$i] as $key => $value) {
					 
					$safe_key = htmlspecialchars($key);
					$safe_value = htmlspecialchars($value);
 
					if($value != $old_data[$i][$key]){


							DB::table('production_items_history')->insert([
							'reference' => $reference_number,
							'item_code' =>  $old_data[$i]['item_code'],
							'action' => 'Update Production Item lines', 
							'description' => 'User Production Item Creation',
							'old_data' => $safe_key .': '. $safe_value,
							'new_data'  =>   $safe_key .': '. $old_data[$i][$key],
							'details'  =>  'new production item has been created with reference number ' . $reference_number,
							'created_at' => $created_at,  
							'updated_at' => now(),
							]);



						if($safe_value != '' && $old_data[$i][$key] == '')
						{
							$html .= "<tr class='group-row added'>
								<td style='padding: 8px 12px;'><span class='label label-info'>New! </span> {$safe_key}</td>
								<td style='padding: 8px 12px;'>N/A</td>
								<td style='padding: 8px 12px;'>{$safe_value}</td>
								</tr>";
						}else if($safe_value == '' && $old_data[$i][$key] != '')
						{
							$html .= "<tr class='group-row deleted'>
								<td style='padding: 8px 12px;'><span class='label label-danger'>Deleted </span> {$safe_key}</td>
								<td style='padding: 8px 12px;'>{$old_data[$i][$key]}</td>
								<td style='padding: 8px 12px;'>N/A</td>
								</tr>";	
						}else
						{
							$html .= "<tr class='group-row updated'>
								<td style='padding: 8px 12px;'><span class='label label-warning'>Updated </span> {$safe_key}</td>
								<td style='padding: 8px 12px;'>{$old_data[$i][$key]}</td>
								<td style='padding: 8px 12px;'>{$safe_value}</td>
								</tr>";	
						} 
					}else
					{ 
 						$html .= "<tr class='group-row ' style='border-bottom: 1px solid #eee;'>
									<td style='padding: 8px 12px;'>{$safe_key}</td>
									<td style='padding: 8px 12px;'>{$old_data[$i][$key]}</td>
									<td style='padding: 8px 12px;'>{$safe_value}</td>
									</tr>";
					} 
				}
				
			}
		
		}
		 else
		{ 
			for ($i = 0; $i < $olddatacount; $i++) {

				//dd($new_data[$i]['description']);
				foreach ($old_data[$i] as $key => $value) {  

					$safe_key = htmlspecialchars($key);
					$safe_value = htmlspecialchars($value); 
					if($value != $new_data[$i][$key]){ 

						DB::table('production_items_history')->insert([
						'reference' => $reference_number,
						'item_code' =>  $old_data[$i]['item_code'],
						'action' => 'Update Production Item lines', 
						'description' => 'User Production Item Creation',
						'old_data' => $safe_key .': '. $safe_value,
						'new_data'  =>   $safe_key .': '. $old_data[$i][$key],
						'details'  =>  'new production item has been created with reference number ' . $reference_number,
						'created_at' => $created_at,  
						'updated_at' => now(),
						]);

					 	if($safe_value != '' && $new_data[$i][$key] == '')
						{
							$html .= "<tr class='group-row deleted'>
								<td style='padding: 8px 12px;'><span class='label label-danger'>Deleted</span> {$safe_key}</td>
								<td style='padding: 8px 12px;'>{$safe_value}</td>
								<td style='padding: 8px 12px;'>N/A</td>
								</tr>";
						}
						else if($safe_value == '' && $new_data[$i][$key] != '')
						{
							$html .= "<tr class='group-row added'>
								<td style='padding: 8px 12px;'><span class='label label-info'>New! </span>{$safe_key}</td>
								<td style='padding: 8px 12px;'>N/A</td>
								<td style='padding: 8px 12px;'>{$new_data[$i][$key]}</td>
								</tr>";	
						}
						else
						{
							$html .= "<tr class='group-row updated'>
								<td style='padding: 8px 12px;'><span class='label label-warning'>Updated </span>{$safe_key}</td>
								<td style='padding: 8px 12px;'>{$safe_value}</td>
								<td style='padding: 8px 12px;'>{$new_data[$i][$key]}</td> 
								</tr>";	
						}
						
					}
					else
					{ 
						$html .= "<tr class='group-row ' style='border-bottom: 1px solid #eee;'>
								<td style='padding: 8px 12px;'>{$safe_key}</td>
								<td style='padding: 8px 12px;'>{$safe_value}</td>
								<td style='padding: 8px 12px;'>{$new_data[$i][$key]}</td>
								</tr>";
					} 
				} 
			}
		}


		 
		$html .= '</tbody></table>';

		return '<div>' . $html .'</div>';



	}







	public function addProductionItemsToDB(Request $request){
 
		$message = '';
		$time_stamp_now = date('Y-m-d H:i:s');
			
		
			$validated = $request->validate([ 
				'description' => 'required|string',
				'production_category' => 'required|integer',
				'production_location' => 'required|integer',  
				'labor_cost' => 'required|numeric|max:99999999.99',
				'gas_cost' => 'required|numeric|max:99999999.99',
				'storage_cost' => 'required|numeric|max:99999999.99',
				'storage_multiplier' => 'required|numeric|max:99999999.99',
				'total_storage_cost' => 'required|numeric|max:99999999.99',
				'storage_location' => 'nullable|integer',
				'depreciation' => 'required|numeric|max:99999999.99',
				'raw_mast_provision' => 'required|numeric|max:99999999.99',
				'markup_percentage' => 'required|numeric|max:99999999.99',
				'final_value_vatex' => 'required|numeric|max:99999999.99',
				'final_value_vatinc' => 'required|numeric|max:99999999.99', 
			]);

			$data = $validated;

			


			if($request['id']){
			 
				
					$lastData= [];
					$lastData = self::getItemLastDetails($request['id']); // Old data from DB
					$currentData = $request->only(array_keys((array) $lastData)); // Current data from request, matching keys
					$item = ProductionItems::where('id', $request['id'])
					->select('reference_number', 'created_at')
					->first();

					// Find differences: fields if current != lastdata
					$differences = [];
					foreach ($lastData as $key => $value) {
						
						if (isset($currentData[$key]) && (string)$currentData[$key] !== (string)$value) {
							$differences[$key] = [
								'old' => $value,
								'new' => $currentData[$key],
							];
						}
					}
					
					
					
					
					

					// //check lines data vs. last data
					$response = self::ingredientsSearch($request['id']); // Old data ingredients
					
					$ingredients = $response->getOriginalContent()['produtionlines']->map(function ($item) {
						return [
							'description' => $item->description,
							'item_code' => $item->item_code,
							'quantity' => $item->quantity,
							'cost' => $item->landed_cost,
							'yield' => $item->yield ?? 'N/A',
						];
					})->toArray();  
					$ingredientsFromRequest = $request->input('produtionlines'); // New ingredients from request --(needs key index clean to match key on old data and check what change, you can see cleaning on generateChangesTable fucntion)

				 

					$old_ingredients_data = [];
					$new_ingredients_data = [];

					foreach ($ingredients as $key => $ingredient) {
						$old_ingredients_data[$key] = [
							'description' => $ingredient['description'],
							'item_code'   =>$ingredient['item_code'],
							'quantity'    => round($ingredient['quantity'],2),
							'cost'    	  => round($ingredient['cost'],2),
							'yield'       => (round($ingredient['yield'],2) != 0) ? round($ingredient['yield'],2) : 'N/A',
						];
					}
					
					foreach ($ingredientsFromRequest as $key => $ingredient) {

						foreach ($ingredient as $code => $parent_code) {
							$new_ingredients_data[$code] = [
								'description' => $parent_code['description'],
								'item_code'   => $parent_code['tasteless_code'],
								'quantity'    => round($parent_code['quantity'],2),
								'cost'        => round($parent_code['cost'],2),
								'yield'       => (round($parent_code['yield'],2) != 0) ? round($parent_code['yield'],2) : 'N/A',
							]; 
						}  
					} 
				

					

 

					// //get generated HTML
					$detailsHtmlFields = $this->generateChangesTableFields($differences);
				 	$detailsHtmlIngredients = $this->generateChangesTable($new_ingredients_data, $old_ingredients_data,  $item->reference_number, $item->created_at);
					

				 	 // if nothing changes return error
					if($detailsHtmlFields == 'null' && $detailsHtmlIngredients == 'null')
					{
						return response()->json(['No changes are made but your trying to update?'
							], 422);  
					}

					if($detailsHtmlIngredients == 'null')
					{
						
						$detailsHtmlIngredients = '<p style="font-family: Arial, sans-serif; font-size: 14px;">No changes detected.</p>';
						
					}
					
					if($detailsHtmlFields == 'null')
					{
						$detailsHtmlFields = '<p style="font-family: Arial, sans-serif; font-size: 14px;">No changes detected.</p>';
					} 
					

					// //combine 2 html generated
					$combinedDetails ='<hr> <label style="font-size: 20px; font-weight: bold; color: #f1c40f; background-color: #2c3e50; padding: 6px 12px; border-radius: 6px; display: inline-block;"> Fields changes </label>' . $detailsHtmlFields . '<hr> <label style="font-size: 20px; font-weight: bold; color: #f1c40f; background-color: #2c3e50; padding: 6px 12px; border-radius: 6px; display: inline-block;"> Ingredients Table </label>' . $detailsHtmlIngredients;
					
					//push logs to DB cms_logs
					DB::table('cms_logs')->insert([
						'ipaddress' => request()->ip(),
						'useragent' => request()->userAgent(),
						'url' => request()->fullUrl(),
						'description' => 'Update data at production item reference number '. $item->reference_number,
						'details' => $combinedDetails,
						'id_cms_users' => CRUDBooster::myId() ?: 1,
						'created_at' =>$item->created_at,
						'updated_at' => now(),
					]);




					DB::table('production_items_history')->insert([
					'reference' =>  $item->reference_number,
					'action' => 'Update', 
					'description' => 'User Production Item Creation ' .  $item->reference_number,
					'old_data' => '',
					'new_data'  =>  '',
					'details'  =>   $combinedDetails,
					'created_at' =>  $item->created_at,  
					'updated_at' => now(),
					]);

					$message = "✔️ Item updated successfully...";
					 
					
					$data['updated_at'] = $time_stamp_now;
					$data['updated_by'] = CRUDBooster::myId();
					$data['reference_number'] = $item->reference_number;

					//delete old ingredients to db for new add
					//DB::table('production_item_lines')->where('production_item_id', $production_items_toDB->reference_number)->delete();


					$message = "✔️ Item reference number " . $data['reference_number'] . " updated successfully...";


		}
		else
 		{ 
			
				$nextId = DB::table('production_items')->max('id') + 1;
				$ref = 700000000 + $nextId;
				$message = "✔️ Item Added successfully with reference number ". $ref;		
				$data['reference_number'] = $ref;
				
				$data['created_by'] = CRUDBooster::myId();
				$data['updated_by'] = CRUDBooster::myId();

			
			 

				DB::table('cms_logs')->insert([
				'ipaddress' => request()->ip(),
				'useragent' => request()->userAgent(),
				'url' => request()->fullUrl(),
				'description' => 'User Production Item Creation',
				'details'  =>  'new production item has been created with reference number ' . $ref,
				'id_cms_users' => CRUDBooster::myId(),
				'created_at' => now(),
				'updated_at' => now(),
				]);

				DB::table('production_items_history')->insert([
				'reference' => $ref,
				'action' => 'Create', 
				'description' => 'User Production Item Creation',
				'old_data' => '',
				'new_data'  =>  '',
				'details'  =>  'new production item has been created with reference number ' . $ref, 
				'created_at' => now(),
				'updated_at' => now(),
				]);


		}

			$ingredients = $request->input('produtionlines'); // get the array

			DB::table('production_item_lines')->where('production_item_id', $data['reference_number'])->delete(); 	

		 

			// $newIngredients = [];
			// $counter = 0;

			// foreach ($ingredients as $parentKey => $childArray) {
			// 	$newIngredients[$parentKey] = [];
			// 	foreach ($childArray as $child) {
			// 		$newIngredients[$parentKey][$counter++] = $child;
			// 	}
			// }

			// $ingredients = $newIngredients;

 



			 


			if (count($ingredients) > 0) {
				foreach ($ingredients as $parentCode => $ingredientGroup) {
					 
					foreach ($ingredientGroup as $key => $ingredient) { 
						self::ingredientSearchToItemMaster(
						 
							$data['reference_number'],
							$ingredient['tasteless_code'],
							$ingredient['description'],
							$ingredient['quantity'],
							$ingredient['yield'],
							$ingredient['cost'],
							$parentCode
						);
					}
				}
			}

			//loop each ingredients and save sa DB 	production_item_lines table
			// dd($ingredients); 
				$cost = ProductionItems::updateOrCreate(
					['reference_number' => $data['reference_number']],
					$data
				);
			 //return redirect()->back()->with('success', 'Production item saved successfully!');
			return redirect(CRUDBooster::mainpath())
			->with([
					'message_type' => 'success',
					'message' => $message,
			])->send();
		
	}

		public function ingredientSearchToItemMaster($reference_number, $tasteless_code, $description, $quantity, $yield, $landed_cost, $packaging_id)
		{ 
			

			$production_items_toDB = new ProductionItemLines();

			$production_items_toDB->production_item_id = $reference_number;
			$production_items_toDB->item_code = $tasteless_code;  
			$production_items_toDB->description = $description;
			$production_items_toDB->quantity = $quantity;
			$production_items_toDB->landed_cost = $landed_cost;
			$production_items_toDB->yield = $yield;
			$production_items_toDB->packaging_id = $packaging_id;
			$production_items_toDB->is_alternative = 1;
 
			$production_items_toDB->save();
 
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
			 	//dd($costings);
				 
				return $this->view('production-items/add-production-item',   $data);
			}

	
	public function getDetail($id)
	{
		 
				if ($action == 'edit') {
					if (!CRUDBooster::isUpdate())
						CRUDBooster::redirect(
						CRUDBooster::adminPath(),
						trans('crudbooster.denied_access')
					);
				}
				
				$data = []; 
				/*
				$data['production_category'] = ProductionItemCategory::active();
				$data['storage_location'] = ProductionItemStorageLocation::active();
				$data['production_location'] = ProductionLocation::active();
				*/

 
				$data['item'] = self::getItemDetails($id); 
				/*
				if ($data['item']->approval_status == 202) {
					return redirect(CRUDBooster::mainpath())->with([
						'message_type' => 'danger',
						'message' => '✖️ You cannot edit a pending item...',
					]);
				}
			 	*/  
			 
				$costings = self::costing(self::getItemDetails($id)->reference_number);
				 
			 

			 	 $data = array_merge($data, $costings); 
				  
				
				//dd($costings);
				return $this->view('production-items/detail-production-item',   $data); 
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
					 '*'
				)
				->where('production_items.id', $id) 
				->limit(1)
				->first();



 			return $item;
		}


	public function costing($ref) {
	 
			$data = [];

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
			$data['production_item_lines'] = DB::table('production_item_lines')
			->select('production_item_lines.*', 'item_masters.ttp', 'item_masters.packaging_size')
			->leftjoin('item_masters', 'production_item_lines.item_code', '=', 'item_masters.tasteless_code')
			->where('production_item_lines.production_item_id', $ref) 
			->orderBy('production_item_lines.id')
			->get()
			->toArray();

		 	 
		

			return $data;
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
				'ttp',
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
					'cost' => $item->ttp, 
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
					'cost' => $items->ttp,   
				];
			});
			

			return response()->json([
				'status_no' => 1,
				'items' => $formattedItems,
				'values' => $existing
			]);
		}

	 	public function exportItems(Request $request) {
			//dd($request);
			//$filename = $request->input('filename');
			//return Excel::download(new ProdutionItems, $filename.'.xlsx');
				$filename = $request->input('filename') . '.csv';

		$callback = function () {
			$handle = fopen('php://output', 'w');

			// Header row
			fputcsv($handle, [
				'Reference Number',
				'Productionion Description',
				'Productionion Category',
				'Productionion Location',
				'Labor Cost',
				'Gas Cost',
				'Utilities',
				'Storage Cost',
				'Total Storage Cost',
				'Depreciation',
				'Raw Mast Provision',
				'Markup Percentage',
				'Final Value (VAT Excluded)',
				'Final Value (VAT Included)',
				'Item Code',
				'Line Description',
				'Quantity',
				'Landed Cost',
				'Is Alternative',
				'Created By',
				'Updated By',
				'Created At',
				'Updated At'
			]);

			DB::table('production_items')
			->leftJoin('production_item_lines', 'production_items.reference_number', '=', 'production_item_lines.production_item_id')
			->leftJoin('production_locations', 'production_items.production_location', '=', 'production_locations.id')
			->leftJoin('production_item_categories', 'production_items.production_category', '=', 'production_item_categories.id')
			->select(
				'production_items.reference_number',
				'production_items.description as product_description',
				'production_item_categories.category_description',
				'production_locations.production_location_description',
				'production_items.labor_cost',
				'production_items.gas_cost',
				'production_items.utilities',
				'production_items.storage_cost',
				'production_items.total_storage_cost',
				'production_items.depreciation',
				'production_items.raw_mast_provision',
				'production_items.markup_percentage',
				'production_items.final_value_vatex',
				'production_items.final_value_vatinc',
				'production_item_lines.item_code',
				'production_item_lines.description as ingredient_description',
				'production_item_lines.quantity',
				'production_item_lines.landed_cost',
				'production_item_lines.is_alternative',
				'production_items.created_by',
				'production_items.updated_by',
				'production_items.created_at',
				'production_items.updated_at'
			)->cursor()->each(function ($row) use ($handle) {
 						fputcsv($handle, [
							$row->reference_number,
							$row->product_description,
							$row->category_description,
							$row->production_location_description,
							$row->labor_cost,
							$row->gas_cost,
							$row->utilities,
							$row->storage_cost,
							$row->total_storage_cost,
							$row->depreciation,
							$row->raw_mast_provision,
							$row->markup_percentage,
							$row->final_value_vatex,
							$row->final_value_vatinc,
							$row->item_code,
							$row->ingredient_description,
							$row->quantity,
							$row->landed_cost,
							$row->is_alternative,
							$row->created_by,
							$row->updated_by,
							$row->created_at,
							$row->updated_at
						]);
					});

				fclose($handle);
			};

			return response()->streamDownload($callback, $filename, [
				'Content-Type' => 'text/csv',
			]);
		}
 
	}