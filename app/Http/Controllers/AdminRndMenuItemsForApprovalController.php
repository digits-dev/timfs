<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Request as Input;

	class AdminRndMenuItemsForApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");

			$this->mainController = new AdminRndMenuItemsController;
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
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "rnd_menu_items";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Approval Status","name"=>"id","join"=>"rnd_menu_approvals,approval_status","join_id"=>"rnd_menu_items_id"];
			$this->col[] = ["label"=>"RND Code","name"=>"rnd_code"];
			$this->col[] = ["label"=>"Concept","name"=>"segmentations_id","join"=>"segmentations,segment_column_description"];
			$this->col[] = ["label"=>"Rnd Menu Description","name"=>"rnd_menu_description"];
			$this->col[] = ["label"=>"SRP","name"=>"rnd_menu_srp"];
			$this->col[] = ["label"=>"Portion Size","name"=>"portion_size"];
			$this->col[] = ["label"=>"Published By","name"=>"rnd_menu_approvals.published_by","join"=>"cms_users,name","join_id"=>"id"];
			$this->col[] = ["label"=>"Published Date","name"=>"rnd_menu_approvals.published_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Rnd Menu Description','name'=>'rnd_menu_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Rnd Code','name'=>'rnd_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Rnd Tasteless Code','name'=>'rnd_tasteless_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Portion Size','name'=>'portion_size','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Rnd Menu Srp','name'=>'rnd_menu_srp','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Rnd Menu Description","name"=>"rnd_menu_description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Rnd Code","name"=>"rnd_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Rnd Tasteless Code","name"=>"rnd_tasteless_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Portion Size","name"=>"portion_size","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Rnd Menu Srp","name"=>"rnd_menu_srp","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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
			
			$privilege = CRUDBooster::myPrivilegeName();

			if (CRUDBooster::isSuperAdmin() || $privilege == 'Marketing Encoder') {
				$this->addaction[] = [
					'title'=>'Edit',
					'url'=>CRUDBooster::mainpath('edit/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => ' ',
					"showIf"=>"
						[approval_status] == 'FOR PACKAGING' ||
						[approval_status] == 'FOR MENU CREATION' ||
						[approval_status] == 'FOR COSTING'
					"
				];
			}

			if (CRUDBooster::isSuperAdmin() || $privilege == 'Marketing Manager') {
				$this->addaction[] = [
					'title'=>'Edit',
					'url'=>CRUDBooster::mainpath('edit/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => ' ',
					"showIf"=>"
						[approval_status] == 'FOR APPROVAL (MARKETING)'
					"
				];
			}
			
			if (CRUDBooster::isSuperAdmin() || $privilege == 'Accounting Manager' || $privilege == 'Accounting - Ingredients') {
				$this->addaction[] = [
					'title'=>'Edit',
					'url'=>CRUDBooster::mainpath('edit/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => ' ',
					"showIf"=>"
						[approval_status] == 'FOR APPROVAL (ACCOUNTING)'
					"
				];
			}

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
			$valid_approval_statuses = [
				'FOR FOOD TASTING',
				'FOR PACKAGING',
				'FOR MENU CREATION',
				'FOR COSTING',
				'FOR APPROVAL (MARKETING)',
				'FOR APPROVAL (ACCOUNTING)',
			];

			$upperCasedPrivilege = strtoupper(CRUDBooster::myPrivilegeName());

			$privileges = [
				'CHEF' => [],
				'MARKETING ENCODER' => ['FOR PACKAGING', 'FOR MENU CREATION', 'FOR COSTING'],
				'MARKETING APPROVER' => ['FOR FOOD TASTING', 'FOR APPROVAL (MARKETING)'],
				'MARKETING MANAGER' => ['FOR FOOD TASTING', 'FOR APPROVAL (MARKETING)'],
				'ACCOUNTING APPROVER' => ['FOR APPROVAL (ACCOUNTING)'],
				'ACCOUNTING MANAGER' => ['FOR APPROVAL (ACCOUNTING)'],
				'ACCOUNTING - INGREDIENTS' => ['FOR APPROVAL (ACCOUNTING)'],
				'PURCHASING STAFF' => [],
			];

			$query
				->addSelect('rnd_menu_approvals.approval_status as approval_status')
				->whereIn('rnd_menu_approvals.approval_status', $valid_approval_statuses);
			
			if (!CRUDBooster::isSuperAdmin()) {
				$query->whereIn('rnd_menu_approvals.approval_status', ($privileges[$upperCasedPrivilege] ?? []));
			}
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	       
			if (is_numeric($column_value)) $column_value = (float) $column_value;

			$blue_status = ['SAVED', 'FOR COSTING'];
			$dark_blue_status = ['FOR FOOD TASTING'];
			$orange_status = ['FOR PACKAGING', 'FOR MENU CREATION', 'FOR ITEM CREATION'];
			$green_status = ['APPROVED'];
			
			if ($column_index == 2) {
				if (in_array($column_value, $blue_status)) {
					$column_value = "<span class='label label-info'>$column_value</span>";
				} else if (in_array($column_value, $orange_status)) {
					$column_value = "<span class='label label-warning'>$column_value</span>";
				} else if (in_array($column_value, $green_status)) {
					$column_value = "<span class='label label-success'>$column_value</span>";
				} else if (in_array($column_value, $dark_blue_status)) {
					$column_value = "<span class='label label-primary'>$column_value</span>";
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

			$returnInputs = Input::all();  
	    	return $this->mainController->saveNewMenu($returnInputs);

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

			$status = DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $id)
				->first()
				->approval_status;

			if ($status == 'FOR FOOD TASTING') {
				return $this->mainController->getDetailWithIngredient($id);
			} else if ($status == 'FOR APPROVAL (ACCOUNTING)') {
				return $this->mainController->getDetailNoIngredient($id, false);
			}

			return $this->mainController->getDetailNoIngredient($id);
			

		}

		public function getEdit($id) {

			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$upperCasedPrivilege = strtoupper(CRUDBooster::myPrivilegeName());

			$privileges = [
				'CHEF' => [],
				'MARKETING ENCODER' => ['FOR PACKAGING', 'FOR MENU CREATION', 'FOR COSTING'],
				'MARKETING APPROVER' => ['FOR FOOD TASTING', 'FOR APPROVAL (MARKETING)'],
				'MARKETING MANAGER' => ['FOR FOOD TASTING', 'FOR APPROVAL (MARKETING)'],
				'ACCOUNTING APPROVER' => ['FOR APPROVAL (ACCOUNTING)'],
				'ACCOUNTING MANAGER' => ['FOR APPROVAL (ACCOUNTING)'],
				'ACCOUNTING - INGREDIENTS' => ['FOR APPROVAL (ACCOUNTING)'],
				'PURCHASING STAFF' => [],
			];

			$status = DB::table('rnd_menu_approvals')
				->where('rnd_menu_items_id', $id)
				->first()
				->approval_status;

			$is_super_admin = CRUDBooster::isSuperAdmin();

			if (!in_array($status, $privileges[$upperCasedPrivilege] ?? []) && !$is_super_admin) {
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);
			}

			if ($status == 'FOR PACKAGING') {
				return $this->mainController->getSetPackaging($id);
			} else if ($status == 'FOR COSTING') {
				return $this->mainController->getSetCosting($id);
			} else if ($status == 'FOR MENU CREATION') {
				return $this->mainController->getCreateNewMenu($id);
			} else if ($status == 'FOR APPROVAL (MARKETING)') {
				return $this->mainController->getApproveByMarketing($id);
			} else if ($status == 'FOR APPROVAL (ACCOUNTING)') {
				return $this->mainController->getApproveByAccounting($id);
			} else {
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
		}

		public function addNewMenu(Request $request) {
			
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			return $this->mainController->saveNewMenu($request);
		}

		public function editNewMenu(Request $request, $id) {
			return $this->mainController->editNewMenu($request, $id);
		}

		public function addPackaging(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			return $this->mainController->addPackaging($request);
		}

		public function submitCosting(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			return $this->mainController->submitCosting($request);
		}

		public function addComment(Request $request) {
			return $this->mainController->addComment($request);
		}

		public function deleteComment(Request $request) {

			return $this->mainController->deleteComment($request);
		}

		public function approveByMarketing(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			return $this->mainController->approveByMarketing($request);
		}

		public function approveByAccounting(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			return $this->mainController->approveByAccounting($request);
		}

		public function returnRNDMenu(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			return $this->mainController->returnRNDMenu($request);
		}
	}