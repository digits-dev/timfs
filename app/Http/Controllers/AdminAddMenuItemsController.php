<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\MenuItem;
	use App\MenuItemApproval;
	use App\ApprovalWorkflowSetting;
	use App\Exports\ExcelTemplate;
	use App\Exports\MenuItemsExport;
	use App\Imports\MenuItemsImport;
	use App\MenuChoiceGroup;
	use App\MenuOldCodeMaster;
	use App\MenuPriceMaster;
	use App\MenuSegmentation;
	use Maatwebsite\Excel\HeadingRowImport;
	use Maatwebsite\Excel\Imports\HeadingRowFormatter;
	use Maatwebsite\Excel\Facades\Excel;
	Use Alert;
	use Illuminate\Support\Facades\Request as Input;
	use Illuminate\Support\Arr;



	class AdminAddMenuItemsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "ingredient_name_3";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "menu_items";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# END CONFIGURATION DO NOT REMOVE THIS LINE

			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
			$prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Tasteless Menu Code","name"=>"tasteless_menu_code"];
			foreach($old_item_codes as $old_code){
				$this->col[] = ["label"=>ucwords(strtolower($old_code->menu_old_code_column_description)),"name"=>$old_code->menu_old_code_column_name];
			}
			$this->col[] = ["label"=>"POS Old Item Description","name"=>"pos_old_item_description"];
			$this->col[] = ["label"=>"Menu Item Description","name"=>"menu_item_description"];
			$this->col[] = ["label"=>"Menu Category","name"=>"menu_categories_id","join"=>"menu_categories,category_description"];
			$this->col[] = ["label"=>"Menu Subcategory","name"=>"menu_subcategories_id","join"=>"menu_subcategories,subcategory_description"];
			// $this->col[] = ["label"=>"Menu Product Type","name"=>"menu_product_types_id","join"=>"menu_product_types,menu_product_type_description"];
			$this->col[] = ["label"=>"Menu Product Type", 'name'=> 'menu_product_types_name'];
			$this->col[] = ["label"=>"Menu Type","name"=>"menu_types_id","join"=>"menu_types,menu_type_description"];
			foreach($prices as $price){
				$this->col[] = ["label"=>ucwords(strtolower($price->menu_price_column_description)),"name"=>$price->menu_price_column_name];
			}
			$this->col[] = ["label"=>"Food Cost","name"=>"food_cost"];
			$this->col[] = ["label"=>"Food Cost Percentage","name"=>"food_cost_percentage"];
            $this->col[] = ["label"=>"Original Concept","name"=>"original_concept"];;
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ["label"=>"Action Type","name"=>"action_type","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			$this->form[] = ["label"=>"Tasteless Menu Code","name"=>"tasteless_menu_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			$this->form[] = ["label"=>"Old Code 3","name"=>"old_code_3","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			$this->form[] = ["label"=>"Old Code 2","name"=>"old_code_2","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			$this->form[] = ["label"=>"Old Code 1","name"=>"old_code_1","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			$this->form[] = ["label"=>"Menu Item Description","name"=>"menu_item_description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			$this->form[] = ["label"=>"Pos Old Item Description","name"=>"pos_old_item_description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];

			# END FORM DO NOT REMOVE THIS LINE     

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
	        $this->script_js = "";


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
			$query->orderBy('status', 'asc');
			
			if (CRUDBooster::myPrivilegeName() == 'Chef') {

				$concept_access_id = DB::table('user_concept_acess')
					->where('cms_users_id', CRUDBooster::myID())
					->get('menu_segmentations_id')
					->first()
					->menu_segmentations_id;
				
				$concepts = DB::table('menu_segmentations')
					->whereIn('id', explode(',', $concept_access_id))
					->get('menu_segment_column_name')->toArray();

					$query->where(function($subQuery) use ($concepts) {
						foreach($concepts as $concept) {
							$subQuery->orWhere('menu_items.' . $concept->menu_segment_column_name, '1');
						}
					});
				}
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code 

			if($column_index == '2'){

				$tasteless_menu_code_id = DB::table('menu_items')
					->where('status', 'ACTIVE')
					->where('tasteless_menu_code', $column_value)
					->first();
		
				$product_type_name = DB::table('menu_product_types')
					->where('id', $tasteless_menu_code_id->menu_product_types_id)
					->select('menu_product_type_description')
					->value('menu_product_type_description');

				if($tasteless_menu_code_id->menu_product_types_name == null){
					$update = DB::table('menu_items')
					->where('tasteless_menu_code', $tasteless_menu_code_id->tasteless_menu_code)
					->update([
						'menu_product_types_name' => $product_type_name
					]);
				}
								
			}

			if($column_index == '18'){

				if($column_value == 'INACTIVE'){
					$column_value = '<span class="label label-danger">INACTIVE</span>';
				}else{
					$column_value = '<span class="label label-success">ACTIVE</span>';
				}
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

			$returnInputs = Input::all();

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
			$postdata['tasteless_menu_code'] = $tasteless_menu_code+1;
			$postdata['old_code_1'] = $returnInputs['pos_item_code_1'];
			$postdata['old_code_2'] = $returnInputs['pos_item_code_2'];
			$postdata['old_code_3'] = $returnInputs['pos_item_code_3'];
			$postdata['menu_item_description'] = $returnInputs['menu_item_description'];
			for($i=0; $i<count($choices_group); $i++){
				$choices_group_str = 'choices_group_'.(string)($i+1);
				$choices_skugroup_str = 'choices_skugroup_'.(string)($i+1);
				$postdata[$choices_group_str] = $returnInputs[$choices_group_str];
				if($returnInputs[$choices_skugroup_str] != null){
					$postdata[$choices_skugroup_str] = implode(', ',$returnInputs[$choices_skugroup_str]);
				}
			}
			$postdata['menu_types_id'] = $returnInputs['menu_type'];
			$postdata['menu_price_dine'] = $returnInputs['price_dine_in'];
			$postdata['menu_price_dlv'] = $price_delivery;
			$postdata['menu_price_take'] = $price_take_out;
			$postdata['original_concept'] = $returnInputs['original_concept'];
			$postdata['pos_old_item_description'] = $returnInputs['pos_item_description'];
			$postdata['menu_product_types_name'] = $returnInputs['product_type'];
			$postdata['menu_categories_id'] = $returnInputs['menu_categories'];
			$postdata['menu_subcategories_id'] = $returnInputs['sub_category'];
			$postdata['status'] = $returnInputs['status'];
			$postdata['created_by'] = CRUDBooster::myid();
			$postdata['created_at'] = date('Y-m-d H:i:s');
			// Get store list column name
			if($returnInputs['menu_segment_column_description'] != null){
				foreach($returnInputs['menu_segment_column_description'] as $menu_segments_id){
					$menu_segmentations_column_name = DB::table('menu_segmentations')
						->where('id', $menu_segments_id)
						->select('menu_segment_column_name')
						->value('menu_segment_column_name');
					$postdata[$menu_segmentations_column_name] = 1;
				}
			}


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
			$returnInputs = Input::all();
			$menu = DB::table('menu_items')->where('id',$id)->select('tasteless_menu_code')->first();
			CRUDBooster::redirect(CRUDBooster::mainpath(),'Tasteless item code '.$menu->tasteless_menu_code.' has been added',"success");

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


			$returnInputs = Input::all();
			$row = DB::table('menu_items')->where('id',$id)->get()->toArray();

			$menu_segment_names = [];
			$user_menu_segmentations = DB::table('menu_segmentations')
				->where('status','ACTIVE')
				->select('menu_segment_column_name')
				->get();
			$menu_segments = Arr::pluck($user_menu_segmentations, 'menu_segment_column_name');

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

			// Choices Group
			$choices_group = DB::table('menu_choice_groups')
				->select('id')
				->where('status', 'ACTIVE')
				->get();

			// Add data to database
			$postdata['old_code_1'] = $returnInputs['pos_item_code_1'];
			$postdata['old_code_2'] = $returnInputs['pos_item_code_2'];
			$postdata['old_code_3'] = $returnInputs['pos_item_code_3'];
			$postdata['menu_item_description'] = $returnInputs['menu_item_description'];
			for($i=0; $i<count($choices_group); $i++){
				$choices_group_str = 'choices_group_'.(string)($i+1);
				$choices_skugroup_str = 'choices_skugroup_'.(string)($i+1);
				$postdata[$choices_group_str] = $returnInputs[$choices_group_str];
				if($returnInputs[$choices_skugroup_str] != null){
					$postdata[$choices_skugroup_str] = implode(', ',$returnInputs[$choices_skugroup_str]);
				}else{
					$postdata[$choices_skugroup_str] = null;
				}
			}
			$postdata['menu_types_id'] = $returnInputs['menu_type'];
			$postdata['menu_price_dine'] = $returnInputs['price_dine_in'];
			$postdata['menu_price_dlv'] = $price_delivery;
			$postdata['menu_price_take'] = $price_take_out;
			$postdata['original_concept'] = $returnInputs['original_concept'];
			$postdata['pos_old_item_description'] = $returnInputs['pos_item_description'];
			$postdata['menu_product_types_name'] = $returnInputs['product_type'];
			$postdata['menu_categories_id'] = $returnInputs['menu_categories'];
			$postdata['menu_subcategories_id'] = $returnInputs['sub_category'];
			$postdata['status'] = $returnInputs['status'];
			$postdata['updated_by'] = CRUDBooster::myid();
			// Update Store List
			if($returnInputs['menu_segment_column_description'] != null){
				// Reset Store List
				foreach($menu_segments as $segments){
					$postdata[$segments] = null;
				}
				foreach($returnInputs['menu_segment_column_description'] as $menu_segments_id){
					$menu_segmentations_column_name = DB::table('menu_segmentations')
						->where('id', $menu_segments_id)
						->select('menu_segment_column_name')
						->value('menu_segment_column_name');
					$postdata[$menu_segmentations_column_name] = 1;
					array_push($menu_segment_names, $menu_segmentations_column_name);
				}
			}else{
				foreach($menu_segments as $segments){
					$postdata[$segments] = null;
				}				
			}
		
			
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
			$returnInputs = Input::all();
			$menu = DB::table('menu_items')->where('id',$id)->select('tasteless_menu_code')->first();
			CRUDBooster::redirect(CRUDBooster::mainpath(),'Tasteless item code '.$menu->tasteless_menu_code.' has been edited',"success");

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



	    //By the way, you can still create your own method in here... :) 
		public function getAdd() {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Add Data';
			// Menu Product Types
			$data['menu_product_types'] = DB::table('menu_product_types')
				->where('status','ACTIVE')
				->orderBy('menu_product_type_description')
				->get()->unique('menu_product_type_description');
			// Menu Types
			$data['menu_types'] = DB::table('menu_types')
				->select('id', DB::raw('REPLACE(menu_type_description, "\t", "") as menu_type_description'))
				->where('status', 'ACTIVE')
				->orderBy('menu_type_description')
				->get()->unique('menu_type_description');
			// Menu Categories
			$data['menu_categories'] = DB::table('menu_categories')
				->select('id', DB::raw('REPLACE(category_description, "\t", "") as category_description'))
				->where('status', 'ACTIVE')
				->orderBy('category_description')
				->get()->unique('category_description');
			// Menu Segmentations
			$data['menu_segmentations'] = DB::table('menu_segmentations')
				->where('status','ACTIVE')
				->orderBy('menu_segment_column_description')
				->get()->unique('menu_segment_column_description');
			// Menu Subcategories
			$data['menu_subcategories'] = DB::table('menu_subcategories')
				->select('id', DB::raw('REPLACE(subcategory_description, "\t", "") as subcategory_description'))
				->where('status', 'ACTIVE')
				->orderBy('subcategory_description')
				->get()->unique('subcategory_description');	
			// Menu Group Choices
			$data['menu_choices_group'] = DB::table('menu_choice_groups')
				->where('status', 'ACTIVE')
				->orderBy('menu_choice_group_column_description')
				->get()->unique('menu_choice_group_column_description');
				
			return $this->view('menu-items.add-menu-items',$data);
		}

		public function groupSku(Request $request){

			$results = DB::table('menu_items')
				->select('tasteless_menu_code' ,'menu_item_description')
				->where('status', 'ACTIVE')
				->where('menu_item_description', 'LIKE', '%'. $request->get('q'). '%')
				->orWhere('tasteless_menu_code', 'LIKE', '%'. $request->get('q'). '%')
				->orderBy('menu_item_description')
				->get();
							
			return response()->json($results);

		}

		public function getEdit($id) {
			//Create an Auth
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Edit Data';
			$data['row'] = DB::table('menu_items')->where('id',$id)->first();
			// Menu Product Types
			$data['menu_product_types'] = DB::table('menu_product_types')
				->where('status','ACTIVE')
				->orderBy('menu_product_type_description')
				->get()->unique('menu_product_type_description');
			// Menu Types
			$data['menu_types'] = DB::table('menu_types')
				->select('id', DB::raw('REPLACE(menu_type_description, "\t", "") as menu_type_description'))
				->where('status', 'ACTIVE')
				->orderBy('menu_type_description')
				->get()->unique('menu_type_description');
			// Menu Categories
			$data['menu_categories'] = DB::table('menu_categories')
				->select('id', DB::raw('REPLACE(category_description, "\t", "") as category_description'))
				->where('status', 'ACTIVE')
				->orderBy('category_description')
				->get()->unique('category_description');
			// Menu Subcategories
			$data['menu_subcategories'] = DB::table('menu_subcategories')
				->select('id', DB::raw('REPLACE(subcategory_description, "\t", "") as subcategory_description'))
				->where('status', 'ACTIVE')
				->orderBy('subcategory_description')
				->get()->unique('subcategory_description');	
			// Menu Segmentations
			$data['menu_segmentations'] = DB::table('menu_segmentations')
				->where('status','ACTIVE')
				->orderBy('menu_segment_column_description')
				->get()->unique('menu_segment_column_description');
			// Menu Group Choices
			$data['menu_choices_group'] = DB::table('menu_choice_groups')
				->where('status', 'ACTIVE')
				->orderBy('menu_choice_group_column_description')
				->get()->unique('menu_choice_group_column_description');
			// User Menu Segments
			$user_menu_segmentations = DB::table('menu_segmentations')
				->where('status','ACTIVE')
				->select('menu_segment_column_name')
				->get()->toArray();		
			$menu_segment = Arr::pluck($user_menu_segmentations, 'menu_segment_column_name');
			$data['user_menu_segment'] = [];
			foreach($data['row'] as $key=>$value){
				if(in_array($key,$menu_segment)){
					if($data['row']->$key == 1){
						$data['user_menu_segment'][] = $key;
					}
				}
			}
			
			$data['store_list_id'] = [];
			foreach($data['user_menu_segment'] as $store_list){
				$store_list = DB::table('menu_segmentations')->select('*')
				->where('status', 'ACTIVE')
				->where('menu_segment_column_name', $store_list)
				->get();
				array_push($data['store_list_id'], $store_list[0]->id);
			}
			
			return $this->view('menu-items.edit-menu-items',$data);
		}

		public function getDetail($id) {
			//Create an Auth
			if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Detail Data';
			$data['row'] = DB::table('menu_items')->where('menu_items.id',$id)
				->leftjoin('menu_types', 'menu_items.menu_types_id', '=', 'menu_types.id')
				->leftjoin('menu_categories', 'menu_items.menu_categories_id', '=', 'menu_categories.id')
				->leftjoin('menu_subcategories', 'menu_items.menu_subcategories_id', '=', 'menu_subcategories.id')
				->select('*',
					'menu_types.menu_type_description as menu_type',
					'menu_categories.category_description as main_category',
					'menu_subcategories.subcategory_description as sub_category',
					'menu_items.status as status')
			->first();
			// Menu Segmentations
			$data['menu_segmentations'] = DB::table('menu_segmentations')
				->where('status','ACTIVE')
				->orderBy('menu_segment_column_description')
				->get();
			// Choices Group
			$data['menu_choices_group'] = DB::table('menu_choice_groups')
				->where('status', 'ACTIVE')
				->orderBy('menu_choice_group_column_description')
				->get()->unique('menu_choice_group_column_description');
			// Product Type
			$data['menu_product_types'] = DB::table('menu_product_types')
				->select('menu_product_type_description')
				->where('status','ACTIVE')
				->where('id',$data['row']->menu_product_types_id)
				->value('menu_product_type_description');			
			// User Menu Segments
			$user_menu_segmentations = DB::table('menu_segmentations')
				->where('status','ACTIVE')
				->select('menu_segment_column_name')
				->get()->toArray();		
			$menu_segment = Arr::pluck($user_menu_segmentations, 'menu_segment_column_name');
			$data['user_menu_segment'] = [];
			foreach($data['row'] as $key=>$value){
				if(in_array($key,$menu_segment)){
					if($data['row']->$key == 1){
						$data['user_menu_segment'][] = $key;
					}
				}
			}
			// Menu Group SKU
			$menu_item_value = [];
			$menu_item_key = [];

			foreach($data['menu_choices_group'] as $value){
				$group_name = 'choices_'.'sku'.$value->menu_choice_group_column_name;
				$group_name_val = explode(', ',$data['row']->$group_name);
				$group_column_desc = $value->menu_choice_group_column_description;
				array_push($menu_item_value, $group_name_val);
				array_push($menu_item_key, $group_column_desc);
			}
			
			foreach($menu_item_value as &$value){
				foreach($value as &$id){
					$row_menu_description = DB::table('menu_items')
						->where('tasteless_menu_code', $id)
						->value('menu_item_description');
					
					$id = $row_menu_description;

				}
			}

			$implode_menu_item_value = array_map(function($subArray) {
				return implode(', ', $subArray);
			}, $menu_item_value);

			$data['groups'] = array_combine($menu_item_key, array_values($implode_menu_item_value));
		
			//Please use view method instead view method from laravel
			return $this->view('menu-items.detail-menu-items',$data);
		}

	}