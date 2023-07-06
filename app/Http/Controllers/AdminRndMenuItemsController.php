<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Request as Input;
	use Illuminate\Support\Arr;

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
			$this->button_delete       = false;
			$this->button_edit         = false;
			$this->button_detail       = false;
			$this->button_show         = TRUE;
			$this->button_filter       = TRUE;        
			$this->button_export       = FALSE;	        
			$this->button_import       = FALSE;
			$this->button_bulk_action  = false;	
			$this->sidebar_mode		   = "normal"; //normal,mini,collapse,collapse-mini
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Approval Status","name"=>"id","join"=>"rnd_menu_approvals,approval_status","join_id"=>"rnd_menu_items_id"];
			$this->col[] = ["label"=>"RND Code","name"=>"rnd_code"];
			$this->col[] = ["label"=>"Concept","name"=>"segmentations_id","join"=>"segmentations,segment_column_description"];
			$this->col[] = ["label"=>"Tasteless Code","name"=>"menu_items_id","join"=>"menu_items,tasteless_menu_code"];
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
			$this->addaction[] = [
				'title'=>'Detail',
				'url'=>CRUDBooster::mainpath('detail/[id]'),
				'icon'=>'fa fa-eye',
				'color' => ' ',
			];

			$this->addaction[] = [
				'title'=>'Edit',
				'url'=>CRUDBooster::mainpath('edit/[id]'),
				'icon'=>'fa fa-pencil',
				'color' => ' ',
				"showIf"=>"[approval_status] == 'SAVED' || 
					[approval_status] == 'FOR FOOD TASTING' ||
					[approval_status] == 'ARCHIVED' ||
					[approval_status] == 'REJECTED' ||
					[approval_status] == 'FOR ADJUSTMENT'",
			];

			$this->addaction[] = [
				'title'=>'Delete',
				'url' => '#[id]',
				'icon'=>'fa fa-trash',
				'color' => ' delete-rnd-menu',
				"showIf"=>"[approval_status] == 'SAVED' || 
					[approval_status] == 'FOR FOOD TASTING' ||
					[approval_status] == 'ARCHIVED' ||
					[approval_status] == 'REJECTED' ||
					[approval_status] == 'FOR ADJUSTMENT'",
			];

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
			$main_path = CRUDBooster::mainPath();
	        $this->script_js = "
			$('.delete-rnd-menu').on('click', function() {
				const dbId = $(this).attr('href')?.replace('#', '');
				swal({   
						title: `Are you sure ?`,   
						text: `You will not be able to recover this record data!`,   
						type: `warning`,   
						showCancelButton: true,   
						confirmButtonColor: `#ff0000`,   
						confirmButtonText: `Yes!`,  
						cancelButtonText: `No`,  
						closeOnConfirm: false 
					}, 
					function(){location.href=`$main_path/delete-rnd-menu/` + dbId}
				);
			});
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
	        
			$query
				->addSelect('rnd_menu_approvals.approval_status')
				->orderBy(DB::raw("rnd_menu_approvals.approval_status = 'ARCHIVED'"));

			if (!CRUDBooster::isSuperAdmin()) {
				$my_concepts = DB::table('user_concept_acess')
					->where('cms_users_id', CRUDBooster::myId())
					->get()
					->first()
					->menu_segmentations_id;

				$query->where('rnd_menu_items.created_by', CRUDBooster::myId());
				
				// if ($my_concepts) {
				// 	$my_concepts = explode(',', $my_concepts);
				// 	$query
				// 		->leftJoin('user_concept_acess', 'user_concept_acess.cms_users_id', 'rnd_menu_items.created_by')
				// 		->where(function($sub_query) use ($my_concepts) {
				// 			foreach ($my_concepts as $my_concept) {
				// 				$sub_query->orWhereRaw("find_in_set('$my_concept', user_concept_acess.menu_segmentations_id)");
				// 			}
				// 		});
				// } else {
				// 	$query->where('rnd_menu_items.created_by', CRUDBooster::myId());
				// }

			}
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

			$blue_status = ['SAVED', 'FOR COSTING'];
			$dark_blue_status = ['FOR FOOD TASTING', 'RETURNED', 'FOR ADJUSTMENT', 'FOR POS UPDATE'];
			$orange_status = ['FOR PACKAGING', 'FOR MENU CREATION', 'FOR ITEM CREATION', 'FOR RELEASE DATE'];
			$green_status = ['APPROVED', 'CLOSED'];
			$red_status = ['REJECTED'];
			$purple_status = ['ARCHIVED'];
			
			if ($column_index == 1) {
				if (in_array($column_value, $blue_status)) {
					$column_value = "<span class='label label-info'>$column_value</span>";
				} else if (in_array($column_value, $orange_status)) {
					$column_value = "<span class='label label-warning'>$column_value</span>";
				} else if (in_array($column_value, $green_status)) {
					$column_value = "<span class='label label-success'>$column_value</span>";
				} else if (in_array($column_value, $dark_blue_status)) {
					$column_value = "<span class='label label-primary'>$column_value</span>";
				} else if (in_array($column_value, $red_status)) {
					$column_value = "<span class='label label-danger'>$column_value</span>";
				} else if (in_array($column_value, $purple_status)) {
					$column_value = "<span class='label label-purple'>$column_value</span>";
				}
				
				if (str_contains($column_value, 'APPROVAL')) $column_value = "<span class='label label-info'>$column_value</span>";
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

			$ingredients = self::getIngredients($id);

			
			$packagings = self::getPackagings($id);
			
			$rnd_menu_description = $data['item']->rnd_menu_description;
			$data['ingredients'] = $ingredients;
			$data['packagings'] = $packagings;
			$data['page_title'] = "Detail RND Menu Item: $rnd_menu_description";
			$data['comments_data'] = self::getRNDComments($id);
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

			$data['uoms'] = DB::table('packagings')
				->where('status', 'ACTIVE')
				->select('id', 'packaging_description')
				->orderBy('packaging_description')
				->get()
				->toArray();

			$data['privilege'] = CRUDBooster::myPrivilegeName();

			$data['segmentations'] = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->orderBy('segmentations.segment_column_description', 'asc')
				->get()
				->toArray();

			if ($id) {
				$data['ingredients'] = DB::table('rnd_menu_ingredients_auto_compute')
					->where('rnd_menu_items_id', $id)
					->where('rnd_menu_ingredients_auto_compute.status', 'ACTIVE')
					->select(\DB::raw('item_masters.id as item_masters_id'),
						'ingredient_name',
						'batching_ingredients_computed_food_cost.ingredient_description',
						'batching_ingredients_computed_food_cost.id as batching_ingredients_id',
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
						'packagings_id',
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
						'rnd_menu_ingredients_auto_compute.new_ingredients_id',
						'rnd_menu_ingredients_auto_compute.item_description')
					->leftJoin('item_masters', 'item_masters.id', '=', 'rnd_menu_ingredients_auto_compute.item_masters_id')
					->leftJoin('menu_items', 'rnd_menu_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
					->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
					->leftJoin('new_ingredients', 'new_ingredients.id', '=', 'rnd_menu_ingredients_auto_compute.new_ingredients_id')
					->leftJoin('batching_ingredients_computed_food_cost', 'batching_ingredients_computed_food_cost.id', 'rnd_menu_ingredients_auto_compute.batching_ingredients_id')
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
						'packagings_id',
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
						'rnd_menu_packagings_auto_compute.new_packagings_id',
						'rnd_menu_packagings_auto_compute.item_description')
					->leftJoin('item_masters', 'item_masters.id', '=', 'rnd_menu_packagings_auto_compute.item_masters_id')
					->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
					->orderBy('packaging_group', 'ASC')
					->orderBy('row_id', 'ASC')
					->get()
					->toArray();

				$data['food_cost_data'] = DB::table('rnd_menu_computed_food_cost')
					->where('id', $id)
					->get()
					->first();

				$data['approval_status'] = DB::table('rnd_menu_approvals')
					->where('rnd_menu_items_id', $id)
					->get()
					->first()
					->approval_status;

				$data['comments_data'] = self::getRNDComments($id);

				$data['workflow_data'] = self::getWorkFlowDetails($id);
			}

			$data['page_title'] = ucwords("$action RND Menu", '-');

			
			if ($action == 'add-packaging') {
				return $this->view('rnd-menu/add-packaging', $data);
			}

			return $this->view('rnd-menu/add-item', $data);
		}

		public function editRNDMenu(Request $request, $action = 'save') {

			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$rnd_menu_description = $request->get('rnd_menu_description');
			$segmentations_id = $request->get('segmentations_id');
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
						'segmentations_id' => $segmentations_id,
						'rnd_code' => $rnd_code,
						'portion_size' => $portion_size,
						'rnd_menu_srp' => $rnd_menu_srp,
						'created_by' => $action_by,
						'created_at' => $time_stamp
					]);

				$message = "✔️ New RND Menu Item Created: $rnd_menu_description";
			} else {
				//update details for rnd menu item
				DB::table('rnd_menu_items')
					->where('id', $rnd_menu_items_id)
					->update([
						'rnd_menu_description' => $rnd_menu_description,
						'segmentations_id' => $segmentations_id,
						'rnd_menu_srp' => $rnd_menu_srp,
						'portion_size' => $portion_size,
						'updated_at' => $time_stamp,
						'updated_by' => $action_by
					]);

				$message = "✔️ RND Menu Item Details of $rnd_menu_description Updated!";
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
							'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id'],
							'new_ingredients_id' => $ingredient['new_ingredients_id'],
							'batching_ingredients_id' => $ingredient['batching_ingredients_id']
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
						$ingredient['ttp']
					);

					//finally, inserting ingredients to the table
					DB::table('rnd_menu_ingredients_details')->updateOrInsert([
						'rnd_menu_items_id' => $rnd_menu_items_id,
						'item_masters_id' => $ingredient['item_masters_id'],
						'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id'],
						'new_ingredients_id' => $ingredient['new_ingredients_id'],
						'batching_ingredients_id' => $ingredient['batching_ingredients_id']
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
							'new_packagings_id' => $packaging['new_packagings_id'],
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
						'new_packagings_id' => $packaging['new_packagings_id']
					], $packaging);
						
				}
			}

			//updating the comments
			DB::table('rnd_menu_comments')
				->where('created_by', $action_by)
				->where('rnd_menu_items_id', null)
				->update(['rnd_menu_items_id' => $rnd_menu_items_id]);
			
			//updating approval status
			DB::table('rnd_menu_approvals')
				->updateOrInsert(['rnd_menu_items_id' => $rnd_menu_items_id],[
					'rnd_menu_items_id' => $rnd_menu_items_id,
					'approval_status' => $rnd_menu_approval_status,
					'created_at' => $time_stamp,
				]);
			
			if ($action != 'save') {
				return $rnd_menu_items_id;
			}

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => $message,
				]);
		}

		public function foodTastingRNDMenu(Request $request) {
			$rnd_menu_approval_status = 'FOR FOOD TASTING';
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$rnd_menu_description = $request->get('rnd_menu_description');
			$rnd_menu_items_id = self::editRNDMenu($request, 'food-tasting');

			DB::table('rnd_menu_approvals')
				->updateOrInsert(['rnd_menu_items_id' => $rnd_menu_items_id],[
					'rnd_menu_items_id' => $rnd_menu_items_id,
					'approval_status' => $rnd_menu_approval_status,
					'food_tasting_by' => $action_by,
					'food_tasting_at' => $time_stamp,
					'updated_at' => $time_stamp,
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ $rnd_menu_description: status updated!"
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
					'message' => "✔️ $rnd_menu_description forwarded to Marketing for Packaging!"
				]);
		}

		public function archiveRNDMenu(Request $request) {
			$rnd_menu_approval_status = 'ARCHIVED';
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$rnd_menu_description = $request->get('rnd_menu_description');
			$rnd_menu_items_id = self::editRNDMenu($request, 'archive');

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
					'message' => "✔️ $rnd_menu_description added to archived items!"
				]);
		}

		public function deleteRndMenuItem($id) {
			DB::table('rnd_menu_items')
				->where('id', $id)
				->update([
					'status' => 'INACTIVE',
					'deleted_at' => date('Y-m-d H:i:s')
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "✔️ RND Menu Item Deleted!"
				]);
		}

		public function searchAllIngredients(Request $request) {
			$search_terms = json_decode($request->content);
			$with_menu = json_decode($request->with_menu);

			$item_masters = DB::table('item_masters')
				->where('sku_statuses_id', '!=', '2')
				->where(function($query) use ($search_terms) {
					$query->where(function($query_item_desc) use ($search_terms) {
						$query_item_desc->orWhere(function($q) use ($search_terms) {
							foreach ($search_terms as $term) {
								$q->where('full_item_description', 'like', "%{$term}%");
							}
						})->orWhere(function($q) use ($search_terms) {
							foreach ($search_terms as $term) {
								$q->where('brands.brand_description', 'like', "%{$term}%");
							}
						});
					})->orWhere(function ($query_code) use ($search_terms) {
						foreach ($search_terms as $term) {
							$query_code->orWhere('tasteless_code', 'like', "%{$term}%");
						}
					});
				})
				->select(\DB::raw('item_masters.id as item_masters_id'),
					'item_masters.packagings_id',
					'ttp',
					'packaging_size',
					'item_masters.full_item_description',
					'item_masters.tasteless_code',
					'uoms.uom_description',
					'brands.brand_description',
					'item_masters.updated_at',
					'item_masters.created_at')
				->leftJoin('uoms','item_masters.packagings_id', '=', 'uoms.id')
				->leftJoin('brands', 'item_masters.brands_id', '=', 'brands.id')
				->orderby('full_item_description')
				->get()
				->toArray();

			if (!$with_menu) {
				return json_encode($item_masters);
			}
			
			$menu_items = DB::table('menu_items')
				->where('menu_items.status', 'ACTIVE')
				->where(function($query) use ($search_terms) {
					$query->orWhere(function($q) use ($search_terms) {
						foreach ($search_terms as $term) {
							$q->where('menu_item_description', 'like', "%{$term}%");
						}
					})->orWhere(function($q) use ($search_terms) {
						foreach ($search_terms as $term) {
							$q->orWhere('tasteless_menu_code', 'like', "%{$term}%");
						}
					});
				})
				->select('menu_items.id as menu_item_id',
					'menu_item_description',
					'tasteless_menu_code',
					'food_cost',
					'menu_items.uoms_id',
					'uom_description')
				->leftJoin('uoms', 'uoms.id', '=', 'menu_items.uoms_id')
				->get()
				->toArray();

			$batching_ingredients = DB::table('batching_ingredients')
				->where('batching_ingredients.status', 'ACTIVE')
				->where(function($query) use ($search_terms) {
					$query->orWhere(function($q) use ($search_terms) {
						foreach ($search_terms as $term) {
							$q->where('batching_ingredients.ingredient_description', 'like', "%{$term}%");
						}
					})->orWhere(function($q) use ($search_terms) {
						foreach ($search_terms as $term) {
							$q->orWhere('batching_ingredients.bi_code', 'like', "%{$term}%");
						}
					});
				})
				->select('batching_ingredients.id as batching_ingredients_id',
					'batching_ingredients.ingredient_description',
					'batching_ingredients_computed_food_cost.portion_ttp as ttp',
					'batching_ingredients.uoms_id',
					'uom_description')
				->leftJoin('uoms', 'uoms.id', '=', 'batching_ingredients.uoms_id')
				->leftJoin('batching_ingredients_computed_food_cost', 'batching_ingredients_computed_food_cost.id', '=', 'batching_ingredients.id')
				->get()
				->toArray();

			$response = array_merge($item_masters, $menu_items, $batching_ingredients);
			
			return json_encode($response);
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
							'new_packagings_id' => $packaging['new_packagings_id'],
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
						'new_packagings_id' => $packaging['new_packagings_id']
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
					'rnd_menu_items.segmentations_id',
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
				
			$menu_items_id = DB::table('rnd_menu_items')
				->where('id', $id)
				->get()
				->first()
				->menu_items_id;

			$comments = self::getRNDComments($id);
			
			if ($menu_items_id) {
				return (new AdminAddMenuItemsController)->getEdit($menu_items_id, 'rnd_menu_items', $id, $comments);
			}

			return (new AdminAddMenuItemsController)->getAdd('rnd_menu_items', $item, $comments);
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
			// $data['tasteless_menu_code'] = $tasteless_menu_code+1;
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
			// $data['original_concept'] = $returnInputs['original_concept'];
			$data['pos_old_item_description'] = $returnInputs['pos_item_description'];
			$data['menu_product_types_name'] = $returnInputs['product_type'];
			$data['menu_categories_id'] = $returnInputs['menu_categories'];
			$data['menu_subcategories_id'] = $returnInputs['sub_category'];
			$data['status'] = $returnInputs['status'];
			$data['segmentations_id'] = implode(',', $returnInputs['original_concept']);
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

			$portion_size = DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->get()
				->first()
				->portion_size;

			$data['portion_size'] = $portion_size;

			$inserted_id = DB::table('menu_items')
				->insertGetId($data);

			// updating the details of rnd menu in db
			DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->update([
					'menu_items_id' => $inserted_id,
					'rnd_menu_description' => $rnd_menu_description,
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
		}

		public function editNewMenu(Request $request, $id) {
			$returnInputs = Input::all();
			$rnd_menu_items_id = $returnInputs['rnd_menu_items_id'];
			$approval_status = 'FOR COSTING';
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');
			$rnd_menu_description = $returnInputs['menu_item_description'];


			//------> START CODE FROM PAT'S CONTROLLER
			$menu_segment_names = [];
			$user_menu_segmentations = DB::table('menu_segmentations')
				->where('status','ACTIVE')
				->select('menu_segment_column_name')
				->get();
			$menu_segments = Arr::pluck($user_menu_segmentations, 'menu_segment_column_name');

			// Choices Group
			$choices_group = DB::table('menu_choice_groups')
				->select('id')
				->where('status', 'ACTIVE')
				->get();

			// Add data to database
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
				}else{
					$data[$choices_skugroup_str] = null;
				}
			}
			$data['menu_types_id'] = $returnInputs['menu_type'];
			// $data['original_concept'] = $returnInputs['original_concept'];
			$data['pos_old_item_description'] = $returnInputs['pos_item_description'];
			$data['menu_product_types_name'] = $returnInputs['product_type'];
			$data['menu_categories_id'] = $returnInputs['menu_categories'];
			$data['menu_subcategories_id'] = $returnInputs['sub_category'];
			$data['status'] = $returnInputs['status'];
			$data['segmentations_id'] = implode(',', $returnInputs['original_concept']);
			$data['updated_by'] = CRUDBooster::myid();
			// Update Store List
			if($returnInputs['menu_segment_column_description'] != null){
				// Reset Store List
				foreach($menu_segments as $segments){
					$data[$segments] = null;
				}
				foreach($returnInputs['menu_segment_column_description'] as $menu_segments_id){
					$menu_segmentations_column_name = DB::table('menu_segmentations')
						->where('id', $menu_segments_id)
						->select('menu_segment_column_name')
						->value('menu_segment_column_name');
					$data[$menu_segmentations_column_name] = 1;
					array_push($menu_segment_names, $menu_segmentations_column_name);
				}
			}else{
				foreach($menu_segments as $segments){
					$data[$segments] = null;
				}				
			}

			//------> END CODE FROM PAT'S CONTROLLER

			$is_updated = DB::table('menu_items')
				->where('id', $id)
				->update($data);

			// updating the details of rnd menu in db
			DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->update([
					'rnd_menu_description' => $rnd_menu_description,
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
		}

		public function getSetCosting($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->first();

			$data['workflow'] = self::getWorkFlowDetails($id);

			$data['menu_items_data'] = self::getMenuItemDetails($data['item']->menu_items_id);

			$data['comments_data'] = self::getRNDComments($id);

			$data['page_title'] = 'Add Costing RND Menu';
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

		public function getApproveByMarketing($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->first();

			$data['workflow'] = self::getWorkFlowDetails($id);

			$data['menu_items_data'] = self::getMenuItemDetails($data['item']->menu_items_id);

			$data['comments_data'] = self::getRNDComments($id);

			$data['page_title'] = 'Approve RND Menu';

			return $this->view('rnd-menu/approve-item-marketing', $data);
		}

		public function approveByMarketing(Request $request) {
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$action = $request->get('action');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$approval_status = null;

			if ($action == 'approve') {
				$approval_status = 'FOR APPROVAL (ACCOUNTING)';
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

		public function getAddReleaseDate($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->first();

			$data['rnd_menu_item'] = DB::table('rnd_menu_items')
				->where('id', $id)
				->first();

			$data['workflow'] = self::getWorkFlowDetails($id);

			$data['menu_items_data'] = self::getMenuItemDetails($data['item']->menu_items_id);

			$data['page_title'] = 'Add Release Date';

			return $this->view('rnd-menu/add-release-date', $data);
		}

		public function addReleaseDate(Request $request) {
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$release_date = $request->get('release_date');
			$end_date = $request->get('end_date');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$approval_status = 'FOR POS UPDATE';

			DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->update([
					'release_date' => $release_date,
					'end_date' => $end_date,
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'set_release_date_by' => $action_by,
					'set_release_date_at' => $time_stamp,
					'updated_at' => $time_stamp,
					'approval_status' => $approval_status,
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => '✔️ Release Date added.',
				]);
		}

		//for accounting
		public function getApproveByAccounting($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->first();

			$no_code_ingredients = DB::table('rnd_menu_ingredients_details')
				->where('status', 'ACTIVE')
				->where('rnd_menu_items_id', $id)
				->where('is_existing', '!=', 'TRUE')
				->count();

			$no_code_packagings = DB::table('rnd_menu_packagings_details')
				->where('status', 'ACTIVE')
				->where('rnd_menu_items_id', $id)
				->where('is_existing', '!=', 'TRUE')
				->count();

			$data['no_codes'] = $no_code_ingredients + $no_code_packagings;

			$data['comments_data'] = self::getRNDComments($id);
			
			$data['workflow'] = self::getWorkFlowDetails($id);

			$data['menu_items_data'] = self::getMenuItemDetails($data['item']->menu_items_id);

			$data['page_title'] = 'Approve RND Menu';

			return $this->view('rnd-menu/approve-item-accounting', $data);
		}

		public function approveByAccounting(Request $request) {
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$action = $request->get('action');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$approval_status = null;

			if ($action == 'approve') {
				$approval_status = 'FOR RELEASE DATE';
				$db_column_at = 'accounting_approved_at';
				$db_column_by = 'accounting_approved_by';
				$message = '✔️ Item Approved!';
			} else if ($action == 'reject') {
				$approval_status = 'REJECTED';
				$db_column_at = 'rejected_at';
				$db_column_by = 'rejected_by';
				$message = '✖️ Item Rejected!';
				self::notifyForRejection($rnd_menu_items_id);
			}

			// updating approval status
			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'approval_status' => $approval_status,
					$db_column_by => $action_by,
					$db_column_at => $time_stamp,
				]);

			if ($action == 'approve') {
				// getting the foreign key of menu item and rnd menu item
				$menu_items_id = DB::table('rnd_menu_items')
					->where('id', $rnd_menu_items_id)
					->get('menu_items_id')
					->first()
					->menu_items_id;

				// getting all the active ingredients of the rnd menu
				$ingredients = DB::table('rnd_menu_ingredients_details')
					->where('rnd_menu_items_id', $rnd_menu_items_id)
					->where('status', 'ACTIVE')
					->get()
					->toArray();
				
				// preparing every ingredient to be copied as
				// ingredient of the menu item
				foreach ($ingredients as $index => $ingredient) {
					$ingredient = (array) $ingredient;
					unset(
						$ingredient['id'],
						$ingredient['rnd_menu_items_id'],
						$ingredient['item_masters_temp_id'],
						$ingredient['deleted_at'],
					);
	
					$ingredient['menu_items_id'] = $menu_items_id;
					$ingredients[$index] = $ingredient;
				}

				// for assurance: set inactive all existing ingredients of the menu
				DB::table('menu_ingredients_details')
					->where('menu_items_id', $menu_items_id)
					->update(['status' => 'INACTIVE']);
	
				// finally, inserting all ingredients to the table
				DB::table('menu_ingredients_details')
					->insert($ingredients);

				// getting all active packaging of the rnd menu
				$packagings = DB::table('rnd_menu_packagings_details')
					->where('rnd_menu_items_id', $rnd_menu_items_id)
					->where('status', 'ACTIVE')
					->get()
					->toArray();

				// preparing every packaging to be copied as
				// packaging of the menu item
				foreach ($packagings as $index => $packaging) {
					$packaging = (array) $packaging;
					unset(
						$packaging['id'],
						$packaging['rnd_menu_items_id'],
						$packaging['item_masters_temp_id'],
						$packaging['deleted_at']
					);

					$packaging['menu_items_id'] = $menu_items_id;
					$packagings[$index] = $packaging;
				}

				// for assurance: set inactive to existing packagings of the menu
				DB::table('menu_packagings_details')
					->where('menu_items_id', $menu_items_id)
					->update(['status' => 'INACTIVE']);

				// finally, inserting all packagings to the table
				DB::table('menu_packagings_details')
					->insert($packagings);

				$promo_id = DB::table('menu_types')
					->select('id')
					->where('status', 'ACTIVE')
					->where('menu_type_description', 'PROMO')->value('id');

				$menu_item = DB::table('menu_items')
					->where('menu_items.id', $menu_items_id)
					->select('*')
					->leftJoin('menu_types', 'menu_types.id', '=', 'menu_items.menu_types_id')
					->first();

				if ($menu_item->menu_type_description == 'PROMO') {
					$tasteless_menu_code = (int) DB::table('menu_items')
						->where('tasteless_menu_code','like',"5%")
						->select('tasteless_menu_code')
						->max('tasteless_menu_code');
				} else {
					$tasteless_menu_code = (int) DB::table('menu_items')
						->where('tasteless_menu_code','like',"6%")
						->select('tasteless_menu_code')
						->max('tasteless_menu_code');
				}

				DB::table('menu_items')
					->leftJoin('menu_computed_food_cost', 'menu_items.id', '=', 'menu_computed_food_cost.id')
					->where('menu_items.id', $menu_items_id)
					->update([
						'menu_items.ingredient_total_cost' => DB::raw('menu_computed_food_cost.computed_ingredient_total_cost'),
						'menu_items.food_cost' => DB::raw('menu_computed_food_cost.computed_food_cost'),
						'menu_items.food_cost_percentage' => DB::raw('menu_computed_food_cost.computed_food_cost_percentage'),
						'menu_items.tasteless_menu_code' => $tasteless_menu_code + 1,
					]);
			}
			

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => $message,
				]);
		}

		public function getAddPosUpdate($id) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->first();

			$data['rnd_menu_item'] = DB::table('rnd_menu_items')
				->where('id', $id)
				->first();

			$data['workflow'] = self::getWorkFlowDetails($id);

			$data['menu_items_data'] = self::getMenuItemDetails($data['item']->menu_items_id);

			$data['page_title'] = 'Add POS Update Date';

			return $this->view('rnd-menu/add-pos-update', $data);
		}

		public function addPosUpdate(Request $request) {
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$pos_update = $request->get('pos_update');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$approval_status = 'CLOSED';

			DB::table('rnd_menu_items')
				->where('id', $rnd_menu_items_id)
				->update([
					'pos_update' => $pos_update,
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'set_pos_update_by' => $action_by,
					'set_pos_update_at' => $time_stamp,
					'updated_at' => $time_stamp,
					'approval_status' => $approval_status,
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => '✔️ POS Update Date added.',
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
				->select(
					'*', 
					'cms_users.id as cms_users_id', 
					'rnd_menu_comments.created_at as comment_added_at', 
					'rnd_menu_comments.id as comment_id'
				)
				->get()
				->first();

			return json_encode([$response]);

		}

		public function deleteComment(Request $request) {
			$comment_id = $request->comment_id;
			$time_stamp = date('Y-m-d H:i:s');

			$response = DB::table('rnd_menu_comments')
				->where('id', $comment_id)
				->update([
					'status' => 'INACTIVE',
					'deleted_at' => $time_stamp,
				]);

			return json_encode($response);
		}

		public function getDetailNoIngredient($id, $with_comments = true) {
			$data = [];

			$data['item'] = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->leftJoin('rnd_menu_items', 'rnd_menu_items.id', '=', 'rnd_menu_costing.rnd_menu_items_id')
				->first();

			$data['workflow'] = self::getWorkFlowDetails($id);

			$data['menu_items_data'] = self::getMenuItemDetails($data['item']->menu_items_id);

			$data['comments_data'] = self::getRNDComments($id, true);

			if (!$with_comments) {
				return $this->view('rnd-menu/detail-no-comments', $data);
			}

			return $this->view('rnd-menu/detail-approvers', $data);
		}

		public function getDetailWithIngredient($id) {
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

			$ingredients = self::getIngredients($id);
			
			$packagings = self::getPackagings($id);
			
			$rnd_menu_description = $data['item']->rnd_menu_description;
			$data['ingredients'] = array_map(fn ($object) => (object) array_filter((array) $object), $ingredients);
			$data['packagings'] = array_map(fn ($object) => (object) array_filter((array) $object), $packagings);
			$data['comments_data'] = self::getRNDComments($id);
			$data['page_title'] = "Detail RND Menu Item: $rnd_menu_description";

			return $this->view('rnd-menu/detail-with-ingredients', $data);
		}

		public function returnRNDMenu(Request $request) {
			$rnd_menu_items_id = $request->get('rnd_menu_items_id');
			$return_to = $request->get('return_to');
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$approval_status = null;
			$message = null;

			if ($return_to == 'chef') {
				$approval_status = 'FOR ADJUSTMENT';
				$message = '✔️ Item successfully returned to Chef.';
			} else {
				$approval_status = 'FOR PACKAGING';
				$message = '✔️ Item successfully returned to Marketing.';
			}

			DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $rnd_menu_items_id)
				->update([
					'returned_by' => $action_by,
					'returned_at' => $time_stamp,
					'approval_status' => $approval_status,
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => $message,
				]);
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
				->where('item_masters_id', null)
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
					'accounting_approved_by.name as accounting_approved_by_name',
					'accounting_approved_at',
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

			if (!$id) return;

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
			return $menu_items_data;
		}

		function getRNDComments($id, $to_comment = true) {
			$data = [];

			$item = DB::table('rnd_menu_costing')
				->where('rnd_menu_items_id', $id)
				->get()
				->first();

			$data['comments'] = DB::table('rnd_menu_comments')
				->where('rnd_menu_comments.rnd_menu_items_id', $id)
				->where('rnd_menu_comments.status', 'ACTIVE')
				->select(
					'*', 
					'cms_users.id as cms_users_id', 
					'rnd_menu_comments.created_at as comment_added_at', 
					'rnd_menu_comments.id as comment_id'
				)
				->leftJoin('cms_users', 'rnd_menu_comments.created_by', '=', 'cms_users.id')
				->orderBy('comment_added_at', 'ASC')
				->get()
				->toArray();

			$data['rnd_menu_items_id'] = $id;

			$data['menu_item_description'] = ($item->menu_item_description ? $item->menu_item_description : $item->rnd_menu_description);

			$data['to_comment'] = $to_comment;

			return $data;
		}

		function getIngredients($id) {
			$ingredients = DB::table('rnd_menu_ingredients_auto_compute')
				->where('rnd_menu_items_id', $id)
				->where('rnd_menu_ingredients_auto_compute.status', 'ACTIVE')
				->select('tasteless_code',
					'menu_items.status as menu_item_status',
					'sku_statuses.sku_status_description as item_status',
					'new_ingredients.status as new_ingredient_status',
					'batching_ingredients.status as batching_ingredient_status',
					'rnd_menu_ingredients_auto_compute.item_masters_id',
					'rnd_menu_ingredients_auto_compute.menu_item_description',
					'rnd_menu_ingredients_auto_compute.item_description',
					'rnd_menu_ingredients_auto_compute.ingredient_description',
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
					'rnd_menu_ingredients_auto_compute.packaging_description',
					'yield',
					'rnd_menu_ingredients_auto_compute.ttp',
					'cost',
					'item_masters.updated_at',
					'item_masters.created_at',
					'rnd_menu_ingredients_auto_compute.item_description')
				->leftJoin('item_masters', 'rnd_menu_ingredients_auto_compute.item_masters_id', '=', 'item_masters.id')
				->leftJoin('menu_items', 'rnd_menu_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
				->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
				->leftJoin('menu_ingredients_preparations', 'rnd_menu_ingredients_auto_compute.menu_ingredients_preparations_id', '=', 'menu_ingredients_preparations.id')
				->leftJoin('new_ingredients', 'new_ingredients.id', '=', 'rnd_menu_ingredients_auto_compute.new_ingredients_id')
				->leftJoin('batching_ingredients', 'batching_ingredients.id', '=', 'rnd_menu_ingredients_auto_compute.batching_ingredients_id')
				->orderby('ingredient_group', 'asc')
				->orderby('row_id', 'asc')
				->get()
				->toArray();

			return $ingredients;
		}

		function getPackagings($id) {
			$packagings = DB::table('rnd_menu_packagings_auto_compute')
				->where('rnd_menu_items_id', $id)
				->where('rnd_menu_packagings_auto_compute.status', 'ACTIVE')
				->select('tasteless_code',
				'sku_statuses.sku_status_description as item_status',
				'new_packagings.status as new_packaging_status',
				'rnd_menu_packagings_auto_compute.item_masters_id',
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
				'rnd_menu_packagings_auto_compute.packaging_description',
				'yield',
				'rnd_menu_packagings_auto_compute.ttp',
				'cost',
				'item_masters.updated_at',
				'item_masters.created_at',
				'rnd_menu_packagings_auto_compute.item_description')
			->leftJoin('item_masters', 'rnd_menu_packagings_auto_compute.item_masters_id', '=', 'item_masters.id')
			->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
			->leftJoin('menu_ingredients_preparations', 'rnd_menu_packagings_auto_compute.menu_ingredients_preparations_id', '=', 'menu_ingredients_preparations.id')
			->leftJoin('new_packagings', 'new_packagings.id', '=', 'rnd_menu_packagings_auto_compute.new_packagings_id')
			->orderby('packaging_group', 'asc')
			->orderby('row_id', 'asc')
			->get()
			->toArray();

			return $packagings;
		}

	}