<?php namespace App\Http\Controllers\ProductionItems;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\ProductionItems\ProductionItemCategory;
	use App\Models\ProductionItems\ProductionItemStorageLocation;
	use App\Models\ProductionItems\ProductionLocation;
	use App\ItemMaster;
use App\Models\ProductionItems\ProductionItemLines;
use App\Models\ProductionItems\ProductionItems;

	class AdminProductionItemsController extends \crocodicstudio\crudbooster\controllers\CBController {
		static $requestor = [1];
		static $approver = [1];
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
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
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


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

 



		public function addProductionItemsToDB(Request $request){
			$message = '';
			$time_stamp_now = date('Y-m-d H:i:s');
			 if($request['id']){
				/*
				$message = "✔️ Item updated successfully...";
				$productlocation = ProductionLocation::findOrFail($request['id']);
				$time_stamp = $time_stamp_now;
				$productlocation->production_location_description = $request['production_location_description'];
				$productlocation->status = 'ACTIVE'; 
				$productlocation->updated_by = CRUDBooster::myId(); 
				$productlocation->updated_at = $time_stamp;
				*/
				 

				$message = "✔️ Item updated successfully...";
				$production_items_toDB =  ProductionItems::findOrFail($request['id']);
				$production_items_toDB->fill($request->all());
				$production_items_toDB->ingredients = json_encode($request->only(['ingredients']));
				$production_items_toDB->updated_at = $time_stamp_now;
				$production_items_toDB->updated_by = CRUDBooster::myId();


		 
			}
			
			 else

			{ 
				$message = "✔️ Item Added successfully...";
				$validated = $request->validate([ 
					'description' => 'nullable|string',
					'production_category' => 'nullable|integer',
					'production_location' => 'nullable|integer',
					'packaging_id' => 'nullable|integer',
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
				$data['reference_number'] = rand();
				$data['created_by'] = CRUDBooster::myId();
				$data['updated_by'] = CRUDBooster::myId();
 
			 
				$production_items_toDB = new ProductionItems();
				
				$production_items_toDB->reference_number = $data['reference_number'];
				$production_items_toDB->description = $data['description'];
				$production_items_toDB->production_category =$data['production_category']; 
				$production_items_toDB->production_location = $data['production_location'];
				$production_items_toDB->packaging_id = $data['packaging_id'];
				$production_items_toDB->labor_cost = $data['labor_cost'];
				$production_items_toDB->gas_cost = $data['gas_cost'];
				$production_items_toDB->storage_cost = $data['storage_cost'];
				$production_items_toDB->storage_multiplier = $data['storage_multiplier'];
				$production_items_toDB->total_storage_cost = $data['total_storage_cost'];
				$production_items_toDB->storage_location = $data['storage_location'];
				$production_items_toDB->depreciation = $data['depreciation'];
				$production_items_toDB->raw_mast_provision = $data['raw_mast_provision'];
				$production_items_toDB->markup_percentage = $data['markup_percentage'];
				$production_items_toDB->final_value_vatex = $data['final_value_vatex'];
				$production_items_toDB->final_value_vatinc = $data['final_value_vatinc'];
				$production_items_toDB->created_by = $data['created_by'];
				$production_items_toDB->updated_by = $data['updated_by'];
			}

				$ingredients = $request->input('ingredients'); // get the array
				
				//loop each ingredients and save sa DB
				foreach ($ingredients as $ingredient) {
					self::ingredientSearchToItemMaster($ingredient['description'], $production_items_toDB->reference_number, $ingredient['quantity']);
				}



				$production_items_toDB->save();

			 //return redirect()->back()->with('success', 'Production item saved successfully!');
			 return redirect(CRUDBooster::mainpath())
				->with([
						'message_type' => 'success',
						'message' => $message,
				])->send();
			
		}

		public function ingredientSearchToItemMaster($description, $reference_number, $quantity)
		{ 
			$item = DB::table('item_masters')
				->where('full_item_description', $description)
				->first();

			if (!$item) { 
				return null;
			}

			$production_items_toDB = new ProductionItemLines();

			$production_items_toDB->production_item_id = $reference_number;
			$production_items_toDB->item_code = $item->tasteless_code;  
			$production_items_toDB->description = $description;
			$production_items_toDB->quantity = $quantity;
			$production_items_toDB->landed_cost = $item->landed_cost;
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
				self::ingredientsSearch($id);
				$data = []; 
				/*
				$data['production_category'] = ProductionItemCategory::active();
				$data['storage_location'] = ProductionItemStorageLocation::active();
				$data['production_location'] = ProductionLocation::active();
				*/


			if ($id) { 
				$data['item'] = self::getItemDetails($id);
				/*
				if ($data['item']->approval_status == 202) {
					return redirect(CRUDBooster::mainpath())->with([
						'message_type' => 'danger',
						'message' => '✖️ You cannot edit a pending item...',
					]);
				}
			 	*/  
			} 
			
				$costings = self::costing();

				
			 	$data = array_merge($data, $costings);
			 
	 
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
				self::ingredientsSearch($id);
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
			 
			
				$costings = self::costing();

				
			 	$data = array_merge($data, $costings);
			 
	 
				return $this->view('production-items/detail-production-item',   $data); 
	}


	


	public function ingredientsSearch($id)
	{
			$item = DB::table('production_items')
				->where('id', $id)
				->get()
				->first();
				

			return response()->json([
 				'ingredients' => $item->ingredients
			]);
	}

	public function getItemDetails($id) {
			$item = DB::table('production_items')
				->where('id', $id)
				->get()
				->first();

			return $item;
		}


	public function costing() {

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
				
 

			return $data;
		}







		public function itemSearch(Request $request){
			$searchTerm = $request->input('search');

			if (!$searchTerm) {
				return response()->json([
					'status_no' => 0,
					'message' => 'No search term provided.',
					'items' => null
				]);
			}

			$items = ItemMaster::where('full_item_description', 'LIKE', '%' . $searchTerm . '%')
				->orWhere('tasteless_code', 'LIKE', '%' . $searchTerm . '%')
				->take(100)
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
					'cost' => $item->landed_cost
				];
			});

			return response()->json([
				'status_no' => 1,
				'items' => $formattedItems
			]);
		}


	}