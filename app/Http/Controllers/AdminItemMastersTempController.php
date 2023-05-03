<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\CodeCounter;
	use App\Segmentation;

	class AdminItemMastersTempController extends \crocodicstudio\crudbooster\controllers\CBController {
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->diffData = [];
			$this->segments = Segmentation::where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();
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
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "item_masters_temp";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Creation Status","name"=>"creation_status"];
			$this->col[] = ["label"=>"Tasteless Code","name"=>"item_masters_id","join"=>"item_masters,tasteless_code"];
			$this->col[] = ["label"=>"Item Description","name"=>"item_description"];
			$this->col[] = ["label"=>"Packaging Size","name"=>"packaging_size"];
			$this->col[] = ["label"=>"Uoms Id","name"=>"uoms_id","join"=>"uoms,uom_description"];
			$this->col[] = ["label"=>"Ttp","name"=>"ttp"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created by","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created at","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Creation Status','name'=>'creation_status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Item Description','name'=>'item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Packaging Size','name'=>'packaging_size','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uoms Id','name'=>'uoms_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'uoms,id'];
			$this->form[] = ['label'=>'Ttp','name'=>'ttp','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Creation Status','name'=>'creation_status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Item Description','name'=>'item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Packaging Size','name'=>'packaging_size','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Uoms Id','name'=>'uoms_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'uoms,id'];
			//$this->form[] = ['label'=>'Ttp','name'=>'ttp','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Status','name'=>'status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
	    	$blue_status = [];
			$orange_status = ['FOR ITEM CREATION'];
			$green_status = ['ITEM CREATED'];
			
			if ($column_index == 2) {
				if (in_array($column_value, $blue_status)) {
					$column_value = "<span class='label label-info'>$column_value</span>";
				} else if (in_array($column_value, $orange_status)) {
					$column_value = "<span class='label label-warning'>$column_value</span>";
				} else if (in_array($column_value, $green_status)) {
					$column_value = "<span class='label label-success'>$column_value</span>";
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



	    //By the way, you can still create your own method in here... :) 
		public function getDetail($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$item_masters_id = DB::table('item_masters_temp')
				->where('id', $id)
				->get()
				->first()
				->item_masters_id;

			if ($item_masters_id) {
				return (new AdminItemMastersController)->getDetail($item_masters_id);
			} else {
				return parent::getDetail($id);
			}
		}

		public function getEdit($id) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];

			$data['item'] = DB::table('item_masters_temp')
				->where('id', $id)
				->get()
				->first();

			$data['tax_codes'] = DB::table('tax_codes')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['accounts'] = DB::table('accounts')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['cogs_accounts'] = DB::table('cogs_accounts')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['asset_accounts'] = DB::table('asset_accounts')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['fulfillment_types'] = DB::table('fulfillment_methods')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['uoms'] = DB::table('uoms')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['uoms_set'] = DB::table('uoms_set')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['currencies'] = DB::table('currencies')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['groups'] = DB::table('groups')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['categories'] = DB::table('categories')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['subcategories'] = DB::table('subcategories')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['segmentations'] = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->get()
				->toArray();


			return $this->view('item-master-temp/edit-item', $data);
		}

		public function saveNewItem(Request $request) {
			$item_data = (array) json_decode($request->get('item_data'));
			$segmentation = (array) json_decode($request->get('segmentation'));
			$item_masters_temp_id = $request->get('item_masters_temp_id');
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');
			$group = DB::table('groups')
				->where('id', $item_data['groups_id'])
				->get()
				->first();

			$item_data['tasteless_code'] = self::getTastelessCode($group);
			$item_data["encoder_privilege_id"] = CRUDBooster::myPrivilegeId();
			$item_data["created_by"] = $action_by;
			$item_data["created_at"] = $time_stamp;
			$item_data["action_type"] = "Create";

			foreach ($segmentation as $column_name => $value) {
				$item_data[$column_name] = $value;
			}

			$inserted_id = DB::table('item_masters')
				->insertgetId($item_data);

			DB::table('item_masters_temp')
				->where('id', $item_masters_temp_id)
				->update([
					'item_masters_id' => $inserted_id,
					'updated_by' => $action_by,
					'updated_at' => $time_stamp,
					'creation_status' => 'ITEM CREATED',
				]);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => '✔️ New Item Created: ' . $item_data['full_item_description']
				]);
			
		} 

		public function searchData(Request $request) {
			$term =  $request->get('term')['term'];
			$id = $request->get('id');
			$to_search = $request->get('to_search');
			$response = null;
			if ($to_search == 'brands') {
				$response = DB::table('brands')
					->where('status', 'ACTIVE')
					->where('brand_description', 'LIKE', '%'. $term . '%')
					->get()
					->toArray();
			} else if ($to_search == 'preferred_vendors') {
				$response = DB::table('suppliers')
					->where('last_name', 'LIKE', '%'. $term . '%')
					->get()
					->toArray();
			} else if ($to_search == 'subcategories') {
				$response = DB::table('subcategories')
					->where('categories_id', $id)
					->where('status', 'ACTIVE')
					->get()
					->toArray();
			}

			return json_encode($response);
		}

		function getTastelessCode($group) {
			$tastless_code = 0;
			$code_column = '';


			if (substr($group->group_description, 0, 4) == 'FOOD' || 
				substr($group->group_description, 0, 4) == 'food') {
				$code_column = "code_1";

				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);
					
			} else if ($group->group_description == 'BEVERAGE' || 
				$group->group_description == 'beverage') {
				$code_column = "code_2";

				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);
			} else if ($group->group_description == 'FINISHED GOODS' || 
				$group->group_description == 'finished goods') {
				$code_column = "code_1";

				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);
					
			} else if (substr($group->group_description, -8) == 'SUPPLIES' || 
				substr($group->group_description, -8) == 'supplies') {
				$code_column = "code_3";

				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);
			} else if ($group->group_description == 'CAPEX' || 
				$group->group_description == 'capex') {					
				$code_column = "code_5";

				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);
			} else if ($group->group_description == 'COMPLIMENTARY' || 
				$group->group_description == 'complimentary') {
				$code_column = "code_7";

				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);
			} else if (substr($group->group_description, -4) == 'FEES' || 
				substr($group->group_description, -4) == 'fees') {
				$code_column = "code_4";

				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);
			} else {
				$code_column = "code_6";
				
				$tasteless_code = CodeCounter::where('id', 1)
					->where('type', 'ITEM MASTER')
					->value($code_column);;
			}
			CodeCounter::where('type', 'ITEM MASTER')->where('id', 1)->increment($code_column);
			return $tasteless_code;
		}


	}