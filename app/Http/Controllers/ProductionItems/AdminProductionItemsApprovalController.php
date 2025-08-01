<?php

namespace App\Http\Controllers\ProductionItems;
 
	use App\Models\ProductionItems\ProductionItemLines; 
	use App\Models\ProductionItems\ProductionItemsModelApproval;
 	use App\Models\ProductionItems\ProductionItems;
	use crocodicstudio\crudbooster\helpers\CRUDBooster;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB; 

class AdminProductionItemsApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {
 
    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "updated_at,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "production_items_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Description","name"=>"full_item_description"];
			$this->col[] = ["label" => "Approval Status", "name" => "approval_status",
							"callback"=>function($row)
							{
								 if ($row->approval_status == '202') {
									return '<center><span style="
											background-color:rgb(252, 164, 41); 
											color: white; 
											padding: 3px 8px; 
											border-radius: 3px; 
											font-weight: bold; 
											font-size: 8px; 
											text-align: center;
											min-width: 20px;
										">PENDING</span></center>';
								}else if ($row->approval_status == '400')
								{
									return '<center><span style="
											background-color:rgba(255, 0, 0, 0.86); 
											color: white; 
											padding: 3px 8px; 
											border-radius: 3px; 
											font-weight: bold; 
											font-size: 8px; 
											text-align: center;
											min-width: 20px;
										">REJECT</span></center>';
								}
								else if ($row->approval_status == '200')
								{
									return '<center><span style="
											background-color:rgb(0, 255, 34); 
											color: white; 
											padding: 3px 8px; 
											border-radius: 3px; 
											font-weight: bold; 
											font-size: 8px; 
											text-align: center;
											min-width: 20px;
										">APPROVED</span></center>';
								}
							}];
			$this->col[] = ["label"=>"Action Type","name"=>"action_type"];
			$this->col[] = ["label"=>"Production Category","name"=>"production_category","join"=>"production_item_categories,category_description" ];
			$this->col[] = ["label"=>"Production Location","name"=>"production_location","join"=>"production_locations,production_location_description"];
				$this->col[] = ["label"=>"FC Landed cost","name"=>"landed_cost"];
			$this->col[] = ["label"=>"OPEX","name"=>"opex"];
			$this->col[] = ["label"=>"PM / Store Supplies", "name" => "packaging_cost","callback"=>function($row){
				return round($row->packaging_cost , 2);
			}];
			$this->col[] = ["label"=>"TP (Existing)","name"=>"final_value_existing"];
			$this->col[] = ["label"=>"TP Vat Ex (Revised Price)","name"=>"final_value_vatex"];
			$this->col[] = ["label"=>"TP Vat Inc (Updated)","name"=>"final_value_vatinc"];
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
			$this->addaction[] = [
					'title'=>'Approve',
					'url'=>CRUDBooster::mainpath('approve_or_reject_production_items/[id]'),
					'icon'=>'fa fa-thumbs-up',
					'color' => ' ',
					"showIf"=>"[approval_status] == '202'",
			];

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
			 	$pending_count = DB::table('production_items_approvals')
					->where('approval_status', '202')
					->count();
				$approved_count = DB::table('production_items_approvals')
					->where('approval_status', '200')
					->count();
				$rejected_count = DB::table('production_items_approvals')
					->where('approval_status', '400')
					->count();
				$this->index_statistic[] = [
					'label' => 'Pending Items',
					'count' => $pending_count,
					'icon' => 'fa fa-hourglass-half',
					'color' => 'orange',
				];
				$this->index_statistic[] = [
					'label' => 'Approved Items',
					'count' => $approved_count,
					'icon' => 'fa fa-thumbs-up',
					'color' => 'green',
				];
				$this->index_statistic[] = [
					'label' => 'Rejected Items',
					'count' => $rejected_count,
					'icon' => 'fa fa-thumbs-down',
					'color' => 'red',
				]; 



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	    

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
	        $query->orderByRaw('GREATEST(production_items_approvals.created_at, production_items_approvals.updated_at) DESC');     
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

