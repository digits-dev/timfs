<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use App\ItemMaster;
	use App\ItemMasterApproval;
	use App\ApprovalWorkflowSetting;
	use App\Group;
	use App\CodeCounter;
	use App\HistoryLandedCost;
	use App\HistoryPurchasePrice;
	use App\HistoryTtp;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Schema;
	use Intervention\Image\Facades\Image;
	use Spatie\ImageOptimizer\OptimizerChainFactory;

	class AdminItemApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->main_controller = new AdminItemMastersController;
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
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "item_master_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			//----added by cris 20200630
        $this->col[] = ["label" => "Action Type", "name" => "action_type"];
        $this->col[] = ["label" => "Approval Status", "name" => "approval_status"];
        $this->col[] = ["label" => "Tasteless Code", "name" => "tasteless_code"];
        $this->col[] = ["label" => "Type", "name" => "types_id", "join" => "types,type_description", "visible" => CRUDBooster::myColumnView()->type ? true : false];
        $this->col[] = ["label" => "Item", "name" => "item", "visible" =>  true];
        $this->col[] = ["label" => "Description", "name" => "full_item_description", "visible" => CRUDBooster::myColumnView()->full_item_description ? true : false];
        $this->col[] = ["label" => "Tax Code", "name" => "tax_codes_id", "join" => "tax_codes,tax_code", "visible" => CRUDBooster::myColumnView()->tax_code ? true : false];
        $this->col[] = ["label" => "Account", "name" => "accounts_id", "join" => "accounts,group_description", "visible" =>  true];
        $this->col[] = ["label" => "COGS Account", "name" => "cogs_accounts_id", "join" => "cogs_accounts,group_description", "visible" =>  true];
        $this->col[] = ["label" => "Asset Account", "name" => "asset_accounts_id", "join" => "asset_accounts,group_description", "visible" =>  true];
        $this->col[] = ["label" => "Accumulated Depreciation", "name" => "accumulated_depreciation", "visible" =>  true];
        $this->col[] = ["label" => "Purchase Description", "name" => "purchase_description", "visible" =>  true];
        $this->col[] = ["label" => "Quantity On Hand", "name" => "quantity_on_hand", "visible" =>  true];
        $this->col[] = ["label" => "Fulfillment Type", "name" => "fulfillment_type_id", "join" => "fulfillment_methods,fulfillment_method"];
        $this->col[] = ["label" => "UOM", "name" => "uoms_id", "join" => "uoms,uom_description", "visible" => CRUDBooster::myColumnView()->uom ? true : false];
        $this->col[] = ["label" => "UOM Set", "name" => "uoms_set_id", "join" => "uoms_set,uom_description", "visible" =>  true];
        $this->col[] = ["label" => "Cost", "name" => "purchase_price", "visible" => CRUDBooster::myColumnView()->purchase_price ? true : false];
        $this->col[] = ["label" => "TTP", "name" => "ttp", "visible" => CRUDBooster::myColumnView()->ttp ? true : false];
        $this->col[] = ["label" => "Commi Margin", "name" => "ttp_percentage", "visible" => CRUDBooster::myColumnView()->ttp_percentage ? true : false];
        $this->col[] = ["label" => "Landed Cost", "name" => "landed_cost", "visible" => CRUDBooster::myColumnView()->landed_cost ? true : false];
        $this->col[] = ["label" => "Preffered Vendor", "name" => "suppliers_id", "join" => "suppliers,last_name", "visible" => CRUDBooster::myColumnView()->supplier ? true : false];
        $this->col[] = ["label" => "Tax Agency", "name" => "tax_agency", "visible" =>  true];
        $this->col[] = ["label" => "Reorder Pt (Min)", "name" => "reorder_pt", "visible" =>  true];
        $this->col[] = ["label" => "MPN", "name" => "mpn", "visible" =>  true];
        $this->col[] = ["label" => "Group", "name" => "groups_id", "join" => "groups,group_description", "visible" => CRUDBooster::myColumnView()->group ? true : false];
        $this->col[] = ["label" => "Category Description", "name" => "categories_id", "join" => "categories,category_description", "visible" => CRUDBooster::myColumnView()->category_description ? true : false];
        $this->col[] = ["label" => "Subcategory Description", "name" => "subcategories_id", "join" => "subcategories,subcategory_description", "visible" => CRUDBooster::myColumnView()->subcategory ? true : false];
        $this->col[] = ["label" => "Dimension", "name" => "packaging_dimension", "visible" => CRUDBooster::myColumnView()->packaging_dimension ? true : false];
        $this->col[] = ["label" => "Packaging Size", "name" => "packaging_size", "visible" => CRUDBooster::myColumnView()->packaging_size ? true : false];
        $this->col[] = ["label" => "Packaging UOM", "name" => "packagings_id", "join" => "packagings,packaging_description", "visible" => CRUDBooster::myColumnView()->packaging ? true : false];
        $this->col[] = ["label" => "Tax Status", "name" => "tax_codes_id", "join" => "tax_codes,tax_code", "visible" => CRUDBooster::myColumnView()->tax_code ? true : false];
        // $this->col[] = ["label" => "Price", "name" => "price", "visible" =>  true];
        $this->col[] = ["label" => "Supplier Item Code", "name" => "supplier_item_code", "visible" => CRUDBooster::myColumnView()->supplier_item_code ? true : false];
        $this->col[] = ["label" => "MOQ Store", "name" => "moq_store", "visible" => CRUDBooster::myColumnView()->moq_store ? true : false];
        $this->col[] = ["label" => "Account Number", "name" => "chart_accounts_id", "join" => "chart_accounts,account_number", "visible" => CRUDBooster::myColumnView()->chart_accounts ? true : false];
        $this->col[] = ["label" => "Created Date", "name" => "created_at", "visible" => CRUDBooster::myColumnView()->create_date ? true : false];
        $this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->create_by ? true : false];
        $this->col[] = ["label" => "Updated Date", "name" => "updated_at", "visible" => CRUDBooster::myColumnView()->update_date ? true : false];
        $this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->update_date ? true : false];
        //--------------------------

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        //----added by cris 20200630
		$this->form[] = ['label' => 'Tasteless Code', 'name' => 'tasteless_code', 'type' => 'text', 'readonly' => true, 'width' => 'col-sm-4'];
		$this->form[] = [
			'label' => 'Type', 'name' => 'types_id', 'type' => 'select2',
			'validation' => CRUDBooster::myAddForm()->type ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'types,type_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->type ?: 'display:none;'
		];


		$this->form[] = [
			'label' => 'Item', 'name' => 'item', 'type' => 'text',
			'validation' => 'required', 'width' => 'col-sm-4'
		];

		$this->form[] = [
			'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text',
			'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2',
			'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'tax_codes,tax_code', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Account', 'name' => 'accounts_id', 'type' => 'select2',
			'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
			'datatable' => 'accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'COGS Account', 'name' => 'cogs_accounts_id', 'type' => 'select2',
			'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'cogs_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Asset Account', 'name' => 'asset_accounts_id', 'type' => 'select2',
			'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
			'datatable' => 'asset_accounts,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Accumulated Depreciation', 'name' => 'accumulated_depreciation', 'type' => 'number',
			'validation' => 'min:0.00', 'width' => 'col-sm-4'
		];

		$this->form[] = [
			'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text',
			'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
		];

		$this->form[] = [
			'label' => 'Quantity On Hand', 'name' => 'quantity_on_hand', 'type' => 'number',
			'validation' => 'min:0.00', 'width' => 'col-sm-4'
		];

		$this->form[] = [
			'label' => 'Fulfillment Type', 'name' => 'fulfillment_type_id', 'type' => 'select2',
			'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
			'datatable' => 'fulfillment_methods,fulfillment_method', 'datatable_where' => "status='ACTIVE'"
		];

		$this->form[] = [
			'label' => 'UOM', 'name' => 'uoms_id', 'type' => 'select2',
			'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'UOM Set', 'name' => 'uoms_set_id', 'type' => 'select2',
			'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
			'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
		];

		$this->form[] = [
			'label' => 'Supplier Cost', 'name' => 'purchase_price', 'type' => 'number',
			'validation' => CRUDBooster::myAddForm()->purchase_price ? 'required' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->purchase_price ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Sales Price', 'name' => 'ttp', 'type' => 'number',
			'validation' => CRUDBooster::myAddForm()->ttp ? 'required' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Commi Margin', 'name' => 'ttp_percentage', 'type' => 'number', 'readonly' => true,
			'validation' => CRUDBooster::myAddForm()->ttp_percentage ? 'required' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->ttp_percentage ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Landed Cost', 'name' => 'landed_cost', 'type' => 'number',
			'validation' => CRUDBooster::myAddForm()->landed_cost ? 'required' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->landed_cost ?: 'display:none;'
		];



		$this->form[] = [
			'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2',
			'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
			'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'suppliers,last_name', 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
		];

		$this->form[] = ['label' => 'Tax Agency', 'name' => 'tax_agency', 'type' => 'text', 'width' => 'col-sm-4'];



		$this->form[] = [
			'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
			'validation' => 'min:0.00', 'width' => 'col-sm-4'
		];

		$this->form[] = ['label' => 'MPN', 'name' => 'mpn', 'type' => 'text', 'width' => 'col-sm-4'];

		$this->form[] = [
			'label' => 'Group', 'name' => 'groups_id', 'type' => 'select2',
			'validation' => CRUDBooster::myAddForm()->group ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'groups,group_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->group ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Category Description', 'name' => 'categories_id', 'type' => 'select',
			'validation' => CRUDBooster::myAddForm()->category_description ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'categories,category_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->category_description ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Subcategory Description', 'name' => 'subcategories_id', 'type' => 'select',
			'validation' => CRUDBooster::myAddForm()->subcategory ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'subcategories,subcategory_description', 'datatable_where' => "status=%27ACTIVE%27", 'parent_select' => 'categories_id', 'style' => CRUDBooster::myAddForm()->subcategory ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Dimension', 'name' => 'packaging_dimension', 'type' => 'text',
			'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'required|max:50' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
			'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Cost', 'name' => 'cost', 'type' => 'number',
			'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
		];

		$this->form[] = [
			'label' => 'Packaging UOM', 'name' => 'packagings_id', 'type' => 'select2',
			'validation' => CRUDBooster::myAddForm()->packaging ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'packagings,packaging_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->packaging ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'Tax Status', 'name' => 'tax_status', 'type' => 'text',
			'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
		];

		$this->form[] = [
			'label' => 'Price', 'name' => 'price', 'type' => 'number',
			'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true
		];

		$this->form[] = [
			'label' => 'Supplier Item Code', 'name' => 'supplier_item_code', 'type' => 'text',
			'validation' => CRUDBooster::myAddForm()->supplier_item_code ? 'max:50' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->supplier_item_code ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'MOQ Store', 'name' => 'moq_store', 'type' => 'number',
			'validation' => CRUDBooster::myAddForm()->moq_store ? 'required' : '', 'width' => 'col-sm-4',
			'style' => CRUDBooster::myAddForm()->moq_store ?: 'display:none;'
		];

		$this->form[] = [
			'label' => 'SKU Status', 'name' => 'sku_statuses_id', 'type' => 'select2',
			'validation' => CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
			'datatable' => 'sku_statuses,sku_status_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->sku_status ?: 'display:none;'
		];

		$this->form[] = ['label' => 'Account Number', 'name' => 'chart_accounts_id', 'type' => 'select2', 'datatable' => 'chart_accounts,account_number', 'width' => 'col-sm-4'];

		$segmentation_data = DB::table('segmentations')->where('status', 'ACTIVE')->orderBy('segment_column_code', 'asc')->get();

		foreach ($segmentation_data as $segment) {

			$this->form[] = ['label' => '+' . " " . $segment->segment_column_description, 'name' => $segment->segment_column_name, 'type' => 'checkbox-custom', 'width' => 'col-sm-4'];
		}
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
			$my_privilege = CRUDBooster::myPrivilegeName();
			$to_edit = in_array($my_privilege, ['Purchasing Staff', 'Encoder', 'Super Administrator']) || CRUDBooster::isSuperAdmin();
			$to_approve = in_array($my_privilege, ['Manager (Purchaser)', 'Super Administrator']) || CRUDBooster::isSuperAdmin();

			if ($to_edit) {
				$this->addaction[] = [
					'title'=>'Edit',
					'url'=>CRUDBooster::mainpath('edit/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => ' ',
					"showIf"=>"[approval_status] == '400'",
				];
			}

			if ($to_approve) {
				$this->addaction[] = [
					'title'=>'Approve',
					'url'=>CRUDBooster::mainpath('approve_or_reject/[id]'),
					'icon'=>'fa fa-thumbs-up',
					'color' => ' ',
					"showIf"=>"[approval_status] == '202'",
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
			if (CRUDBooster::isUpdate() && (in_array(CRUDBooster::myPrivilegeName(),['Administrator','Supervisor (Purchaser)','Supervisor (Accounting)','Cost Accounting','Manager (Accounting)','Manager (Purchaser)']) || CRUDBooster::isSuperadmin()) ) {
	        	$this->button_selected[] = ['label'=>'APPROVE','icon'=>'fa fa-check','name'=>'approve'];
				$this->button_selected[] = ['label'=>'REJECT','icon'=>'fa fa-times','name'=>'reject'];
			}
	                
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
			// $module_id = DB::table('cms_moduls')->where('controller','AdminItemMastersController')->value('id');
			// $approver_checker = ApprovalWorkflowSetting::where('cms_moduls_id', 'LIKE', '%' . $module_id . '%')->where('approver_privilege_id',CRUDBooster::myPrivilegeId())->where('status','ACTIVE')->first();
	        
	        
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

			return self::approve_or_reject($id_selected, $button_name);
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
			$my_privilege = CRUDBooster::myPrivilegeName();

			$query->where(function($sub) {
					$sub
						->where('item_master_approvals.created_at', '>=', date('2023-06-07'))
						->orWhere('item_master_approvals.updated_at', '>=', date('2023-06-07'));
				});

			if ($my_privilege == 'Purchasing Staff') {
				$my_id = CRUDBooster::myId();
				$query->whereRaw("
					(item_master_approvals.action_type = 'CREATE' and item_master_approvals.created_by = $my_id)
					or
					(item_master_approvals.action_type = 'UPDATE' and item_master_approvals.updated_by = $my_id)
				");
			} else if ($my_privilege == 'Manager (Purchaser)') {
				$query->where('item_master_approvals.approval_status', '202');
			}

			$query->orderBy(DB::raw('COALESCE(item_master_approvals.updated_at, item_master_approvals.created_at)'), 'desc');
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
			//Your code here
			if($column_index == 3) {
				if ($column_value == '200') {
					$column_value = '<span class="label label-success">APPROVED</span>';
				} else if ($column_value == '202') {
					$column_value = '<span class="label label-warning">PENDING</span>';
				} else if ($column_value == '400') {
					$column_value = '<span class="label label-danger">REJECTED</span>';
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
			$postdata["created_by"]=CRUDBooster::myId();
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

		public function getEdit($id) {
			$my_privilege = CRUDBooster::myPrivilegeName();
			$to_edit = in_array($my_privilege, ['Purchasing Staff', 'Encoder', 'Super Administrator', 'Supervisor (Purchaser)']) || CRUDBooster::isSuperAdmin();
			if (!CRUDBooster::isUpdate() || !$to_edit)
					CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				); 

			$data = [];
			$data['item'] = DB::table('item_master_approvals')
				->where('id', $id)
				->get()
				->first();

			$data['action'] = 'edit';

			if ($data['item']->approval_status == 202) {
				return redirect(CRUDBooster::mainpath())->with([
					'message_type' => 'danger',
					'message' => '✖️ You cannot edit a pending item...',
				]);
			}
			
			$submaster_details = $this->main_controller->getSubmasters();

			$data = array_merge($data, $submaster_details);

			return $this->view('item-master/edit-item', $data);
		}

		public function getApproveOrReject($id) {
			$my_privilege = CRUDBooster::myPrivilegeName();
			$to_approve = in_array($my_privilege, ['Manager (Purchaser)', 'Super Administrator']) || CRUDBooster::isSuperAdmin();
			if (!CRUDBooster::isUpdate() || !$to_approve)
					CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];
			$data['item'] = DB::table('item_master_approvals')
				->where('id', $id)
				->get()
				->first();
			
			$submaster_details = $this->main_controller->getSubmasters();

			$data = array_merge($data, $submaster_details);

			return $this->view('item-master/approve-item', $data);
		}

		public function approveOrReject(Request $request) {

			$action = $request->get('action');
			$item_master_approvals_id = $request->get('item_master_approvals_id');
			return self::approve_or_reject([$item_master_approvals_id], $action);
		}

		public function approve_or_reject($item_ids, $action) {
			$action_by = CRUDBooster::myId();
			$privilege_name = CRUDBooster::myPrivilegeName();
			$time_stamp = date('Y-m-d H:i:s');

			if (!is_array($item_ids)) $item_ids = [$item_ids];

			foreach ($item_ids as $id) {
				$item = DB::table('item_master_approvals')
					->where('id', $id)
					->first();

				if ($item->approval_status != '202') {
					continue;
				}

				$tasteless_code = $item->tasteless_code;
				if (!$tasteless_code) {
					$groups_id = ItemMasterApproval::where('id', $id)->first()->groups_id;
					$group = Group::find($groups_id);
					$tasteless_code = $this->main_controller->getTastelessCode($group);
					$item->tasteless_code = $tasteless_code;
				}

				if ($action == 'approve') {
					ItemMasterApproval::where('id', $id)->update([
						'approval_status' => '200',
						'tasteless_code' => $tasteless_code,
						'approved_by_1' => $action_by,
						'approved_at_1' => $time_stamp,
					]);
					unset($item->id);
					ItemMaster::updateOrInsert(['tasteless_code' => $item->tasteless_code], (array) $item);
				} else if ($action == 'reject') {
					ItemMasterApproval::where('id', $id)->update([
						'approval_status' => '400',
						'approved_by_1' => $action_by,
						'approved_at_1' => $time_stamp,
					]);
				} 
					
			}

			if ($action == 'approve') {
				$message_type = 'success';
				$message = '✔️ Item successfully approved.';
			} else if ($action == 'reject') {
				$message_type = 'success';
				$message = '✖️ Item successfully rejected.';
			}

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => $message_type,
					'message' => $message,
				])->send();
		}
	}