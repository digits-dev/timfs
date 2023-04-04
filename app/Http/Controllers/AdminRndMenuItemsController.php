<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;

	class AdminRndMenuItemsController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Rnd Code","name"=>"rnd_code"];
			$this->col[] = ["label"=>"Rnd Tasteless Code","name"=>"rnd_tasteless_code"];
			$this->col[] = ["label"=>"Rnd Menu Description","name"=>"rnd_menu_description"];
			$this->col[] = ["label"=>"SRP","name"=>"rnd_menu_srp"];
			$this->col[] = ["label"=>"Portion Size","name"=>"portion_size"];
			$this->col[] = ["label"=>"Ingredient Total Cost","name"=>"id","join"=>"rnd_menu_computed_food_cost,computed_ingredient_total_cost","join_id"=>"id"];
			$this->col[] = ["label"=>"Food Cost","name"=>"id","join"=>"rnd_menu_computed_food_cost,computed_food_cost","join_id"=>"id"];
			$this->col[] = ["label"=>"Food Cost Percentage","name"=>"id","join"=>"rnd_menu_computed_food_cost,computed_food_cost_percentage","join_id"=>"id"];
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

			if (is_numeric($column_value)) $column_value = (float) $column_value;
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
					'computed_food_cost_percentage'
				)
				->leftJoin('rnd_menu_computed_food_cost', 'rnd_menu_computed_food_cost.id', '=', 'rnd_menu_items.id')
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

			$data['ingredients'] = array_map(fn ($object) =>(object) array_filter((array) $object), $ingredients);

			return $this->view('rnd-menu/detail-item', $data);

		}
		
		public function getAdd() {
			if (!CRUDBooster::isCreate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];

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

			return $this->view('rnd-menu/add-item', $data);
		}

		public function getEdit($id) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);
			
			$data = [];

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
					'item_masters.created_at')
				->leftJoin('item_masters', 'item_masters.id', '=', 'rnd_menu_ingredients_auto_compute.item_masters_id')
				->leftJoin('menu_items', 'rnd_menu_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
				->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
				->orderBy('ingredient_group', 'ASC')
				->orderBy('row_id', 'ASC')
				->get()
				->toArray();

			return $this->view('rnd-menu/add-item', $data);
		}

		public function addNewRNDMenu(Request $request) {

			$rnd_menu_description = $request->get('rnd_menu_description');
			$food_cost = $request->get('food_cost');
			$food_cost_percentage = $request->get('food_cost_percentage');
			$rnd_menu_srp = $request->get('rnd_menu_srp');
			$portion_size = $request->get('portion_size');
			$ingredient_total_cost = $request->get('ingredient_total_cost');
			$ingredients = json_decode($request->get('ingredients'));
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$max_rnd_code = DB::table('rnd_menu_items')->max('rnd_code');
			$rnd_code_int = (int) explode('-', $max_rnd_code)[1] + 1;
			$rnd_code = 'RND-' . str_pad($rnd_code_int, 5, '0', STR_PAD_LEFT);
			

			//inserting new rnd menu item
			$rnd_menu_items_id = DB::table('rnd_menu_items')
				->insertGetId([
					'rnd_menu_description' => $rnd_menu_description,
					'rnd_code' => $rnd_code,
					'portion_size' => $portion_size,
					'rnd_menu_srp' => $rnd_menu_srp,
					'created_by' => $action_by,
					'created_at' => $time_stamp
				]);

			//looping through the nested ingredients by their ingredient_group
			foreach ($ingredients as $group) {
				foreach ($group as $ingredient) {
					$ingredient = (array) $ingredient;
					$ingredient['rnd_menu_items_id'] = $rnd_menu_items_id;
					$ingredient['created_by'] = $action_by;
					$ingredient['created_at'] = $time_stamp;
					
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
					DB::table('rnd_menu_ingredients_details')->insert($ingredient);
				}
			}
			


			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => 'New RND Item Created!'
				]);
		}

		public function editRNDMenu(Request $request) {

			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$rnd_menu_description = $request->get('rnd_menu_description');
			$food_cost = $request->get('food_cost');
			$food_cost_percentage = $request->get('food_cost_percentage');
			$rnd_menu_srp = $request->get('rnd_menu_srp');
			$portion_size = $request->get('portion_size');
			$ingredient_total_cost = $request->get('ingredient_total_cost');
			$ingredients = json_decode($request->get('ingredients'));
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();

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

			//inactivating all active ingredients of menu item
			DB::table('rnd_menu_ingredients_details')
				->where('status', 'ACTIVE')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'status' => 'INACTIVE',
					'row_id' => null,
					'deleted_at' => date('Y-m-d H:i:s')
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
					'message' => 'New RND Item Created!'
				]);
		}

	}