		public function approveOrReject($id)
		{	
			$data = [];   
			$data['item'] = self::getItemDetails($id);  
			$costings = self::costing(self::getItemDetails($id)->reference_number);
				 
			 

			$data = array_merge($data, $costings); 
		 
			return $this->view('production-items/add-production-item', $data);
		}
		
		public function getItemDetails($id) {
			$item = DB::table('production_items_approvals') 
				->select(
					 'production_items_approvals.*',
					 'brands.brand_description',
					 'suppliers.last_name'
				)
				->join('brands', 'production_items_approvals.brands_id','=','brands.id')
				->join('suppliers', 'production_items_approvals.suppliers_id','=','suppliers.id')
				->where('production_items_approvals.id', $id) 
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
			$data['transfer_price_category'] = DB::table('transfer_price_category')
				->where('status', 'ACTIVE')
				->orderBy('transfer_price_category_description')
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
					
			$data['production_item_lines'] = DB::table('production_item_lines_approvals')
											->select('production_item_lines_approvals.*', 
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
											'production_item_lines_approvals.production_item_line_id',
											'item_masters.ttp',  
											'menu_ingredients_preparations.preparation_desc')
											->leftjoin('item_masters', 'production_item_lines_approvals.item_code', '=', 'item_masters.tasteless_code')
											->leftjoin('new_packagings', 'production_item_lines_approvals.item_code', '=', 'new_packagings.nwp_code')
											->leftjoin('menu_ingredients_preparations', 'production_item_lines_approvals.preparations', '=', 'menu_ingredients_preparations.id')
											->where('production_item_lines_approvals.production_item_id', $ref) 
											->orderBy('production_item_lines_approvals.production_item_line_id' , 'asc')
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



		public function addProductionItemsToDB(Request $request){
	 
			$message = ''; 
			$data =  $request->all();
 			$ref = $data['reference_number'];
			//	dd($request->action);
			if($request->action == 'reject')
			{
				DB::table('cms_logs')->insert([
				'ipaddress' => request()->ip(),
				'useragent' => request()->userAgent(),
				'url' => request()->fullUrl(),
				'description' => 'User Reject Production Item',
				'details'  =>  'Production Items Reject ' . $ref,
				'id_cms_users' => CRUDBooster::myId(),
				'created_at' => now(),
				'updated_at' => now(),
				]);

				DB::table('production_items_history')->insert([
				'reference' => $ref,
				'action' => 'Create', 
				'description' => 'User Reject Production Item',
				'key_old_value' => '', // .': '. $safe_value,
				'description_old_value' => '',	
				'key_new_value' => '',
				'description_new_value' => '',  
				'updated_by' => CRUDBooster::myId() ?: 1, 
				'details'  =>  'Production Items Reject ' . $ref, 
				'created_at' => now(),
				'updated_at' => now(),
				]);

				$approvalStatus = ProductionItemsModelApproval::updateOrCreate(
					['reference_number' => $data['reference_number']],
					['approval_status' => 400]                                 
				); 

				return redirect(CRUDBooster::mainpath())
				->with([
						'message_type' => 'success',
						'message' => 'Item Successfully Rejected',
				])->send();
			}
				 
		 
				$message = "✔️ Item Added successfully with reference number ". $ref;	 
				
				$data['created_by'] = CRUDBooster::myId();
				$data['updated_by'] = CRUDBooster::myId();
				$data['approval_status'] = 200;
				
			    //status 202=pending, 200=approve, 400=reject


					$input = $data;

					 $input['item_photo'] = DB::table('production_items_approvals') 
					->select('image_filename')
					->where('reference_number', '=', $ref)
					->pluck('image_filename')
    				->first();



					if ($input['item_photo']) $data['image_filename'] = $input['item_photo'];  


			$segment_columns = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->pluck('segment_column_name')
				->toArray();


			$segmentations = (array) json_decode($data['segmentations']);
			//segmentation => initializing all to 'X'
			foreach ($segment_columns as $segment_column) {
				$data[$segment_column] = 'X';
			}

			//overwriting the selected segmentations
			foreach ($segmentations as $value => $columns) {
				foreach ($columns as $column_name) {
					$data[$column_name] = $value; 
				} 
			}





				DB::table('cms_logs')->insert([
				'ipaddress' => request()->ip(),
				'useragent' => request()->userAgent(),
				'url' => request()->fullUrl(),
				'description' => 'User Approve Production Item',
				'details'  =>  'Production Items Approved ' . $ref,
				'id_cms_users' => CRUDBooster::myId(),
				'created_at' => now(),
				'updated_at' => now(),
				]);

				DB::table('production_items_history')->insert([
				'reference' => $ref,
				'action' => 'Create', 
				'description' => 'User Approve Production Item',
				'key_old_value' => '', // .': '. $safe_value,
				'description_old_value' => '',	
				'key_new_value' => '',
				'description_new_value' => '',  
				'updated_by' => CRUDBooster::myId() ?: 1, 
				'details'  =>  'Production Items Approved ' . $ref, 
				'created_at' => now(),
				'updated_at' => now(),
				]);

 
			
			$production_item_id = $data['reference_number'];
			$ingredients = $request->input('produtionlines');
			$labor_lines = $request->input('LaborLines');
			$new_id = 0;
			$labor_new_id = 0;
			// Flatten all new item_codes for later comparison
			   
			//mula dito

			 
			$newItemCodesID = []; 
			if (count($ingredients) > 0) {
				foreach ($ingredients as $parentCode => $ingredientGroup) {
					$gg = $new_id;
				  	$parent_id = $gg + 1;
					foreach ($ingredientGroup as $ingredient) {  
						$new_id++;
						$newItemCodesID[] = $new_id; 
						ProductionItemLines::updateOrCreate(
							[
								'production_item_id' => $production_item_id,
								'production_item_line_id' => $new_id,
							],
							[ 
								'production_item_id' => $production_item_id,
								'item_code' => $ingredient['tasteless_code'],	
								'description' => $ingredient['itemDesc'],
								'quantity' => $ingredient['quantity'],
								'yield' => $ingredient['yield'],
								'preparations' => $ingredient['preparations'], 
								'landed_cost' =>  $ingredient['ttp'] ?? $ingredient['cost'],
								'packaging_id' =>  $parent_id, 
								'production_item_line_id' => $new_id,
								'production_item_line_type' => $ingredient['production_type'],
								'approval_status' => 200,
							]
						);
					}
					$gg = 0;
				}
			} 
			//$(`#itemDesc${lastCharsub}`).attr('name', `produtionlines[${parentid}][${lastCharsub}][description]`); 
		

			ProductionItemLines::where('production_item_id', $production_item_id)
				->whereNotIn('production_item_line_id', $newItemCodesID) 
				->delete();
			//hanggadito 
			 
			if (count($labor_lines) > 0) {
				foreach ($labor_lines as $parentCode => $labor_lines_description) {
					
					$new_id++;
					$newItemCodesID[] = $new_id;
					ProductionItemLines::updateOrCreate(
						[
							'production_item_id' => $production_item_id,
							'production_item_line_id' => $new_id,
						],
						[ 
							'production_item_id' => $production_item_id, 
							'time_labor' => $labor_lines_description['time-labor'], 
							'yield' => $labor_lines_description['yiel'],
							'preparations' => $labor_lines_description['preparations'], 
							'production_item_line_id' => $new_id,
							'production_item_line_type' => $labor_lines_description['production_item_line_type'],
							'approval_status' => 202,
						]
					); 
				}
			}

			$data['final_value_existing'] = DB::table('production_items')
			->where('reference_number', $data['reference_number'])
			->value('final_value_vatex') ?? $data['final_value_vatex'];
				 
 

			//loop each ingredients and save sa DB 	production_item_lines table
			// dd($ingredients); 
				$approvalStatus = ProductionItemsModelApproval::updateOrCreate(
					['reference_number' => $data['reference_number']],
					['approval_status' => 200,
					'approved_by' => CRUDBooster::myId(),
					'approved_at' => now()]                                 
				);

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

}
