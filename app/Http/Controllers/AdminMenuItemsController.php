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
	use App\Exports\MenuIngredientsExport;
	use App\Imports\MenuItemsImport;
	use App\MenuChoiceGroup;
	use App\MenuOldCodeMaster;
	use App\MenuPriceMaster;
	use App\MenuSegmentation;
	use App\MenuProductType;
	use App\MenuSubcategory;
	use Illuminate\Support\Facades\Request as Input;
    use Maatwebsite\Excel\HeadingRowImport;
    use Maatwebsite\Excel\Imports\HeadingRowFormatter;
	use Maatwebsite\Excel\Facades\Excel;
	Use Alert;
	use Illuminate\Support\Arr;


	class AdminMenuItemsController extends \crocodicstudio\crudbooster\controllers\CBController {
		static $to_view = [
			'Chef' => ['ingredients', 'packagings', 'costing', 'menu-data'],
			'Chef Assistant' => ['ingredients', 'packagings', 'costing', 'menu-data'],
			'Marketing Encoder' => ['packagings', 'costing', 'menu-data'],
			'Marketing Manager' => ['ingredients', 'packagings', 'costing', 'menu-data'],
			'Sales Accounting' => ['costing', 'menu-data'],
			'Accounting - Ingredients' => ['ingredients', 'packagings', 'costing', 'menu-data'],
			'Accounting Manager' => ['ingredients', 'packagings', 'costing', 'menu-data'],
		];

		static $to_edit = [
			'Chef' => ['ingredients'],
			'Chef Assistant' => ['ingredients'],
			'Marketing Encoder' => ['packagings', 'costing', 'menu-data'],
		];

		static $to_update_menu = [
			'Purchasing',
			'Encoder (Menu Item)',
			'Purchasing Encoder',
			'Purchasing Manager',
			'Marketing Encoder',
			'Marketing Manager',
		];

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "tasteless_menu_code";
			$this->limit = "20";
			$this->orderby = "tasteless_menu_code,desc";
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
			$this->table = "menu_items";
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
			$this->col[] = ["label"=>"Menu Product Type","name"=>"menu_product_types_id","join"=>"menu_product_types,menu_product_type_description"];
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
			if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave'])){
			    $this->form[] = ['label'=>'Tasteless Menu Code','name'=>'tasteless_menu_code','type'=>'text','readonly'=>true,'width'=>'col-sm-4'];
			}
			$this->form[] = ['label'=>'Menu Item Description','name'=>'menu_item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Menu Category','name'=>'menu_categories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'menu_categories,category_description','datatable_where'=>"status='ACTIVE'"];
			// $this->form[] = ['label'=>'Menu Subcategory','name'=>'menu_subcategories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'menu_subcategories,subcategory_description','datatable_where'=>"status='ACTIVE'"];
			// $this->form[] = ['label'=>'Tax Code','name'=>'tax_codes_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-4','datatable'=>'tax_codes,tax_code','datatable_where'=>"status='ACTIVE'"];
			// $this->form[] = ['label'=>'Menu Cost Price','name'=>'menu_cost_price','type'=>'number','validation'=>'required|min:0','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Menu Selling Price','name'=>'menu_selling_price','type'=>'number','validation'=>'required|min:0','width'=>'col-sm-4'];
			if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave','getDetail'])){
				$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'ACTIVE;INACTIVE'];
			}
			// if(in_array(CRUDBooster::getCurrentMethod(),['getDetail'])){
			//     foreach($segmentation_data as $datas){
			// 		$this->form[] = ['label'=>'Segmentation'." ".$datas->menu_segment_column_description,'name'=>$datas->menu_segment_column_name,'type'=>'checkbox-custom','width'=>'col-sm-4'];
			// 	}
			// }else{
			// 	$this->form[] = ['label'=>'Segmentation','name'=>'segmentation','type'=>'checkbox-menu','width'=>'col-sm-6',
			// 		'datatable'=>'menu_segmentations,menu_segment_column_description,menu_segment_column_name','datatable_where'=>"status='ACTIVE'"];
			// }
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
			$privilege = CRUDBooster::myPrivilegeName(); 

			if (CRUDBooster::isRead()) {
				$this->addaction[] = [
					'title'=>'Detail',
					'url' => '#[id]',
					'icon'=>'fa fa-eye',
					'color' => ' view-menu-details'
				];
			}

			if (CRUDBooster::isUpdate()) {
				$this->addaction[] = [
					'title'=>'Edit',
					'url' => '#[id]',
					'icon'=>'fa fa-pencil',
					'color' => ' edit-menu-item',
					'showIf' => '[tasteless_menu_code] != null'
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
	        $this->alert = array();
	                

	        
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
            if(CRUDBooster::getCurrentMethod() == 'getIndex') {
				if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), self::$to_update_menu)) {
					$this->index_button[] = [
						"title"=>"Add Non-Trade Item",
                        "label"=>"Add Non-Trade Item",
                        "icon"=>"fa fa-plus",
                        "color"=>"success",
                        "url"=>route('add_non_trade_item')
					];
				}
				// if (strtolower(CRUDBooster::myName()) == 'fillinor gunio') {
					
				// 	if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), self::$to_update_menu)){
				// 		$this->index_button[] = [
				// 			"title"=>"Upload New Menu Items",
				// 			"label"=>"Upload New Menu Items",
				// 			"icon"=>"fa fa-upload",
				// 			"color"=>"success",
				// 			"url"=>route('menu-items.view')];
				// 	}
				// 	if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), self::$to_update_menu)){
				// 		$this->index_button[] = [
				// 			"title"=>"Update Menu Items",
				// 			"label"=>"Update Menu Items",
				// 			"icon"=>"fa fa-upload",
				// 			"color"=>"success",
				// 			"url"=>route('menu-items.update-view')];
				// 	}
				// }
				$this->index_button[] = [
					'label'=>'Export Menu Items',
					'url'=>"javascript:showMenuItemExport()",
					'icon'=>'fa fa-download'
				];

				if (CRUDBooster::isSuperAdmin() || CRUDBooster::isCreate() || CRUDBooster::isUpdate() || in_array('ingredients', self::$to_view[CRUDBooster::myPrivilegeName()] ?? [])) {
					$this->index_button[] = [
						'label'=>'Export Menu Ingredients',
						'url'=>"javascript:menuIngredientsExport()",
						'icon'=>'fa fa-download'
					];
				}
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
			$main_path = CRUDBooster::mainPath();
			$admin_path = CRUDBooster::adminPath();
	        $this->script_js = "
			function showMenuItemExport() {
				$('#modal-menu-item-export').modal('show');
			}
			  
			function menuIngredientsExport() {
				$('#modal-menu-ingredients-export').modal('show');
			}

			$('.user-footer .pull-right a').on('click', function () {
				const currentMainPath = window.location.origin;
				Swal.fire({
					title: 'Do you want to logout?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#b9b9b9',
					confirmButtonText: 'Logout',
					reverseButtons: true,
				}).then((result) => {
					if (result.isConfirmed) {
						location.assign(`$admin_path/logout`);
					}
				});
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
	        $this->post_index_html = "
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-menu-item-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Menu Items</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("export").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export ".CRUDBooster::getCurrentModule()->name ." - ".date('Y-m-d H:i:s')."'/>
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
			" . 
			"
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-menu-ingredients-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Menu Ingredients</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("export-menu-ingredients").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Menu Ingredients " ." - ".date('Y-m-d H:i:s')."'/>
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
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
			$this->load_js[] = '//cdn.jsdelivr.net/npm/sweetalert2@11';
			$this->load_js[] = asset('js/menu-items-action-buttons.js');
	        
	        
	        
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
			$this->load_css[] = asset('css/custom.css');
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | button selected
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
			// if (CRUDBooster::myPrivilegeName() == 'Chef' || CRUDBooster::myPrivilegeName() == 'Chef Assistant') {
			// 	$menu_ids = self::getMyMenuIds();
			// 	$query->whereIn('menu_items.id', $menu_ids);
			// }

			$query->whereNotNull('menu_items.tasteless_menu_code');
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here

			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')
				->orderBy('menu_old_code_column_description','ASC')
				->get();
			
			$old_item_codes_count = count($old_item_codes);

			if ($column_index == 12 + $old_item_codes_count) {
				if ($column_value) $column_value = (float) $column_value;
			}

			if ($column_index == 13 + $old_item_codes_count) {
				if ($column_value) $column_value = (float) $column_value . '%';
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
	        //Your code here.
	        
	        $sku_legend = 			Input::all();
			$segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();

			foreach($segmentation_datas as $segment){
				$segment_search = $sku_legend[$segment->segment_column_name];
				$postdata[$segment->segment_column_name] = $segment_search;
			}
			
			
			$postdata["encoder_privilege_id"]		=	CRUDBooster::myPrivilegeId();
            $postdata["created_by"]					=	CRUDBooster::myId();
			$postdata["action_type"]				=	"Create";
			$postdata['approval_status']			= 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->orWhere('approver_privilege_id', CRUDBooster::myPrivilegeId())->value('current_state');
	
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        

			$menuitem_details = MenuItem::where('id',$id)->get()->toArray();
			MenuItemApproval::insert($menuitem_details);
			
			$for_approval = MenuItemApproval::where('id',$id)->first();
			$approvers = ApprovalWorkflowSetting::where('status','ACTIVE')->where('action_type', 'Create')
							->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->get();

			foreach ($approvers as $approvers_list){
				$approver_privilege_for =	DB::table('cms_privileges')->where('id',$approvers_list->encoder_privilege_id)->first();
				$approver_privilege =		DB::table('cms_privileges')->where('id',$approvers_list->approver_privilege_id)->first();	
			
				if($for_approval->encoder_privilege_id == $approver_privilege_for->id){
					$send_to =	DB::table('cms_users')->where('id_cms_privileges',$approver_privilege->id)->get();
					foreach ($send_to as $send_now){
						$config['content'] = CRUDBooster::myName(). " has created Menu Item with Menu Item Description".$for_approval->menu_item_description." at Menu Item Module!";
						$config['to'] = CRUDBooster::adminPath('menu_item_approvals?q='.$for_approval->card_id);
						$config['id_cms_users'] = [$send_now->id];
						CRUDBooster::sendNotification($config);	
					}
				}
				
			}
			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been created and pending for approval.","info");
			
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
	        $sku_legend = Input::all();
			$segmentation_datas = DB::table('segmentations')->where('status','ACTIVE')->get();

			foreach($segmentation_datas as $segment){
				$segment_search = $sku_legend[$segment->segment_column_name];
				$postdata[$segment->segment_column_name] = $segment_search;
			}
			
			//$postdata["encoder_privilege_id"]		=	CRUDBooster::myPrivilegeId();
			$postdata["action_type"]				=	"Update";
			$postdata['approval_status']			= 	ApprovalWorkflowSetting::where('workflow_number', 1)->where('action_type', 'Create')->where('cms_moduls_id', 'LIKE', '%' . CRUDBooster::getCurrentModule()->id . '%')->where('encoder_privilege_id', CRUDBooster::myPrivilegeId())->orWhere('approver_privilege_id', CRUDBooster::myPrivilegeId())->value('next_state');
			$postdata["updated_by"]                 =   CRUDBooster::myId();
	    
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
	        $item_info = MenuItem::where('id',$id)->first();
	        
			$data_menu = [
				'tasteless_menu_code' 			=> $item_info['tasteless_menu_code'],
				'menu_item_description' 		=> $item_info['menu_item_description'],
				'menu_categories_id' 			=> $item_info['menu_categories_id'],
				'menu_subcategories_id' 		=> $item_info['menu_subcategories_id'],
				'tax_codes_id' 				    => $item_info['tax_codes_id'],
				'status' 				        => $item_info['status'],
				'menu_cost_price' 				=> $item_info['menu_cost_price'],
				'menu_selling_price' 			=> $item_info['menu_selling_price'],
				'segmentation' 					=> $item_info['segmentation'],
				'approval_status'				=> $item_info['approval_status'],
				'action_type'				    => $item_info['action_type'],
				'updated_by' 					=> $item_info['updated_by'],
				'updated_at' 					=> $item_info['updated_at']
			];

			$data_segment = [];

            MenuItemApproval::where('id',$item_info['id'])->update(array_merge($data_menu,$data_menu));
			
			CRUDBooster::redirect(CRUDBooster::mainpath(),"Your item has been edited and pending for approval.","info");
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

	    
	    public function uploadView(){
	        if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }
            
            $data = [];
            $data['page_title'] = 'Upload Menu Items';
            $data['uploadRoute'] = route('menu-items.upload');
            $data['uploadTemplate'] = route('menu-items.template');
			$data['uploadAction'] = 'create';
            return view('menu-items.upload-items',$data);
	    }

		public function uploadUpdateView(){
	        if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }
            
            $data = [];
            $data['page_title'] = 'Update Menu Items';
            $data['uploadRoute'] = route('menu-items.upload');
            $data['uploadTemplate'] = route('menu-items.update-template');
			$data['uploadAction'] = 'update';
            return view('menu-items.upload-items',$data);
	    }
	    
	    public function uploadTemplate(){

			$header = array();
			$segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
			$prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
			$group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();

			
			foreach($old_item_codes as $old_codes){
				array_push($header,$old_codes->menu_old_code_column_description);
			}

			array_push($header,'POS OLD DESCRIPTION');
			array_push($header,'MENU DESCRIPTION');
			array_push($header,'PRODUCT TYPE');

			foreach($group_choices as $choice){
				array_push($header,$choice->menu_choice_group_column_description);
				array_push($header,$choice->menu_choice_group_column_description.' SKU');
			}
			
			array_push($header,'MENU TYPE');
			array_push($header,'MAIN CATEGORY');
			array_push($header,'SUB CATEGORY');

			foreach($prices as $price){
				array_push($header,$price->menu_price_column_description);
			}
			
			array_push($header,'ORIGINAL CONCEPT');
			array_push($header,'STATUS');

			foreach($segmentations as $segment){
				array_push($header,$segment->menu_segment_column_description);
			}
			
			$export = new ExcelTemplate([$header]);
            return Excel::download($export, 'menu-items-'.date("Ymd").'-'.date("h.i.sa").'.csv');
	    }

		public function uploadUpdateTemplate(){

			$header = array('MENU CODE');
			$segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
			$prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
			$group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();

			
			foreach($old_item_codes as $old_codes){
				array_push($header,$old_codes->menu_old_code_column_description);
			}

			array_push($header,'POS OLD DESCRIPTION');
			array_push($header,'MENU DESCRIPTION');
			array_push($header,'PRODUCT TYPE');

			foreach($group_choices as $choice){
				array_push($header,$choice->menu_choice_group_column_description);
				array_push($header,$choice->menu_choice_group_column_description.' SKU');
			}
			
			array_push($header,'MENU TYPE');
			array_push($header,'MAIN CATEGORY');
			array_push($header,'SUB CATEGORY');

			foreach($prices as $price){
				array_push($header,$price->menu_price_column_description);
			}
			
			array_push($header,'ORIGINAL CONCEPT');
			array_push($header,'STATUS');

			foreach($segmentations as $segment){
				array_push($header,$segment->menu_segment_column_description);
			}
			
			$export = new ExcelTemplate([$header]);
            return Excel::download($export, 'menu-items-'.date("Ymd").'-'.date("h.i.sa").'.csv');
	    }
	    
	    public function uploadItems(Request $request){
	        set_time_limit(0);
				
			$errors = array();
			$path_excel = $request->file('import_file')->store('temp');
			$path = storage_path('app').'/'.$path_excel;
            HeadingRowFormatter::default('none');
            $headings = (new HeadingRowImport)->toArray($path);
			$excelData = Excel::toArray(new MenuItemsImport, $path);

			if($request->upload_action == 'create'){
				$header = array();
			}
			else{
				$header = array('MENU CODE');
			}
            //check headings
            
			$segmentations =  MenuSegmentation::where('status','ACTIVE')->orderBy('menu_segment_column_description','ASC')->get();
			$old_item_codes = MenuOldCodeMaster::where('status','ACTIVE')->orderBy('menu_old_code_column_description','ASC')->get();
			$prices = MenuPriceMaster::where('status','ACTIVE')->orderBy('menu_price_column_description','ASC')->get();
			$group_choices = MenuChoiceGroup::where('status','ACTIVE')->orderBy('menu_choice_group_column_description','ASC')->get();

			
			foreach($old_item_codes as $old_codes){
				array_push($header,$old_codes->menu_old_code_column_description);
			}

			array_push($header,'POS OLD DESCRIPTION');
			array_push($header,'MENU DESCRIPTION');
			array_push($header,'PRODUCT TYPE');

			foreach($group_choices as $choice){
				array_push($header,$choice->menu_choice_group_column_description);
				array_push($header,$choice->menu_choice_group_column_description.' SKU');
			}
			
			array_push($header,'MENU TYPE');
			array_push($header,'MAIN CATEGORY');
			array_push($header,'SUB CATEGORY');

			foreach($prices as $price){
				array_push($header,$price->menu_price_column_description);
			}
			
			array_push($header,'ORIGINAL CONCEPT');
			array_push($header,'STATUS');

			foreach($segmentations as $segment){
				array_push($header,$segment->menu_segment_column_description);
			}

			for ($i=0; $i < sizeof($headings[0][0]); $i++) {
				if (!in_array($headings[0][0][$i], $header)) {
					$unMatch[] = $headings[0][0][$i];
				}
			}

			if(!empty($unMatch)) {
                return redirect()->back()->with(['message_type' => 'danger', 'message' => 'Failed ! Please check template headers, mismatched detected.']);
			}

			if($request->upload_action == 'update'){

				$items = array_unique(array_column($excelData[0], "menu_code"));
				$uploaded_items = array_column($excelData[0], "menu_code");

				if(count((array)$uploaded_items) != count((array)$items)){
					array_push($errors, 'duplicate item found!');
				}

				foreach ($items as $key => $value) {
					$itemExist = MenuItem::where('menu_code',$value)->first();

					if(!is_null($itemExist)){
						array_push($errors, 'no item found!');
					}
				}
			}

			if(!empty($errors)){
				return redirect('admin/menu_items')->with(['message_type' => 'danger', 'message' => 'Failed ! Please check '.implode(", ",$errors)]);
			}

            HeadingRowFormatter::default('slug');
			Excel::import(new MenuItemsImport, $path);
			return redirect('admin/menu_items')->with(['message_type' => 'success', 'message' => 'Upload complete!']);
	    }
	    
	    public function exportItems(Request $request){
			
		   $filename = $request->input('filename');
		   return Excel::download(new MenuItemsExport, $filename.'.xlsx');
	    }

		public function exportMenuIngredients(Request $request) {
			$filename = $request->input('filename');
			return Excel::download(new MenuIngredientsExport, $filename.'.xlsx');
		}

		public function addNonTradeItem() {
			if (!CRUDBooster::isCreate()) {
				CRUDBooster::redirect(CRUDBooster::mainPath(), trans('crudbooster.denied_access'));
			}

			$data = [];
			$data['page_title'] = 'Add Non Trade Items';
			$data['menu_type'] = DB::table('menu_types')
				->select('id', 'menu_type_description')
				->where('status', 'ACTIVE')
				->where('menu_type_description', 'OTHERS')
				->first();

			$data['menu_categories'] = DB::table('menu_categories')
				->select('id', 'category_description')
				->where('status', 'ACTIVE')
				->orderBy('category_description', 'ASC')
				->get()
				->toArray();

			$data['concepts'] = DB::table('segmentations')
				->select('id', 'segment_column_description')
				->where('status', 'ACTIVE')
				->orderBy('segment_column_description')
				->get()
				->toArray();

			$data['menu_segmentations'] = DB::table('menu_segmentations')
				->select('menu_segment_column_name', 'menu_segment_column_description')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			return $this->view('menu-items/add-non-trade-item', $data);

		}

		public function submitNonTradeItem(Request $request) {
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$menu_item_description = $request->get('menu_item_description');
			$product_type = MenuProductType::firstOrCreate([
				'menu_product_type_description' => strtoupper($request->get('menu_product_type'))
			]);
			$menu_subcategory = MenuSubcategory::firstOrCreate([
				'subcategory_description' => strtoupper($request->get('menu_subcategory'))
			]);
			$original_concept_ids = implode(',', $request->get('original_concept'));
			$original_concept_name = DB::table('segmentations')
				->whereIn('id', $request->get('original_concept'))
				->pluck('segment_column_description')
				->toArray();

			$tasteless_menu_code = (int) DB::table('menu_items')
				->where('tasteless_menu_code','like',"6%")
				->select('tasteless_menu_code')
				->max('tasteless_menu_code') + 1;

			$data = [
				'tasteless_menu_code' => $tasteless_menu_code,
				'menu_item_description' => $menu_item_description,
				'menu_product_types_name' => $request->get('menu_product_type'),
				'menu_product_types_id' => $product_type->id,
				'menu_types_id' => $request->get('menu_types_id'),
				'menu_categories_id' => $request->get('menu_categories_id'),
				'menu_subcategories_id' => $menu_subcategory->id,
				'menu_price_dine' => $request->get('menu_price_dine'),
				'menu_price_take' => $request->get('menu_price_take'),
				'menu_price_dlv' => $request->get('menu_price_dlv'),
				'original_concept' => implode(',', $original_concept_name),
				'segmentations_id' => $original_concept_ids,
				'created_at' => $time_stamp,
				'created_by' => $action_by,
			];

			foreach ($request->get('segmentations') as $key => $segmentation) {
				$data[$segmentation] = '1';
			}
			
			$is_inserted = DB::table('menu_items')->insert($data);

			if ($is_inserted) {
				return redirect('admin/menu_items')
					->with([
						'message_type' => 'success',
						'message' => "✔️ Non-Trade Item Added: $menu_item_description!"
					]);
			}
			return redirect('admin/menu_items')
				->with([
					'message_type' => 'danger',
					'message' => "Something went wrong, try again!"
				]);
		}

		public function getEdit($id, $to_edit) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];
			$data['item'] = DB::table('menu_items')
				->select('menu_items.id as id',
					'menu_items.tasteless_menu_code',
					'menu_items.menu_price_dine',
					'menu_items.portion_size',
					'menu_items.menu_item_description',
					'menu_items.buffer',
					'menu_items.ideal_food_cost',
					'menu_costing.packaging_cost')
				->where('menu_items.id', $id)
				->leftJoin('menu_costing', 'menu_costing.menu_items_id', 'menu_items.id')
				->first();

			$data['privilege'] = CRUDBooster::myPrivilegeName();

			$data['ingredients'] = DB::table('menu_ingredients_auto_compute')
				->where('menu_items_id', $id)
				->where('menu_ingredients_auto_compute.status', 'ACTIVE')
				->select(\DB::raw('item_masters.id as item_masters_id'),
					'ingredient_name',
					'batching_ingredients_computed_food_cost.ingredient_description',
					'batching_ingredients_computed_food_cost.id as batching_ingredients_id',
					'menu_as_ingredient_id',
					'menu_ingredients_auto_compute.menu_item_description',
					'is_selected',
					'is_primary',
					'is_existing',
					'menu_ingredients_auto_compute.packaging_size',
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
					'menu_ingredients_auto_compute.ttp',
					'menu_ingredients_auto_compute.ttp as ingredient_cost',
					'item_masters.full_item_description',
					'sku_status_description as item_status',
					'menu_items.status as menu_status',
					'new_ingredients.status as new_ingredient_status',
					'batching_ingredients_computed_food_cost.status as batching_ingredient_status',
					'item_masters.updated_at',
					'item_masters.created_at',
					'menu_ingredients_auto_compute.new_ingredients_id',
					'menu_ingredients_auto_compute.item_description')
				->leftJoin('item_masters', 'item_masters.id', '=', 'menu_ingredients_auto_compute.item_masters_id')
				->leftJoin('menu_items', 'menu_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
				->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
				->leftJoin('new_ingredients', 'new_ingredients.id', '=', 'menu_ingredients_auto_compute.new_ingredients_id')
				->leftJoin('batching_ingredients_computed_food_cost', 'batching_ingredients_computed_food_cost.id', 'menu_ingredients_auto_compute.batching_ingredients_id')
				->orderBy('ingredient_group', 'ASC')
				->orderBy('row_id', 'ASC')
				->get()
				->toArray();
	
			$data['packagings'] = DB::table('menu_packagings_auto_compute')
				->where('menu_items_id', $id)
				->where('menu_packagings_auto_compute.status', 'ACTIVE')
				->select(\DB::raw('item_masters.id as item_masters_id'),
					'packaging_name',
					'is_selected',
					'is_primary',
					'is_existing',
					'menu_packagings_auto_compute.packaging_size',
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
					'menu_packagings_auto_compute.ttp',
					'menu_packagings_auto_compute.ttp as packaging_cost',
					'item_masters.full_item_description',
					DB::raw('COALESCE(sku_status_description, new_packagings.status) as item_status'),
					'item_masters.updated_at',
					'item_masters.created_at',
					'menu_packagings_auto_compute.new_packagings_id',
					'menu_packagings_auto_compute.item_description')
				->leftJoin('item_masters', 'item_masters.id', '=', 'menu_packagings_auto_compute.item_masters_id')
				->leftJoin('new_packagings', 'menu_packagings_auto_compute.new_packagings_id', '=', 'new_packagings.id')
				->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
				->orderBy('packaging_group', 'ASC')
				->orderBy('row_id', 'ASC')
				->get()
				->toArray();

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

			$data['food_cost_data'] = DB::table('menu_computed_food_cost')
				->where('id', $id)
				->get()
				->first();

			$data['costing'] = DB::table('menu_costing')
				->where('menu_items_id', $id)
				->first();

			$data['menu_items_data'] = self::getMenuItemDetails($id);

			$my_privilege = CRUDBooster::myPrivilegeName();
			$is_superadmin = CRUDBooster::isSuperAdmin();

			if ($to_edit == 'ingredients') {
				if (!in_array($to_edit, self::$to_edit[$my_privilege] ?? []) && !$is_superadmin)
					CRUDBooster::redirect(
						CRUDBooster::mainPath(),
						trans('crudbooster.denied_access')
					);

				if (!in_array($id, self::getMyMenuIds()) && !$is_superadmin)
					CRUDBooster::redirect(
						CRUDBooster::mainPath(),
						trans('crudbooster.denied_access')
					);

				$data['page_title'] = 'Edit Ingredients';

				return $this->view('menu-items/edit-item', $data);
			} else if ($to_edit == 'packagings') {
				if (!in_array($to_edit, self::$to_edit[$my_privilege] ?? []) && !$is_superadmin)
					CRUDBooster::redirect(
						CRUDBooster::mainPath(),
						trans('crudbooster.denied_access')
					);
				
					$data['page_title'] = 'Edit Packaging';

				return $this->view('menu-items/add-packaging', $data);
			} else if ($to_edit == 'costing') {
				if (!in_array($to_edit, self::$to_edit[$my_privilege] ?? []) && !$is_superadmin)
					CRUDBooster::redirect(
						CRUDBooster::mainPath(),
						trans('crudbooster.denied_access')
					);

				$data['item'] = DB::table('menu_costing')
					->where('menu_items_id', $id)
					->first();

				$data['page_title'] = 'Edit Costing';

				return $this->view('menu-items/edit-costing', $data);
			} else if ($to_edit == 'menu-data') {
				if (!in_array($to_edit, self::$to_edit[$my_privilege] ?? []) && !$is_superadmin)
					CRUDBooster::redirect(
						CRUDBooster::mainPath(),
						trans('crudbooster.denied_access')
					);
				return (new AdminAddMenuItemsController)->getEdit($id);
			}
		}

		public function submitEdit(Request $request) {
			$menu_items_id = $request->input('menu_items_id');
			$ingredients = json_decode($request->input('ingredients'));
			$packagings = json_decode($request->input('packagings'));
			$food_cost = $request->input('food_cost');
			$food_cost_percentage = $request->input('food_cost_percentage');
			$portion_size = $request->input('portion_size');
			$ingredient_total_cost = $request->input('ingredient_total_cost');
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');

			//updating food cost and percentage to menu items table
			DB::table('menu_items')
				->where('id', $menu_items_id)
				->update([
					'food_cost' => $food_cost,
					'food_cost_percentage' => $food_cost_percentage,
					'portion_size' => $portion_size,
					'ingredient_total_cost' => $ingredient_total_cost
				]);

			//inactivating all active ingredients of menu item
			DB::table('menu_ingredients_details')
				->where('status', 'ACTIVE')
				->where('menu_items_id', $menu_items_id)
				->update(['status' => 'INACTIVE',
					'row_id' => null,
					'total_cost' => null,
					'deleted_by' => $action_by,
					'deleted_at' => $time_stamp]);
			
			foreach ($ingredients as $ingredient_group) {
				foreach ($ingredient_group as $ingredient) {
					$ingredient = (array) $ingredient;
					
					//checking if the ingredient already exists
					$is_existing = DB::table('menu_ingredients_details')
						->where([
							'menu_items_id' => $menu_items_id,
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
					$ingredient['deleted_by'] = null;

					//unsetting ingredients details that could be outdated
					unset(
						$ingredient['qty'], 
						$ingredient['cost'], 
						$ingredient['total_cost'],
						$ingredient['ttp'],
					);
	
					//finally, inserting ingredients to menu ingredients details table
					DB::table('menu_ingredients_details')->updateOrInsert([
						'menu_items_id' => $menu_items_id,
						'item_masters_id' => $ingredient['item_masters_id'],
						'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id'],
						'new_ingredients_id' => $ingredient['new_ingredients_id'],
						'batching_ingredients_id' => $ingredient['batching_ingredients_id']
					], $ingredient);
				}
			}

			//calling the function... should start the recursion
			self::updateCostOfOtherMenu();

			return redirect('admin/menu_items')->with(['message_type' => 'success', 'message' => 'Ingredients Updated!']);
		}

		public function submitPackagings(Request $request) {
			$menu_items_id = $request->input('menu_items_id');
			$packagings = json_decode($request->input('packagings'));
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');

			DB::table('menu_packagings_details')
				->where('menu_items_id', $menu_items_id)
				->where('status', 'ACTIVE')
				->update([
					'status' => 'INACTIVE',
					'row_id' => null,
					'deleted_at' => $time_stamp
				]);

			foreach ($packagings as $group) {
				foreach ($group as $packaging) {
					$packaging = (array) $packaging;

					//checking if the packaging already exists
					$is_existing = DB::table('menu_packagings_details')
						->where([
							'menu_items_id' => $menu_items_id,
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
					DB::table('menu_packagings_details')->updateOrInsert([
						'menu_items_id' => $menu_items_id,
						'item_masters_id' => $packaging['item_masters_id'],
						'new_packagings_id' => $packaging['new_packagings_id']
					], $packaging);
						
				}
			}

			//calling the function... should start the recursion
			self::updateCostOfOtherMenu();

			return true;
		}

		public function submitMenuData(Request $request) {
			$returnInputs = Input::all();
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

			// Old Codes
			$old_codes = DB::table('menu_old_code_masters')
				->where('status', 'ACTIVE')
				->pluck('menu_old_code_column_name')
				->toArray();

			// Add data to database
			foreach ($old_codes as $old_code) {
				$data[$old_code] = $returnInputs[$old_code];
			}
			$data['menu_item_description'] = $returnInputs['menu_item_description'];
			for ($i=0; $i<count($choices_group); $i++) {
				$choices_group_str = 'choices_group_'.(string)($i+1);
				$choices_skugroup_str = 'choices_skugroup_'.(string)($i+1);
				$data[$choices_group_str] = $returnInputs[$choices_group_str];
				if ($returnInputs[$choices_skugroup_str] != null){
					$data[$choices_skugroup_str] = implode(', ',$returnInputs[$choices_skugroup_str]);
				} else {
					$data[$choices_skugroup_str] = null;
				}
			}
			$data['menu_types_id'] = $returnInputs['menu_type'];
			$data['pos_old_item_description'] = $returnInputs['pos_item_description'];
			$product_type = MenuProductType::firstOrCreate(['menu_product_type_description' => strtoupper($returnInputs["product_type"])]);
			$data['menu_product_types_id'] = $product_type->id;
			$data['menu_product_types_name'] = $returnInputs['product_type'];
			$data['menu_categories_id'] = $returnInputs['menu_categories'];
			$data['menu_subcategories_id'] = $returnInputs['sub_category'];
			$data['status'] = $returnInputs['status'];
			$original_concept = DB::table('segmentations')
				->whereIn('segmentations.id', $returnInputs['original_concept'])
				->pluck('segmentations.segment_column_description')
				->toArray();
			$data['original_concept'] = implode(',', $original_concept);
			$data['segmentations_id'] = implode(',', $returnInputs['original_concept']);
			$data['updated_by'] = CRUDBooster::myid();
			$data['updated_at'] = date('Y-m-d H:i:s');
			// Update Store List
			if ($returnInputs['menu_segment_column_description'] != null) {
				// Reset Store List
				foreach($menu_segments as $segments) {
					$data[$segments] = null;
				}
				foreach($returnInputs['menu_segment_column_description'] as $menu_segments_id) {
					$menu_segmentations_column_name = DB::table('menu_segmentations')
						->where('id', $menu_segments_id)
						->select('menu_segment_column_name')
						->value('menu_segment_column_name');
					$data[$menu_segmentations_column_name] = 1;
					array_push($menu_segment_names, $menu_segmentations_column_name);
				}
			} else {
				foreach ($menu_segments as $segments) {
					$data[$segments] = null;
				}				
			}

			DB::table('menu_items')
				->where('tasteless_menu_code', $returnInputs['tasteless_menu_code'])
				->update($data);

			return redirect(CRUDBooster::mainPath())->with([
				'message_type' => 'success',
				'message' => 'Menu Item Data successfully updated!',
			]);
		}

		public function submitCosting(Request $request) {
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');
			$menu_items_id = $request->input('menu_items_id');
			$buffer = $request->input('buffer');
			$ideal_food_cost = $request->input('ideal_food_cost');
			$menu_price_dine = $request->input('menu_price_dine');
			$menu_price_take = $request->input('menu_price_take');
			$menu_price_dlv = $request->input('menu_price_dlv');

			DB::table('menu_items')
				->where('id', $menu_items_id)
				->update([
					'buffer' => $buffer,
					'ideal_food_cost' => $ideal_food_cost,
					'menu_price_dine' => $menu_price_dine,
					'menu_price_take' => $menu_price_take,
					'menu_price_dlv' => $menu_price_dlv,
					'updated_by' => $action_by,
					'updated_at' => $time_stamp,
				]);

			//calling the function... should start the recursion
			self::updateCostOfOtherMenu();

			return redirect('admin/menu_items')
				->with([
					'message_type' => 'success',
					'message' => 'Costing Updated!'
				]);
		}

		public function getDetail($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					trans('crudbooster.denied_access')
				);

			$my_privilege = CRUDBooster::myPrivilegeName();
			if (!in_array('ingredients', self::$to_view[$my_privilege] ?? []) && !CRUDBooster::isSuperAdmin())
					CRUDBooster::redirect(
						CRUDBooster::mainPath(),
						trans('crudbooster.denied_access')
					);

			$data = [];
			$data['item'] = DB::table('menu_items')
				->where('menu_items.id', $id)
				->select(
					'menu_items.tasteless_menu_code',
					'menu_items.menu_price_dine',
					'menu_items.menu_item_description',
					'menu_items.portion_size',
					'computed_ingredient_total_cost',
					'computed_food_cost',
					'computed_food_cost_percentage',
					'computed_packaging_total_cost'
				)
				->leftJoin('menu_computed_food_cost', 'menu_computed_food_cost.id', '=', 'menu_items.id')
				->leftJoin('menu_computed_packaging_cost', 'menu_computed_packaging_cost.id', '=', 'menu_items.id')
				->first();

			$data['ingredients'] = self::getIngredients($id);
			$data['packagings'] = self::getPackagings($id);
			$data['page_title'] = 'Detail Ingredient';

			return $this->view('menu-items/detail-item', $data);
		}

		public function getCostingDetails($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					trans('crudbooster.denied_access')
				);
				
			$data = [];

			$item = DB::table('menu_costing')
				->where('menu_costing.menu_items_id', $id)
				->leftJoin('menu_items', 'menu_items.id', '=', 'menu_costing.menu_items_id')
				->get()
				->first();

			$menu_items_data = self::getMenuItemDetails($id);

			$data['item'] = $item;
			$data['menu_items_data'] = self::getMenuItemDetails($id);
			$data['page_title'] = 'Detail Costing';

			return $this->view('menu-items/costing-details', $data);
		}

		public function getPackagingDetail($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					trans('crudbooster.denied_access')
				);

			$my_privilege = CRUDBooster::myPrivilegeName();
			if (!in_array('packagings', self::$to_view[$my_privilege] ?? []) && !CRUDBooster::isSuperAdmin())
				CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					trans('crudbooster.denied_access')
				);
			$data = [];
			
			$data['item'] = DB::table('menu_items')
				->where('menu_items.id', $id)
				->select(
					'menu_items.tasteless_menu_code',
					'menu_items.menu_price_dine',
					'menu_items.menu_item_description',
					'menu_items.portion_size',
					'computed_ingredient_total_cost',
					'computed_food_cost',
					'computed_food_cost_percentage',
					'computed_packaging_total_cost'
				)
				->leftJoin('menu_computed_food_cost', 'menu_computed_food_cost.id', '=', 'menu_items.id')
				->leftJoin('menu_computed_packaging_cost', 'menu_computed_packaging_cost.id', '=', 'menu_items.id')
				->first();

			$data['packagings'] = self::getPackagings($id);
			$data['page_title'] = 'Detail Packaging';
			return $this->view('menu-items/packaging-detail', $data);
			
		}

		public function getMenuDataDetail($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];
			$data['item'] = DB::table('menu_items')
				->where('id', $id)
				->get()
				->first();
			$data['menu_items_data'] = self::getMenuItemDetails($id);
			$data['page_title'] = 'Detail Menu Data';

			return $this->view('menu-items/menu-data-detail', $data);
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
				->leftJoin('menu_product_types', 'menu_product_types.id', '=', 'menu_items.menu_product_types_id')
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

		public function getDetailNotChef($id) {
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

		public function searchIngredient(Request $request) {
			$search_terms = json_decode($request->content);
			
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

			$response = array_merge($item_masters, $menu_items);
			
			return json_encode($response);
		}

		function updateCostOfOtherMenu() {
			$to_update = DB::table('menu_items')
				->join('menu_computed_food_cost', 'menu_items.id', '=', 'menu_computed_food_cost.id')
				->whereRaw("
					CAST(
						COALESCE(
							menu_items.food_cost,
							0
						) AS DECIMAL(18, 4)
					) != CAST(
						COALESCE(
							menu_computed_food_cost.computed_food_cost,
							0
						) AS DECIMAL(18, 4)
					)
				")
				->orWhereRaw("
					CAST(
						COALESCE(
							menu_items.food_cost_percentage,
							0
						) AS DECIMAL(18, 2)
					) != CAST(
						COALESCE(
							menu_computed_food_cost.computed_food_cost_percentage,
							0
						) AS DECIMAL(18, 2)
					)
				")
				->orWhereRaw("
					CAST(
						COALESCE(
							menu_items.ingredient_total_cost,
							0
						) AS DECIMAL(18, 4)
					) != CAST(
						COALESCE(
							menu_computed_food_cost.computed_ingredient_total_cost,
							0
						) AS DECIMAL(18, 4)
					)
				")
				->pluck('menu_items.id')
				->toArray();

			//stopping the recursion if array is empty
			if (!$to_update) return;

			DB::table('menu_items')
				->whereIn('menu_items.id', $to_update)
				->leftJoin('menu_costing', 'menu_items.id', '=', 'menu_costing.menu_items_id')
				->leftJoin('menu_computed_food_cost', 'menu_computed_food_cost.id', '=', 'menu_items.id')
				->update([
					'menu_items.ingredient_total_cost' => DB::raw("menu_computed_food_cost.computed_ingredient_total_cost"),
					'menu_items.food_cost' => DB::raw("menu_costing.final_recipe_cost"),
					'menu_items.food_cost_percentage' => DB::raw("menu_costing.food_cost_percentage"),
				]);

			//finally, calling the function itself
			//the process keeps going on until there are no more ingredients to be updated
			self::updateCostOfOtherMenu();
		}

		function getIngredients($id) {
			$ingredients = DB::table('menu_ingredients_auto_compute')
				->where('menu_items_id', $id)
				->where('menu_ingredients_auto_compute.status', 'ACTIVE')
				->select('tasteless_code',
					DB::raw('COALESCE(
						item_masters.tasteless_code,
						menu_items.tasteless_menu_code,
						batching_ingredients.bi_code,
						new_ingredients.nwi_code
					) AS item_code'),
					'menu_items.status as menu_item_status',
					'sku_statuses.sku_status_description as item_status',
					'new_ingredients.status as new_ingredient_status',
					'batching_ingredients.status as batching_ingredient_status',
					'menu_ingredients_auto_compute.item_masters_id',
					'menu_ingredients_auto_compute.menu_item_description',
					'menu_ingredients_auto_compute.item_description',
					'menu_ingredients_auto_compute.ingredient_description',
					'tasteless_menu_code',
					'ingredient_name',
					'prep_qty',
					'ingredient_group',
					'row_id',
					'is_primary',
					'is_selected',
					'menu_ingredients_auto_compute.packaging_size',
					'menu_ingredients_auto_compute.full_item_description',
					'menu_ingredients_preparations.preparation_desc',
					'ingredient_qty',
					'menu_ingredients_auto_compute.uom_description',
					'menu_ingredients_auto_compute.packaging_description',
					'yield',
					'menu_ingredients_auto_compute.ttp',
					'cost',
					'item_masters.updated_at',
					'item_masters.created_at',
					'menu_ingredients_auto_compute.item_description')
				->leftJoin('item_masters', 'menu_ingredients_auto_compute.item_masters_id', '=', 'item_masters.id')
				->leftJoin('menu_items', 'menu_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
				->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
				->leftJoin('menu_ingredients_preparations', 'menu_ingredients_auto_compute.menu_ingredients_preparations_id', '=', 'menu_ingredients_preparations.id')
				->leftJoin('new_ingredients', 'new_ingredients.id', '=', 'menu_ingredients_auto_compute.new_ingredients_id')
				->leftJoin('batching_ingredients', 'batching_ingredients.id', '=', 'menu_ingredients_auto_compute.batching_ingredients_id')
				->orderby('ingredient_group', 'asc')
				->orderby('row_id', 'asc')
				->get()
				->toArray();

			return $ingredients;
		}

		function getPackagings($id) {
			$packagings = DB::table('menu_packagings_auto_compute')
				->where('menu_items_id', $id)
				->where('menu_packagings_auto_compute.status', 'ACTIVE')
				->select('tasteless_code',
				DB::raw('COALESCE(
					item_masters.tasteless_code,
					new_packagings.nwp_code
				) AS item_code'),
				'sku_statuses.sku_status_description as item_status',
				'new_packagings.status as new_packaging_status',
				'menu_packagings_auto_compute.item_masters_id',
				'packaging_name',
				'prep_qty',
				'packaging_group',
				'row_id',
				'is_primary',
				'is_selected',
				'menu_packagings_auto_compute.packaging_size',
				'menu_packagings_auto_compute.full_item_description',
				'menu_ingredients_preparations.preparation_desc',
				'packaging_qty',
				'menu_packagings_auto_compute.uom_description',
				'menu_packagings_auto_compute.packaging_description',
				'yield',
				'menu_packagings_auto_compute.ttp',
				'cost',
				'item_masters.updated_at',
				'item_masters.created_at',
				'menu_packagings_auto_compute.item_description')
			->leftJoin('item_masters', 'menu_packagings_auto_compute.item_masters_id', '=', 'item_masters.id')
			->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
			->leftJoin('menu_ingredients_preparations', 'menu_packagings_auto_compute.menu_ingredients_preparations_id', '=', 'menu_ingredients_preparations.id')
			->leftJoin('new_packagings', 'new_packagings.id', '=', 'menu_packagings_auto_compute.new_packagings_id')
			->orderby('packaging_group', 'asc')
			->orderby('row_id', 'asc')
			->get()
			->toArray();

			return $packagings;
		}

		public function getMyMenuIds() {
			$ids = (new AdminFoodCostController)
				->getMyMenuAndConcepts()['menu_query']
				->pluck('menu_items.id')
				->toArray();

			return $ids;
		}

	}	