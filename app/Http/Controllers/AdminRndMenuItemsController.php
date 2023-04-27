<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Request as Input;

	class AdminRndMenuItemsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {
	    	# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->table 			   = "rnd_menu_items";	        
			$this->title_field         = "id";
			$this->limit               = 20;
			$this->orderby             = "id,desc";
			$this->show_numbering      = FALSE;
			$this->global_privilege    = FALSE;	        
			$this->button_table_action = TRUE;   
			$this->button_action_style = "button_icon";     
			$this->button_add          = TRUE;
			$this->button_delete       = TRUE;
			$this->button_edit         = TRUE;
			$this->button_detail       = TRUE;
			$this->button_show         = TRUE;
			$this->button_filter       = TRUE;        
			$this->button_export       = FALSE;	        
			$this->button_import       = FALSE;
			$this->button_bulk_action  = TRUE;	
			$this->sidebar_mode		   = "normal"; //normal,mini,collapse,collapse-mini
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Approval Status","name"=>"id","join"=>"rnd_menu_approvals,approval_status","join_id"=>"rnd_menu_items_id"];
			$this->col[] = ["label"=>"Rnd Code","name"=>"rnd_code"];
			$this->col[] = ["label"=>"Rnd Tasteless Code","name"=>"id","join"=>"menu_items,tasteless_menu_code"];
			$this->col[] = ["label"=>"Rnd Menu Description","name"=>"rnd_menu_description"];
			$this->col[] = ["label"=>"SRP","name"=>"rnd_menu_srp"];
			$this->col[] = ["label"=>"Portion Size","name"=>"portion_size"];
			$this->col[] = ["label"=>"Food Cost","name"=>"id","join"=>"rnd_menu_computed_food_cost,computed_food_cost","join_id"=>"id"];
			$this->col[] = ["label"=>"Food Cost Percentage","name"=>"id","join"=>"rnd_menu_computed_food_cost,computed_food_cost_percentage","join_id"=>"id"];
			$this->col[] = ["label"=>"Packaging Cost","name"=>"id","join"=>"rnd_menu_computed_packaging_cost,computed_packaging_total_cost","join_id"=>"id"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated At","name"=>"updated_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Rnd Menu Description','name'=>'rnd_menu_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Rnd Code","name"=>"rnd_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Rnd Menu Description","name"=>"rnd_menu_description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Rnd Tasteless Code","name"=>"rnd_tasteless_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


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

	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



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
	        $this->load_css = [
				asset('css/custom.css')
			];
	        
	        
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
	        
			$query->whereIn('rnd_menu_approvals.approval_status', ['SAVED', 'REJECTED']);
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here

			if (is_numeric($column_value)) $column_value = (float) $column_value;
			if ($column_index == 2) {
				if ($column_value == 'SAVED') $column_value = "<span class='label label-info'>$column_value</span>";
				if ($column_value == 'PENDING') $column_value = "<span class='label label-warning'>$column_value</span>";
				if ($column_value == 'REJECTED') $column_value = "<span class='label label-danger'>$column_value</span>";
			}
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
			DB::table('rnd_menu_items')->where('id', $id)->update(['status' => 'INACTIVE']);

	    }



	    //By the way, you can still create your own method in here... :) 


		//for chef
		public function getDetail($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];

			$data['item'] = DB::table('rnd_menu_items')
				->where('rnd_menu_items.id', $id)
				->select(
					'rnd_code',
					'rnd_menu_items.rnd_menu_srp',
					'rnd_menu_items.rnd_menu_description',
					'rnd_menu_items.portion_size',
					'computed_ingredient_total_cost',
					'computed_food_cost',
					'computed_food_cost_percentage',
					'computed_packaging_total_cost'
				)
				->leftJoin('rnd_menu_computed_food_cost', 'rnd_menu_computed_food_cost.id', '=', 'rnd_menu_items.id')
				->leftJoin('rnd_menu_computed_packaging_cost', 'rnd_menu_computed_packaging_cost.id', '=', 'rnd_menu_items.id')
				->first();

			$ingredients = DB::table('rnd_menu_ingredients_auto_compute')
			->where('rnd_menu_items_id', $id)
			->where('rnd_menu_ingredients_auto_compute.status', 'ACTIVE')
			->select('tasteless_code',
				'menu_items.status as menu_item_status',
				'sku_statuses.sku_status_description as item_status',
				'item_masters_id',
				'rnd_menu_ingredients_auto_compute.menu_item_description',
				'tasteless_menu_code',
				'ingredient_name',
				'prep_qty',
				'ingredient_group',
				'row_id',
				'is_primary',
				'is_selected',
				'rnd_menu_ingredients_auto_compute.packaging_size',
				'rnd_menu_ingredients_auto_compute.full_item_description',
				'menu_ingredients_preparations.preparation_desc',
				'ingredient_qty',
				'rnd_menu_ingredients_auto_compute.uom_description',
				'yield',
				'rnd_menu_ingredients_auto_compute.ttp',
				'cost',
				'item_masters.updated_at',
				'item_masters.created_at')
			->leftJoin('item_masters', 'rnd_menu_ingredients_auto_compute.item_masters_id', '=', 'item_masters.id')
			->leftJoin('menu_items', 'rnd_menu_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
			->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
			->leftJoin('menu_ingredients_preparations', 'rnd_menu_ingredients_auto_compute.menu_ingredients_preparations_id', '=', 'menu_ingredients_preparations.id')
			->orderby('ingredient_group', 'asc')
			->orderby('row_id', 'asc')
			->get()
			->toArray();
			
			$packagings = DB::table('rnd_menu_packagings_auto_compute')
				->where('rnd_menu_items_id', $id)
				->where('rnd_menu_packagings_auto_compute.status', 'ACTIVE')
				->select('tasteless_code',
				'sku_statuses.sku_status_description as item_status',
				'item_masters_id',
				'packaging_name',
				'prep_qty',
				'packaging_group',
				'row_id',
				'is_primary',
				'is_selected',
				'rnd_menu_packagings_auto_compute.packaging_size',
				'rnd_menu_packagings_auto_compute.full_item_description',
				'menu_ingredients_preparations.preparation_desc',
				'packaging_qty',
				'rnd_menu_packagings_auto_compute.uom_description',
				'yield',
				'rnd_menu_packagings_auto_compute.ttp',
				'cost',
				'item_masters.updated_at',
				'item_masters.created_at')
			->leftJoin('item_masters', 'rnd_menu_packagings_auto_compute.item_masters_id', '=', 'item_masters.id')
			->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
			->leftJoin('menu_ingredients_preparations', 'rnd_menu_packagings_auto_compute.menu_ingredients_preparations_id', '=', 'menu_ingredients_preparations.id')
			->orderby('packaging_group', 'asc')
			->orderby('row_id', 'asc')
			->get()
			->toArray();
			
			$rnd_menu_description = $data['item']->rnd_menu_description;
			$data['ingredients'] = array_map(fn ($object) => (object) array_filter((array) $object), $ingredients);
			$data['packagings'] = array_map(fn ($object) => (object) array_filter((array) $object), $packagings);
			$data['page_title'] = "Detail RND Menu Item: $rnd_menu_description";
			return $this->view('rnd-menu/detail-item', $data);

		}
		
		public function getAdd() {
			if (!CRUDBooster::isCreate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			return self::getEdit(null, 'add');
		}

		public function getEdit($id, $action = 'edit') {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];

			$data['action'] = $action;

			$data['item'] = DB::table('rnd_menu_items')
				->where('id', $id)
				->first();

			$data['preparations'] = DB::table('menu_ingredients_preparations')
				->where('status', 'ACTIVE')
				->select('id', 'preparation_desc')
				->orderBy('preparation_desc', 'ASC')
				->get()
				->toArray();

			$data['uoms'] = DB::table('uoms')
				->where('status', 'ACTIVE')
				->select('id', 'uom_description')
				->orderBy('uom_description')
				->get()
				->toArray();

			$data['privilege'] = CRUDBooster::myPrivilegeName();

			if ($id) {
				$data['ingredients'] = DB::table('rnd_menu_ingredients_auto_compute')
					->where('rnd_menu_items_id', $id)
					->where('rnd_menu_ingredients_auto_compute.status', 'ACTIVE')
					->select(\DB::raw('item_masters.id as item_masters_id'),
						'ingredient_name',
						'menu_as_ingredient_id',
						'rnd_menu_ingredients_auto_compute.menu_item_description',
						'is_selected',
						'is_primary',
						'is_existing',
						'rnd_menu_ingredients_auto_compute.packaging_size',
						'ingredient_qty',
						'cost',
						'menu_items.food_cost',
						'ingredient_group',
						'uom_id',
						'uom_description',
						'packaging_description',
						'prep_qty',
						'menu_ingredients_preparations_id',
						'yield',
						'rnd_menu_ingredients_auto_compute.ttp',
						'rnd_menu_ingredients_auto_compute.ttp as ingredient_cost',
						'item_masters.full_item_description',
						'sku_status_description as item_status',
						'menu_items.status as menu_status',
						'item_masters.updated_at',
						'item_masters.created_at',
						'rnd_menu_ingredients_auto_compute.item_masters_temp_id')
					->leftJoin('item_masters', 'item_masters.id', '=', 'rnd_menu_ingredients_auto_compute.item_masters_id')
					->leftJoin('menu_items', 'rnd_menu_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
					->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
					->leftJoin('item_masters_temp', 'item_masters_temp.id', 'rnd_menu_ingredients_auto_compute.item_masters_temp_id')
					->orderBy('ingredient_group', 'ASC')
					->orderBy('row_id', 'ASC')
					->get()
					->toArray();
	
				$data['packagings'] = DB::table('rnd_menu_packagings_auto_compute')
					->where('rnd_menu_items_id', $id)
					->where('rnd_menu_packagings_auto_compute.status', 'ACTIVE')
					->select(\DB::raw('item_masters.id as item_masters_id'),
						'packaging_name',
						'is_selected',
						'is_primary',
						'is_existing',
						'rnd_menu_packagings_auto_compute.packaging_size',
						'packaging_qty',
						'cost',
						'packaging_group',
						'uom_id',
						'uom_description',
						'packaging_description',
						'prep_qty',
						'menu_ingredients_preparations_id',
						'yield',
						'rnd_menu_packagings_auto_compute.ttp',
						'rnd_menu_packagings_auto_compute.ttp as packaging_cost',
						'item_masters.full_item_description',
						'sku_status_description as item_status',
						'item_masters.updated_at',
						'item_masters.created_at',
						'rnd_menu_packagings_auto_compute.item_masters_temp_id')
					->leftJoin('item_masters', 'item_masters.id', '=', 'rnd_menu_packagings_auto_compute.item_masters_id')
					->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
					->orderBy('packaging_group', 'ASC')
					->orderBy('row_id', 'ASC')
					->get()
					->toArray();
			}

			
			if ($action == 'add-packaging') {
				return $this->view('rnd-menu/add-packaging', $data);
			}

			return $this->view('rnd-menu/add-item', $data);
		}

		public function editRNDMenu(Request $request, $action = 'save') {

			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$rnd_menu_description = $request->get('rnd_menu_description');
			$food_cost = $request->get('food_cost');
			$food_cost_percentage = $request->get('food_cost_percentage');
			$rnd_menu_srp = $request->get('rnd_menu_srp');
			$portion_size = $request->get('portion_size');
			$ingredient_total_cost = $request->get('ingredient_total_cost');
			$ingredients = json_decode($request->get('ingredients'));
			$packagings = json_decode($request->get('packagings'));
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$rnd_menu_approval_status = 'SAVED';
			$max_rnd_code = DB::table('rnd_menu_items')->max('rnd_code');
			$rnd_code_int = (int) explode('-', $max_rnd_code)[1] + 1;
			$rnd_code = 'RND-' . str_pad($rnd_code_int, 5, '0', STR_PAD_LEFT);

			if (!$rnd_menu_items_id) {
				// inserting new rnd menu item and getting the id
				$rnd_menu_items_id = DB::table('rnd_menu_items')
					->insertGetId([
						'rnd_menu_description' => $rnd_menu_description,
						'rnd_code' => $rnd_code,
						'portion_size' => $portion_size,
						'rnd_menu_srp' => $rnd_menu_srp,
						'created_by' => $action_by,
						'created_at' => $time_stamp
					]);
			} else {
				//update details for rnd menu item
				DB::table('rnd_menu_items')
					->where('id', $rnd_menu_items_id)
					->update([
						'rnd_menu_description' => $rnd_menu_description,
						'rnd_menu_srp' => $rnd_menu_srp,
						'portion_size' => $portion_size,
						'updated_at' => $time_stamp,
						'updated_by' => $action_by
					]);
			}

			//inactivating all active ingredients of rnd menu item
			DB::table('rnd_menu_ingredients_details')
				->where('status', 'ACTIVE')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'status' => 'INACTIVE',
					'row_id' => null,
					'deleted_at' => $time_stamp
				]);

			//inactivating all active packagings of rnd menu item
			DB::table('rnd_menu_packagings_details')
				->where('status', 'ACTIVE')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'status' => 'INACTIVE',
					'row_id' => null,
					'deleted_at' => $time_stamp
				]);

			//looping through the nested ingredients by their ingredient_group
			foreach ($ingredients as $group) {
				foreach ($group as $ingredient) {
					$ingredient = (array) $ingredient;

					//checking if the ingredient already exists
					$is_existing = DB::table('rnd_menu_ingredients_details')
						->where([
							'rnd_menu_items_id' => $rnd_menu_items_id,
							'item_masters_id' => $ingredient['item_masters_id'],
							'ingredient_name' => $ingredient['ingredient_name'],
							'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id']
						])->exists();
					
					if ($is_existing) {
						$ingredient['updated_at'] = $time_stamp;
						$ingredient['updated_by'] = $action_by;
					} else {
						$ingredient['created_at'] = $time_stamp;
						$ingredient['created_by'] = $action_by;
					}
					
					$ingredient['status'] = 'ACTIVE';
					$ingredient['deleted_at'] = null;

					// inserting item to item_masters_temp if new ingredient
					if ($ingredient['is_existing'] != 'TRUE' && !$ingredient['item_masters_temp_id']) {
						$inserted_item_id = DB::table('item_masters_temp')->insertGetId([
							'item_description' => $ingredient['ingredient_name'],
							'packaging_size' => $ingredient['packaging_size'],
							'uoms_id' => $ingredient['uom_id'],
							'ttp' => $ingredient['ttp'],
							'created_by' => $action_by,
							'created_at' => $time_stamp,
						]);

						$ingredient['item_masters_temp_id'] = $inserted_item_id;
						unset($ingredient['ttp']);
					}

					//unsetting ingredients details that may be outdated in the future
					unset(
						$ingredient['qty'], 
						$ingredient['cost'], 
						$ingredient['total_cost'],
						$ingredient['ttp']
					);

					//finally, inserting ingredients to the table
					DB::table('rnd_menu_ingredients_details')->updateOrInsert([
						'rnd_menu_items_id' => $rnd_menu_items_id,
						'item_masters_id' => $ingredient['item_masters_id'],
						'ingredient_name' => $ingredient['ingredient_name'],
						'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id'],
						'item_masters_temp_id' => $ingredient['item_masters_temp_id'],
					], $ingredient);
				}
			}

			//looping through nested packagings
			foreach ($packagings as $group) {
				foreach ($group as $packaging) {
					$packaging = (array) $packaging;

					//checking if the packaging already exists
					$is_existing = DB::table('rnd_menu_packagings_details')
						->where([
							'rnd_menu_items_id' => $rnd_menu_items_id,
							'item_masters_id' => $packaging['item_masters_id'],
							'packaging_name' => $packaging['packaging_name'],
						])->exists();

					if ($is_existing) {
						$packaging['updated_at'] = $time_stamp;
						$packaging['updated_by'] = $action_by;
					} else {
						$packaging['created_at'] = $time_stamp;
						$packaging['created_by'] = $action_by;
					}

					$packaging['status'] = 'ACTIVE';
					$packaging['deleted_at'] = null;

					// inserting item to item_masters_temp if new packaging
					if ($packaging['is_existing'] != 'TRUE' && !$packaging['item_masters_temp_id']) {
						$inserted_item_id = DB::table('item_masters_temp')->insertGetId([
							'item_description' => $packaging['packaging_name'],
							'packaging_size' => $packaging['packaging_size'],
							'uoms_id' => $packaging['uom_id'],
							'ttp' => $packaging['ttp'],
							'created_by' => $action_by,
							'created_at' => $time_stamp,
						]);

						$packaging['item_masters_temp_id'] = $inserted_item_id;
						
					}

					//unsetting packagings details that may be outdated in the future
					unset(
						$packaging['qty'], 
						$packaging['cost'], 
						$packaging['total_cost'],
						$packaging['ttp']
					);

					//finally, inserting packaging to the table
					DB::table('rnd_menu_packagings_details')->updateOrInsert([
						'rnd_menu_items_id' => $rnd_menu_items_id,
						'item_masters_id' => $packaging['item_masters_id'],
						'packaging_name' => $packaging['packaging_name'],
						'item_masters_temp_id' => $packaging['item_masters_temp_id']
					], $packaging);
						
				}
			}
			
			//updating approval status
			DB::table('rnd_menu_approvals')
				->updateOrInsert(['rnd_menu_items_id' => $rnd_menu_items_id],[
					'rnd_menu_items_id' => $rnd_menu_items_id,
					'approval_status' => $rnd_menu_approval_status,
					'created_at' => $time_stamp,
				]);
			
			if ($action == 'publish') {
				return $rnd_menu_items_id;
			}

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ RND Menu Item Details of $rnd_menu_description Updated!"
				]);
		}

		public function publishRNDMenu(Request $request) {
			$rnd_menu_approval_status = 'FOR PACKAGING';
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$rnd_menu_description = $request->get('rnd_menu_description');
			$rnd_menu_items_id = self::editRNDMenu($request, 'publish');
			
			DB::table('rnd_menu_approvals')
				->updateOrInsert(['rnd_menu_items_id' => $rnd_menu_items_id],[
					'rnd_menu_items_id' => $rnd_menu_items_id,
					'approval_status' => $rnd_menu_approval_status,
					'published_by' => $action_by,
					'published_at' => $time_stamp,
					'updated_at' => $time_stamp,
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ $rnd_menu_description forwarded to Marketing for Menu Creation!"
				]);
		}

		// for marketing
		public function getSetPackaging($id) {

			return self::getEdit($id, 'add-packaging');
		}

		public function addPackaging(Request $request) {
			$approval_status = 'FOR MENU CREATION';
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$packagings = json_decode($request->packagings);
			$rnd_menu_description = $request->rnd_menu_description;
			$rnd_menu_items_id = $request->rnd_menu_items_id;
			$rnd_menu_srp = $request->rnd_menu_srp;

			//updating rnd menu details
			DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->update([
					'rnd_menu_description' => $rnd_menu_description,
					'rnd_menu_srp' => $rnd_menu_srp,
					'updated_by' => $action_by,
					'updated_at' => $time_stamp
				]);

			//inactivating all active packagings
			DB::table('rnd_menu_packagings_details')
				->where('status', 'ACTIVE')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update(['status' => 'INACTIVE']);

			//looping through nested packagings
			foreach ($packagings as $group) {
				foreach ($group as $packaging) {
					$packaging = (array) $packaging;

					//checking if the packaging already exists
					$is_existing = DB::table('rnd_menu_packagings_details')
						->where([
							'rnd_menu_items_id' => $rnd_menu_items_id,
							'item_masters_id' => $packaging['item_masters_id'],
							'packaging_name' => $packaging['packaging_name'],
						])->exists();

					if ($is_existing) {
						$packaging['updated_at'] = $time_stamp;
						$packaging['updated_by'] = $action_by;
					} else {
						$packaging['created_at'] = $time_stamp;
						$packaging['created_by'] = $action_by;
					}

					$packaging['status'] = 'ACTIVE';
					$packaging['deleted_at'] = null;

					// inserting item to item_masters_temp if new packaging
					if ($packaging['is_existing'] != 'TRUE' && !$packaging['item_masters_temp_id']) {
						$inserted_item_id = DB::table('item_masters_temp')->insertGetId([
							'item_description' => $packaging['packaging_name'],
							'packaging_size' => $packaging['packaging_size'],
							'uoms_id' => $packaging['uom_id'],
							'ttp' => $packaging['ttp'],
							'created_by' => $action_by,
							'created_at' => $time_stamp,
						]);

						$packaging['item_masters_temp_id'] = $inserted_item_id;
						
					}

					//unsetting packagings details that may be outdated in the future
					unset(
						$packaging['qty'], 
						$packaging['cost'], 
						$packaging['total_cost'],
						$packaging['ttp']
					);

					//finally, inserting packaging to the table
					DB::table('rnd_menu_packagings_details')->updateOrInsert([
						'rnd_menu_items_id' => $rnd_menu_items_id,
						'item_masters_id' => $packaging['item_masters_id'],
						'packaging_name' => $packaging['packaging_name'],
						'item_masters_temp_id' => $packaging['item_masters_temp_id']
					], $packaging);
						
				}
			}

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'approval_status' => $approval_status,
					'packaging_updated_by' => $action_by,
					'packaging_updated_at' => $time_stamp
				]);
			return true;
		}

		public function getCreateNewMenu($id) {

			$item = DB::table('rnd_menu_items')
				->where('rnd_menu_items.id', $id)
				->select(
					'rnd_menu_items.id as rnd_menu_items_id',
					'rnd_menu_items.rnd_menu_description',
					'rnd_code',
					'rnd_menu_items.portion_size',
					'rnd_menu_items.rnd_menu_srp',
					'approval_status',
					'computed_ingredient_total_cost',
					'computed_food_cost',
					'computed_food_cost_percentage',
					'publisher.name as published_by',
					'published_at'
				)
				->leftJoin('rnd_menu_approvals', 'rnd_menu_items.id', '=', 'rnd_menu_approvals.rnd_menu_items_id')
				->leftJoin('rnd_menu_computed_food_cost', 'rnd_menu_items.id', '=', 'rnd_menu_computed_food_cost.id')
				->leftJoin('cms_users as publisher', 'rnd_menu_approvals.published_by', '=', 'publisher.id')
				->first();

			return (new AdminAddMenuItemsController)->getAdd('rnd_menu_items', $item);
		}

		public function saveNewMenu(Request $request) {
			$returnInputs = Input::all();
			$rnd_menu_items_id = $returnInputs['rnd_menu_items_id'];
			$approval_status = 'FOR COSTING';
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');
			$rnd_menu_srp = $returnInputs['price_dine_in'];
			$rnd_menu_description = $returnInputs['menu_item_description'];

			//------> START CODE FROM PAT'S CONTROLLER
			// Tasteless menu code
			$promo_id = DB::table('menu_types')
				->select('id')
				->where('status', 'ACTIVE')
				->where('menu_type_description', 'PROMO')->value('id');

			if($returnInputs['menu_type'] == $promo_id){
				$tasteless_menu_code = (int) DB::table('menu_items')->where('tasteless_menu_code','like',"5%")
				->select('tasteless_menu_code')
				->max('tasteless_menu_code');
			}else{
				$tasteless_menu_code = (int) DB::table('menu_items')->where('tasteless_menu_code','like',"6%")
				->select('tasteless_menu_code')
				->max('tasteless_menu_code');
			}

			// Price Delivery
			if($returnInputs['price_delivery'] == null){
				$price_delivery = $returnInputs['price_dine_in'];
			}else{
				$price_delivery = $returnInputs['price_delivery'];
			}

			if($returnInputs['price_take_out'] == null){
				$price_take_out = $returnInputs['price_dine_in'];
			}else{
				$price_take_out = $returnInputs['price_take_out'];
			}
			
			$choices_group = DB::table('menu_choice_groups')
				->select('id')
				->where('status', 'ACTIVE')
				->get();

			// Add data to database
			$data['tasteless_menu_code'] = $tasteless_menu_code+1;
			$data['old_code_1'] = $returnInputs['pos_item_code_1'];
			$data['old_code_2'] = $returnInputs['pos_item_code_2'];
			$data['old_code_3'] = $returnInputs['pos_item_code_3'];
			$data['menu_item_description'] = $returnInputs['menu_item_description'];
			for($i=0; $i<count($choices_group); $i++){
				$choices_group_str = 'choices_group_'.(string)($i+1);
				$choices_skugroup_str = 'choices_skugroup_'.(string)($i+1);
				$data[$choices_group_str] = $returnInputs[$choices_group_str];
				if($returnInputs[$choices_skugroup_str] != null){
					$data[$choices_skugroup_str] = implode(', ',$returnInputs[$choices_skugroup_str]);
				}
			}
			$data['menu_types_id'] = $returnInputs['menu_type'];
			$data['menu_price_dine'] = $returnInputs['price_dine_in'];
			$data['menu_price_dlv'] = $price_delivery;
			$data['menu_price_take'] = $price_take_out;
			$data['original_concept'] = $returnInputs['original_concept'];
			$data['pos_old_item_description'] = $returnInputs['pos_item_description'];
			$data['menu_product_types_name'] = $returnInputs['product_type'];
			$data['menu_categories_id'] = $returnInputs['menu_categories'];
			$data['menu_subcategories_id'] = $returnInputs['sub_category'];
			$data['status'] = $returnInputs['status'];
			$data['created_by'] = CRUDBooster::myid();
			$data['created_at'] = date('Y-m-d H:i:s');
			// Get store list column name
			if($returnInputs['menu_segment_column_description'] != null){
				foreach($returnInputs['menu_segment_column_description'] as $menu_segments_id){
					$menu_segmentations_column_name = DB::table('menu_segmentations')
						->where('id', $menu_segments_id)
						->select('menu_segment_column_name')
						->value('menu_segment_column_name');
					$data[$menu_segmentations_column_name] = 1;
				}
			}
			//------> END CODE FROM PAT'S CONTROLLER

			$inserted_id = DB::table('menu_items')
				->insertGetId($data);

			// updating the details of rnd menu in db
			DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->update([
					'menu_items_id' => $inserted_id,
					'rnd_menu_description' => $rnd_menu_description,
					'rnd_menu_srp' => $rnd_menu_srp,
					'updated_by' => $action_by,
					'updated_at' => $time_stamp,
				]);

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'approval_status' => $approval_status,
					'updated_at' => $time_stamp,
					'menu_created_by' => $action_by,
					'menu_created_at' => $time_stamp
				]);

				return true;

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ New menu: $rnd_menu_description created."
				]);
		}

		public function getSetCosting($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->first();

			$data['workflow'] = self::getWorkFlowDetails($id);

			$data['menu_items_data'] = self::getMenuItemDetails($data['item']->menu_items_id);

			$data['comments_data'] = self::getRNDComments($id);


			return $this->view('rnd-menu/add-costing', $data);
		}

		public function submitCosting(Request $request) {
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$menu_items_id = $request->get('menu_items_id');
			$rnd_menu_data = (array) json_decode($request->get('rnd_menu_data'));
			$menu_item_data  = (array) json_decode($request->get('menu_item_data'));
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$approval_status = 'FOR APPROVAL (MARKETING)';

			$rnd_menu_data['updated_at'] = $time_stamp;
			$rnd_menu_data['updated_by'] = $action_by;

			DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->update($rnd_menu_data);

			DB::table('menu_items')
				->where('id', $menu_items_id)
				->update($menu_item_data);

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'approval_status' => $approval_status,
					'costing_updated_at' => $time_stamp,
					'costing_updated_by' => $action_by,
					'updated_at' => $time_stamp,
				]);
			

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ Costing Updated!"
				]);
		}

		public function getDetailMarketing($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_costing.rnd_menu_items_id', $id)
				->leftJoin('rnd_menu_approvals', 'rnd_menu_approvals.rnd_menu_items_id', '=', 'rnd_menu_costing.rnd_menu_items_id')
				->first();

			$data['page_title'] = 'Details: ' . $data['item']->rnd_menu_description;

			return $this->view('rnd-menu/hide-ingredients', $data);
		}

		public function getDetailMarketingApprover($id) {
			return self::getDetail($id);
		}

		public function getApproveByMarketing($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->leftJoin('rnd_menu_items', 'rnd_menu_items.id', '=', 'rnd_menu_costing.rnd_menu_items_id')
				->first();

			$data['page_title'] = 'For Approval (Marketing): ' . $data['item']->rnd_menu_description;

			return $this->view('rnd-menu/approve-item', $data);
		}

		public function approveByMarketing(Request $request) {
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$action = $request->get('action');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$approval_status = null;

			if ($action == 'approve') {
				$approval_status = 'FOR ITEM CREATION';
				$db_column_at = 'marketing_approved_at';
				$db_column_by = 'marketing_approved_by';
				$message = '✔️ Item Approved!';
			} else {
				$approval_status = 'REJECTED';
				$db_column_at = 'rejected_at';
				$db_column_by = 'rejected_by';
				$message = '✖️ Item Rejected!';
				self::notifyForRejection($rnd_menu_items_id);
			}

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'approval_status' => $approval_status,
					$db_column_by => $action_by,
					$db_column_at => $time_stamp,
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => $message,
				]);

		}

		// for purchasing
		public function getDetailPurchasing($id) {
			return self::getDetail($id);
		}

		public function getEditByPurchasing($id) {

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->leftJoin('rnd_menu_items', 'rnd_menu_items.id', '=', 'rnd_menu_costing.rnd_menu_items_id')
				->first();

			$data['ingredients'] = DB::table('rnd_menu_ingredients_auto_compute')
				->where('rnd_menu_items_id', $id)
				->where('status', 'ACTIVE')
				->where('is_existing', 'FALSE')
				->get()
				->toArray();

			$data['packagings'] = DB::table('rnd_menu_packagings_auto_compute')
				->where('rnd_menu_items_id', $id)
				->where('status', 'ACTIVE')
				->where('is_existing', 'FALSE')
				->get()
				->toArray();
			
			return $this->view('rnd-menu/add-tasteless-code', $data);
		}

		public function editByPurchasing(Request $request) {
			
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$rnd_menu_description = $request->get('rnd_menu_description');
			$ingredients = json_decode($request->get('ingredients'));
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();

			// inactivating all ingredients
			DB::table('rnd_menu_ingredients_details')
				->where('status', 'ACTIVE')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'status' => 'INACTIVE',
					'row_id' => null,
					'deleted_at' => date('Y-m-d H:i:s')
				]);

			foreach ($ingredients as $group) {
				foreach ($group as $ingredient) {
					$ingredient = (array) $ingredient;

					//checking if the ingredient already exists
					$is_existing = DB::table('rnd_menu_ingredients_details')
						->where([
							'rnd_menu_items_id' => $rnd_menu_items_id,
							'item_masters_id' => $ingredient['item_masters_id'],
							'ingredient_name' => $ingredient['ingredient_name'],
							'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id']
						])->exists();
					
					if ($is_existing) {
						$ingredient['updated_at'] = $time_stamp;
						$ingredient['updated_by'] = $action_by;
					} else {
						$ingredient['created_at'] = $time_stamp;
						$ingredient['created_by'] = $action_by;
					}
					
					$ingredient['status'] = 'ACTIVE';
					$ingredient['deleted_at'] = null;

					//unsetting ingredients details that may be outdated in the future
					unset(
						$ingredient['qty'], 
						$ingredient['cost'], 
						$ingredient['total_cost'], 
					);

					if ($ingredient['is_existing'] == 'TRUE') {
						unset($ingredient['ttp']);
					}

					//finally, inserting ingredients to the table
					DB::table('rnd_menu_ingredients_details')->updateOrInsert([
						'rnd_menu_items_id' => $rnd_menu_items_id,
						'item_masters_id' => $ingredient['item_masters_id'],
						'ingredient_name' => $ingredient['ingredient_name'],
						'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id']
					], $ingredient);
				}
			}

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ RND Menu Item Details of $rnd_menu_description Updated!"
				]);
		}

		public function submitByPurchasing(Request $request) {

			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$approval_status = 'FOR APPROVAL (ACCOUNTING)';
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();

			self::editByPurchasing($request);

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'approval_status' => $approval_status,
					'purchasing_approved_at' => $time_stamp,
					'purchasing_approved_by' => $action_by,
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ RND Menu Item Details Updated!"
				]);
		}

		//for accounting
		public function getDetailAccounting($id) {
			return self::getDetailMarketing($id);
		}

		public function getEditAccounting($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_items')
				->where('rnd_menu_items.id', $id)
				->select(
					'rnd_menu_items.id as rnd_menu_items_id',
					'rnd_menu_items.rnd_menu_description',
					'rnd_code',
					'rnd_menu_items.portion_size',
					'rnd_menu_items.rnd_menu_srp',
					'approval_status',
					'computed_ingredient_total_cost',
					'computed_food_cost',
					'computed_food_cost_percentage',
					'publisher.name as published_by',
					'published_at',
					'marketing_approver.name as marketing_approver',
					'marketing_approved_at',
					'purchasing_approver.name as purchasing_approver',
					'purchasing_approved_at',
					'accounting_approver.name as accounting_approver',
					'accounting_approved_at'
				)
				->leftJoin('rnd_menu_approvals', 'rnd_menu_items.id', '=', 'rnd_menu_approvals.rnd_menu_items_id')
				->leftJoin('rnd_menu_computed_food_cost', 'rnd_menu_items.id', '=', 'rnd_menu_computed_food_cost.id')
				->leftJoin('cms_users as publisher', 'rnd_menu_approvals.published_by', '=', 'publisher.id')
				->leftJoin('cms_users as marketing_approver', 'rnd_menu_approvals.marketing_approved_by', '=', 'marketing_approver.id')
				->leftJoin('cms_users as purchasing_approver', 'rnd_menu_approvals.purchasing_approved_by', '=', 'purchasing_approver.id')
				->leftJoin('cms_users as accounting_approver', 'rnd_menu_approvals.accounting_approved_by', '=', 'accounting_approver.id')
				->first();

			return $this->view('rnd-menu/edit-accounting', $data);
		}

		public function approveByAccounting(Request $request) {
			$action = $request->get('action');
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$packaging_cost = $request->get('packaging_cost');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();

			if ($action == 'approve') {
				$approval_status = 'APPROVED';
				$db_column_by = 'accounting_approved_by';
				$db_column_at = 'accounting_approved_at';
				$message = '✔️ Item Approved!';
				$send_email = true;
			} else {
				$approval_status = 'REJECTED';
				$db_column_by = 'rejected_by';
				$db_column_at = 'rejected_at';
				$message = '✖️ Item Rejected!';
				self::notifyForRejection($rnd_menu_items_id);
				$send_email = false;
			}

			$item = DB::table('rnd_menu_items')
				->where('rnd_menu_items.id', $rnd_menu_items_id)
				->leftJoin('rnd_menu_computed_food_cost', 'rnd_menu_items.id', '=', 'rnd_menu_computed_food_cost.id')
				->first();

			DB::table('rnd_menu_approvals')
				->where('id', $rnd_menu_items_id)
				->update([
					'approval_status' => $approval_status,
					'updated_at' => $time_stamp,
					$db_column_at => $time_stamp,
					$db_column_by => $action_by,
				]);

			if ($send_email) {
				CRUDBooster::sendEmail([
					'to' => 'fillinorgunio@digits.ph',
					'from' => 'noreply@digits.ph',
					'data' => (array) $item,
					'template' => 'rnd_menu_creation',
				]);
			}

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => $message,
				]);
		}

		// custom functions
		public function addComment(Request $request) {
			$comment_content = $request->comment_content;
			$rnd_menu_items_id = $request->rnd_menu_items_id;
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');

			$inserted_id = DB::table('rnd_menu_comments')
				->insertGetId([
					'rnd_menu_items_id' => $rnd_menu_items_id,
					'comment_content' => $comment_content,
					'created_by' => $action_by,
					'created_at' => $time_stamp,
				]);

			$response = DB::table('rnd_menu_comments')
				->where('rnd_menu_comments.id', $inserted_id)
				->leftJoin('cms_users', 'rnd_menu_comments.created_by', '=', 'cms_users.id')
				->select('*', 'cms_users.id as cms_users_id', 'rnd_menu_comments.created_at as comment_added_at')
				->get()
				->first();

			return json_encode([$response]);

		}

		function notifyForRejection($id) {
			$item = DB::table('rnd_menu_items')
				->where('rnd_menu_items.id', $id)
				->leftJoin('rnd_menu_approvals', 'rnd_menu_approvals.rnd_menu_items_id', 'rnd_menu_items.id')
				->first();

			$to_notify = [$item->published_by];

			$myPrvilegeName = CRUDBooster::myPrivilegeName();
			
			$config = [
				'content' => "<strong>RND menu item: $item->rnd_menu_description</strong> has been rejected by $myPrvilegeName",
				'to' => CRUDBooster::adminPath("rnd_menu_items/detail/$id"),
				'id_cms_users' => $to_notify,
			];

			CRUDBooster::sendNotification($config);
		}

		function searchTempItems(Request $request) {
			$search_terms = json_decode($request->content);

			$result = DB::table('item_masters_temp')
				->where('status', 'ACTIVE')
				->where(function($query) use ($search_terms) {
					foreach ($search_terms as $search_term) {
						$query->where('item_description', 'like', "%{$search_term}%");
					}
				})
				->select('*', DB::raw('id as item_masters_temp_id'))
				->get()
				->toArray();

			return json_encode($result);
		}

		function getWorkFlowDetails($id) {

			$data = DB::table('rnd_menu_approvals')
			->where('rnd_menu_items_id', $id)
			->select(
				'published_by.name as published_by_name',
				'published_at',
				'packaging_updated_by.name as packaging_updated_by_name',
				'packaging_updated_at',
				'menu_created_by.name as menu_created_by_name',
				'menu_created_at',
				'costing_updated_by.name as costing_updated_by_name',
				'costing_updated_at',
				'marketing_approved_by.name as marketing_approved_by_name',
				'marketing_approved_at',
				'rejected_by.name as reject_by_name',
				'rejected_at'
			)
			->leftJoin('cms_users as published_by', 'published_by.id', '=', 'rnd_menu_approvals.published_by')
			->leftJoin('cms_users as packaging_updated_by', 'packaging_updated_by.id', '=', 'rnd_menu_approvals.packaging_updated_by')
			->leftJoin('cms_users as menu_created_by', 'menu_created_by.id', '=', 'rnd_menu_approvals.menu_created_by')
			->leftJoin('cms_users as costing_updated_by', 'costing_updated_by.id', '=', 'rnd_menu_approvals.costing_updated_by')
			->leftJoin('cms_users as marketing_approved_by', 'marketing_approved_by.id', '=', 'rnd_menu_approvals.marketing_approved_by')
			->leftJoin('cms_users as accounting_approved_by', 'accounting_approved_by.id', '=', 'rnd_menu_approvals.accounting_approved_by')
			->leftJoin('cms_users as rejected_by', 'rejected_by.id', '=', 'rnd_menu_approvals.rejected_by')
			->first();

			return $data;
		}

		function getMenuItemDetails($id) {
			$data = [];

			$menu_items_data = DB::table('menu_items')
				->where('menu_items.id', $id)
				->select(
					'*',
					'menu_items.id as menu_items_id',
				)
				->leftJoin('menu_categories', 'menu_categories.id', 'menu_items.menu_categories_id')
				->leftJoin('menu_types', 'menu_types.id', '=', 'menu_items.menu_types_id')
				->leftJoin('menu_subcategories', 'menu_subcategories.id', '=', 'menu_items.menu_subcategories_id')
				->get()
				->first();

			$all_old_codes = DB::table('menu_old_code_masters')
					->where('status', 'ACTIVE')
					->get()
					->toArray();

			$menu_items_data->old_codes = $all_old_codes;

			$all_segmentations = DB::table('menu_segmentations')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$menu_segmentations = [];

			foreach ($all_segmentations as $segmentation) {
				if ($menu_items_data->{$segmentation->menu_segment_column_name}) {
					$menu_segmentations[] = $segmentation->menu_segment_column_description;
				}
			}

			$menu_items_data->menu_segmentations = $menu_segmentations;

			$all_menu_choices_groups = DB::table('menu_choice_groups')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$menu_items_data->menu_choice_groups = $all_menu_choices_groups;

			foreach($all_menu_choices_groups as $choice_group) {
				$column_name = 'choices_sku' . $choice_group->menu_choice_group_column_name;
				$sku_ids = explode(', ', $menu_items_data->{$column_name});
				$menu_names = DB::table('menu_items')
					->whereIn('tasteless_menu_code', $sku_ids)
					->get('menu_item_description')
					->toArray();

				$menu_names = array_map(fn($obj) => $obj->menu_item_description, $menu_names);
				$menu_items_data->{$column_name} = $menu_names;
			}
			// dd($menu_items_data);
			return $menu_items_data;
		}

		function getRNDComments($id) {
			$data = [];

			$item = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->get()
				->first();

			$data['comments'] = DB::table('rnd_menu_comments')
				->where('rnd_menu_comments.rnd_menu_items_id', $id)
				->where('rnd_menu_comments.status', 'ACTIVE')
				->select('*', 'cms_users.id as cms_users_id', 'rnd_menu_comments.created_at as comment_added_at')
				->leftJoin('cms_users', 'rnd_menu_comments.created_by', '=', 'cms_users.id')
				->orderBy('comment_added_at', 'ASC')
				->get()
				->toArray();

			$data['rnd_menu_items_id'] = $id;

			$data['menu_item_description'] = $item->menu_item_description;

			return $data;
		}

	}