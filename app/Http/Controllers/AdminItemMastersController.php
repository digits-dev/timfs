<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use Maatwebsite\Excel\HeadingRowImport;
	use Maatwebsite\Excel\Imports\HeadingRowFormatter;
	use Maatwebsite\Excel\Facades\Excel;
	use CRUDBooster;
	use App\Brand;
	use App\ItemMaster;
	use App\ItemMasterApproval;
	use App\ApprovalWorkflowSetting;
	use Illuminate\Http\Request;
	use App\CodeCounter;
	use App\Exports\ExcelTemplate;
	use App\Exports\BartenderExport;
	use App\Exports\ItemExport;
	use App\Exports\POSExport;
	use App\Exports\QBExport;
	use App\Group;
	use App\Segmentation;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Redirect;
	use Carbon\Carbon;
	use Illuminate\Support\Facades\Schema;
	use Intervention\Image\Facades\Image;
	use Spatie\ImageOptimizer\OptimizerChainFactory;
	use Illuminate\Support\Str;


	class AdminItemMastersController extends \crocodicstudio\crudbooster\controllers\CBController {
	    
        private $pre_ttp_price = 0;
        private $segmentation_editForm = [];
        private $counter = 0;
		protected $diffData;
		protected $segments;

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->diffData = [];
			$this->segments = Segmentation::where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();
			$this->requestor = ['Purchasing Staff', 'Purchasing Encoder', 'Encoder'];
			$this->approver = ['Purchasing Manager', 'Manager (Purchaser)'];
		}
	    
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "supplier_item_code";
			$this->limit = "20";
			$this->orderby = "tasteless_code,desc";
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
			$this->table = "item_masters";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
            $this->col[] = ["label" => "Item ID", "name" => "id", "visible" => false];
    		$this->col[] = ["label" => "Tasteless Code", "name" => "tasteless_code","visible" =>  false];
    		$this->col[] = ["label" => "Preferred Vendor", "name" => "suppliers_id", "join" => "suppliers,last_name", "visible" => CRUDBooster::myColumnView()->supplier ? true : false];
    		$this->col[] = ["label" => "Item", "name" => "tasteless_code", "visible" =>  true];
    		$this->col[] = ["label" => "Description", "name" => "full_item_description", "visible" => CRUDBooster::myColumnView()->full_item_description ? true : false];
    		$this->col[] = ["label"=>"Brand Description","name"=>"brands_id","join"=>"brands,brand_description","visible" => CRUDBooster::myColumnView()->brand_description ? true : false];
    		$this->col[] = ["label" => "Category Description", "name" => "categories_id", "join" => "categories,category_description", "visible" => CRUDBooster::myColumnView()->category_description ? true : false];
    		$this->col[] = ["label" => "Subcategory Description", "name" => "subcategories_id", "join" => "subcategories,subcategory_description", "visible" => CRUDBooster::myColumnView()->subcategory ? true : false];
            $this->col[] = ["label" => "Fulfillment Type", "name" => "fulfillment_type_id", "join" => "fulfillment_methods,fulfillment_method"];
            $this->col[] = ["label" => "Packaging Size", "name" => "packaging_size", "visible" => CRUDBooster::myColumnView()->packaging_size ? true : false];
    		$this->col[] = ["label" => "Packaging UOM", "name" => "packagings_id", "join" => "packagings,packaging_code", "visible" => CRUDBooster::myColumnView()->packaging ? true : false];
    		$this->col[] = ["label"=>"Currency","name"=>"currencies_id","join"=>"currencies,currency_code","visible" => CRUDBooster::myColumnView()->currency ? true : false];
    		$this->col[] = ["label" => "Supplier Cost", "name" => "purchase_price", "visible" => CRUDBooster::myColumnView()->purchase_price ? true : false];
			$this->col[] = ["label" => "Sales Price", "name" => "ttp", "visible" => CRUDBooster::myColumnView()->ttp ? true : false];
			$this->col[] = ["label" => "Sales Price Change", "name" => "ttp_price_change", "visible" => CRUDBooster::myColumnView()->ttp ? true : false]; //2022-07-04
			$this->col[] = ["label" => "Sales Price Effective Date", "name" => "ttp_price_effective_date", "visible" => CRUDBooster::myColumnView()->ttp ? true : false]; //2022-07-04
    		$this->col[] = ["label" => "Landed Cost", "name" => "landed_cost", "visible" => CRUDBooster::myColumnView()->landed_cost ? true : false];
            $this->col[] = ["label" => "Commi Margin", "name" => "ttp_percentage", "visible" => CRUDBooster::myColumnView()->landed_cost ? true : false];
            $this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->create_by ? true : false];
    		$this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->update_date ? true : false];
		
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			

                //----added by cris 20200630----------------------
			$this->form[] = ['label' => 'Item', 'name' => 'tasteless_code', 'type' => 'text', 'readonly' => true, 'width' => 'col-sm-4'];
			
			$this->form[] = [
				'label' => 'Active Status', 'name' => 'sku_statuses_id', 'type' => 'select2',
				'validation' => CRUDBooster::myAddForm()->sku_status ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
				'datatable' => 'sku_statuses,sku_status_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->sku_status ?: 'display:none;'
			];
			
			$this->form[] = [
				'label' => 'Type', 'name' => 'type', 'type' => 'select',
				'validation' => 'required', 'width' => 'col-sm-4', 'dataenum'=>'Inventory Part'
			];
			
			$this->form[] = [
				'label' => 'Description', 'name' => 'full_item_description', 'type' => 'text',
				'validation' => CRUDBooster::myAddForm()->full_item_description ? 'required|min:5|max:255' : '', 'width' => 'col-sm-4',
				'style' => CRUDBooster::myAddForm()->full_item_description ?: 'display:none;'
			];
			
			$this->form[] = ['label'=>'Brand Description','name'=>'brands_id','type'=>'select2',
				'validation'=>CRUDBooster::myAddForm()->brand_description ? 'required|integer|min:0' : '','width'=>'col-sm-4',
				'datatable'=>'brands,brand_description','datatable_where'=>"status='ACTIVE'",'style'=> CRUDBooster::myAddForm()->brand_description ? : 'display:none;'];


			$this->form[] = [
				'label' => 'Tax Code', 'name' => 'tax_codes_id', 'type' => 'select2',
				'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
				'datatable' => 'tax_codes,tax_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
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
				'label' => 'Purchase Description', 'name' => 'purchase_description', 'type' => 'text',
				'validation' => 'required|min:5|max:255', 'width' => 'col-sm-4', 'readonly' => true
			];
			
			$this->form[] = [
				'label' => 'Fulfillment Type', 'name' => 'fulfillment_type_id', 'type' => 'select2',
				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
				'datatable' => 'fulfillment_methods,fulfillment_method', 'datatable_where' => "status='ACTIVE'"
			];

			$this->form[] = [
				'label' => 'U/M', 'name' => 'uoms_id', 'type' => 'select2',
				'validation' => CRUDBooster::myAddForm()->uom ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
				'datatable' => 'uoms,uom_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->uom ?: 'display:none;'
			];

			$this->form[] = [
				'label' => 'U/M Set', 'name' => 'uoms_set_id', 'type' => 'select2',
				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4',
				'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
			];

				$this->form[] = ['label'=>'Currency','name'=>'currencies_id','type'=>'select2','width'=>'col-sm-4',
			'datatable'=>'currencies,currency_code','datatable_where'=>"status='ACTIVE'"];

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
				'label' => 'Sales Price Change', 'name' => 'ttp_price_change', 'type' => 'number',
				'width' => 'col-sm-4',
				'style' => CRUDBooster::myAddForm()->ttp ?: 'display:none;'
			];

			$this->form[] = [
				'label' => 'Sales Price Effective Date', 'name' => 'ttp_price_effective_date', 'type' => 'date',
				'width' => 'col-sm-4',
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
				'label' => 'Sales Price', 'name' => 'price', 'type' => 'number',
				'validation' => 'required', 'width' => 'col-sm-4', 'readonly' => true, 'style' => 'display:none;'
			];

			$this->form[] = [
				'label' => 'Preferred Vendor', 'name' => 'suppliers_id', 'type' => 'select2',
				'disabled' => CRUDBooster::myEditReadOnly()->supplier ? true : false,
				'validation' => CRUDBooster::myEditForm()->supplier ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
				'datatable' => 'suppliers,last_name', 'datatable_where' => "approval_status != 'NULL'", 'style' => CRUDBooster::myAddForm()->supplier ?: 'display:none;'
			];
			
			$this->form[] = [
				'label' => 'Reorder Pt (Min)', 'name' => 'reorder_pt', 'type' => 'number',
				'validation' => 'required|min:0.00', 'width' => 'col-sm-4'
			];
			
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
				'validation' => CRUDBooster::myAddForm()->packaging_dimension ? 'max:50' : '', 'width' => 'col-sm-4',
				'style' => CRUDBooster::myAddForm()->packaging_dimension ?: 'display:none;'
			];

			$this->form[] = [
				'label' => 'Packaging Size', 'name' => 'packaging_size', 'type' => 'number',
				'validation' => CRUDBooster::myAddForm()->packaging_size ? 'required' : '', 'width' => 'col-sm-4',
				'style' => CRUDBooster::myAddForm()->packaging_size ?: 'display:none;'
			];
			
			$this->form[] = [
				'label' => 'Packaging UOM', 'name' => 'packagings_id', 'type' => 'select2',
				'validation' => 'required|integer|min:0', 'width' => 'col-sm-4', 'readonly' => true,
				'datatable' => 'uoms_set,uom_description', 'datatable_where' => "status='ACTIVE'"
			];

			$this->form[] = [
				'label' => 'Tax Status', 'name' => 'tax_codes_id', 'type' => 'select2',
				'validation' => CRUDBooster::myAddForm()->tax_code ? 'required|integer|min:0' : '', 'width' => 'col-sm-4',
				'datatable' => 'tax_codes,tax_description', 'datatable_where' => "status='ACTIVE'", 'style' => CRUDBooster::myAddForm()->tax_code ?: 'display:none;'
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

			

			$this->form[] = ['label' => 'Account Number', 'name' => 'chart_accounts_id', 'type' => 'select2', 'datatable' => 'chart_accounts,account_number', 'width' => 'col-sm-4'];

			foreach ($this->segments as $segment) {

				$this->form[] = ['label' => '+' . " " . $segment->segment_column_description, 'name' => $segment->segment_column_name, 'type' => 'checkbox-custom',
				'datatable' => 'segmentations,segment_column_description,segment_column_name', 'datatable_where' => "status='ACTIVE'", 'width' => 'col-sm-4'];
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
			if (in_array($my_privilege, $this->requestor) || CRUDBooster::isSuperAdmin()) {
				$this->addaction[] = [
					'title'=>'Edit',
					'url'=>CRUDBooster::mainpath('edit/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => ' ',
					"showIf"=>"[status_of_approval] != '202'",
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
			if (CRUDBooster::getCurrentMethod() == 'getIndex') 
            {
				$this->index_button[] = ['label' => 'Export Items', "url" => "javascript:showItemExport()", "icon" => "fa fa-download"];
				
				if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Administrator","Manager (Purchaser)","Encoder (Purchaser)"])){
					$this->index_button[] = ['label' => 'Upload Module', "url" => CRUDBooster::mainpath("upload-module").'?'.urldecode(http_build_query(@$_GET)), "icon" => "fa fa-upload"];
				}
				if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Administrator","Manager (Purchaser)","Manager (Accounting)","Encoder (Purchaser)","Encoder (Accounting)","Supervisor (Purchaser)"])){
    				$this->index_button[] = ['label' => 'Bartender Format', 'url'=>"javascript:showBartenderExport()",'icon'=>'fa fa-download'];
    				$this->index_button[] = ['label' => 'POS Format', "url" => "javascript:showPOSExport()", "icon" => "fa fa-download"];
    				$this->index_button[] = ['label' => 'QB Item Format', "url" => "javascript:showQBExport()", "icon" => "fa fa-download"];
				    
				}
                if (!CRUDBooster::isSuperadmin() && in_array(CRUDBooster::myPrivilegeName(), ["View I (TTP)", "View II (Purchase Price)", "View III (TTP and Purchase Price)"])) {
                    $this->index_button[] = ['label' => 'QB Item Format', "url" => "javascript:showQBExport()", "icon" => "fa fa-download"];
                    
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
			$this->script_js = NULL;
			$this->script_js = "
				function showBartenderExport() {
					$('#modal-bartender-export').modal('show');
				}

				function showPOSExport() {
					$('#modal-pos-export').modal('show');
				}

				function showQBExport() {
					$('#modal-qb-export').modal('show');
				}

				function showItemExport() {
					$('#modal-items-export').modal('show');
				}

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
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-bartender-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Bartender</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("bartender").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Bartender - ".date('Y-m-d H:i:s')."'/>
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

			<div class='modal fade' tabindex='-1' role='dialog' id='modal-pos-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export POS Format</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("posformat").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export POS Format - ".date('Y-m-d H:i:s')."'/>
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

			<div class='modal fade' tabindex='-1' role='dialog' id='modal-qb-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export QB Format</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("qbformat").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export QB Format - ".date('Y-m-d H:i:s')."'/>
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

			<div class='modal fade' tabindex='-1' role='dialog' id='modal-items-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Items</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("item-export").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Items - ".date('Y-m-d H:i:s')."'/>
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
	        // $this->load_js[] = asset("js/item_master.js");
	        
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
	    public function hook_query_index(&$query) 
        {

			$query
				->leftJoin('item_master_approvals', 'item_masters.tasteless_code', '=', 'item_master_approvals.tasteless_code')
				->addSelect('item_master_approvals.approval_status as status_of_approval');
				// ->orderByRaw(DB::raw('COALESCE(item_masters.updated_at, item_masters.created_at) desc'));
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
            // if($column_index == 12){
			// 	$column_value = floatval(number_format($column_value, 5, '.', ''));
			// }
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
	    public function hook_after_add($id)
        {        
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
	    public function hook_before_edit(&$postdata,$id) 
        {

		}

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) 
        {

		}

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) 
        { 
			$deletedItem = ItemMaster::where('id', $id)->first();

			DB::table('history_item_masterfile')->insert([
				'tasteless_code'	=>	$deletedItem->tasteless_code,
				'item_id'			=>	$id,
				'brand_id'			=>	$deletedItem->brands_id,
				'group_id'			=>	$deletedItem->groups_id,
				'action'			=>	"Delete",
				'details'			=>	'This item is deleted at '.date("Y-m-d H:i:s"),
				'created_by'		=>	$deletedItem->created_by,
				'updated_by'		=>	CRUDBooster::myId()
			]);
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

			$data = [];

			$data['action'] = $action;

			if ($id) {
				$tasteless_code = ItemMaster::where('id', $id)->first()->tasteless_code;
				$data['item'] = self::getItemDetails($tasteless_code);
				if ($data['item']->approval_status == 202) {
					return redirect(CRUDBooster::mainpath())->with([
						'message_type' => 'danger',
						'message' => '✖️ You cannot edit a pending item...',
					]);
				}
			}

			$submaster_details = self::getSubmasters();

			$data = array_merge($data, $submaster_details);
			
			return $this->view('item-master/edit-item', $data);
		}

		public function getTastelessCode($group) {
			if (strtolower(substr($group->group_description, 0, 4)) == 'food') {
				$code_column = "code_1";
			} else if (strtolower($group->group_description) == 'beverage') {
				$code_column = "code_2";
			} else if (strtolower($group->group_description) == 'finished goods') {
				$code_column = "code_1";
			} else if (strtolower(substr($group->group_description, -8)) == 'supplies') {
				$code_column = "code_3";
			} else if (strtolower($group->group_description) == 'capex') {
				$code_column = "code_5";
			} else if (strtolower($group->group_description) == 'complimentary') {
				$code_column = "code_7";
			} else if (strtolower(substr($group->group_description, -4)) == 'fees') {
				$code_column = "code_4";
			} else {
				$code_column = "code_6";
			}
			$tasteless_code = CodeCounter::where('id', 1)->where('type', 'ITEM MASTER')->value($code_column);
			CodeCounter::where('type', 'ITEM MASTER')->where('id', 1)->increment($code_column);
			return $tasteless_code;
		}

		public function getItemDetails($tasteless_code) {
			$item = DB::table('item_master_approvals')
				->where('tasteless_code', $tasteless_code)
				->get()
				->first();

			return $item;
		}

		public function getSubmasters() {

			$data = [];

			$data['brands'] = DB::table('brands')
				->where('status', 'ACTIVE')
				->orderBy('brand_description')
				->get()
				->toArray();

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

			$data['suppliers'] = DB::table('suppliers')
				->orderBy('last_name')
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

			// EDIT ITEM
			$data['types'] = DB::table('types')
				->where('status', 'ACTIVE')
				->orderBy('type_description')
				->get()
				->toArray();

			return $data;
		}

		public function submitAddOrEdit(Request $request) {
			$input = $request->all();
			if ($input['item_photo']) {
				$random_string = Str::random(10);
				$random_string = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $random_string);
				$item_description = str_replace(['/', ':', ' ', ','], '_', $input['full_item_description']);
				$item_description = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $item_description);
	
				$img_file = $input['item_photo'];
				$filename = "$item_description(" . date('Y-m-d') . ")$random_string." . $img_file->getClientOriginalExtension();
				$image = Image::make($img_file);
				
				$image->resize(1024, 768, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
	
				// Save the resized image to the public folder
				$image->save(public_path('img/item-master/' . $filename));
				// Optimize the uploaded image
				$optimizerChain = OptimizerChainFactory::create();
				$optimizerChain->optimize(public_path('img/item-master/' . $filename));
			}

			$segmentations = (array) json_decode($input['segmentations']);
			$group = Group::findOrFail($input['groups_id']);
			$tasteless_code = $input['tasteless_code'];
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$my_privilege_id = CRUDBooster::myPrivilegeId();
			$segment_columns = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->pluck('segment_column_name')
				->toArray();

			$segmentation_statuses = DB::table('sku_legends')
				->where('status', 'ACTIVE')
				->where('sku_legend', '!=', 'X')
				->pluck('sku_legend')
				->toArray();

			$data = $request->all();

			unset($data['_token'], $data['segmentations'], $data['item_photo']);
			$data['price'] = $data['ttp'];
			$data['myob_item_description'] = $data['full_item_description'];
			$data['sku_statuses_id'] = $input['sku_statuses_id'] ?? 1;
			$data['type'] = 'Inventory Part';
			$data['tax_status'] = $data['tax_codes_id'];
			$data['tasteless_code'] = $tasteless_code;
			if ($filename) $data['image_filename'] = $filename;

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

			$data['encoder_privilege_id'] =	$my_privilege_id;
			$data['approval_status'] = 202;
			
			$is_existing = ItemMasterApproval::where('tasteless_code', $tasteless_code)->exists();

			if ($is_existing && $tasteless_code) {
				$data['updated_by'] = $action_by;
				$data['updated_at'] = $time_stamp;
			} else {
				$data['created_by'] = $action_by;
				$data['created_at'] = $time_stamp;
			}

			if (!$tasteless_code) {
				$data['action_type'] = 'CREATE';
				ItemMasterApproval::insert($data);
			} else {
				$data['action_type'] = 'UPDATE';
				ItemMasterApproval::where('tasteless_code', $tasteless_code)->update($data);
			}

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => '✔️ Item added to Pending Items...',
				]);
			
		}

		public function exportItems(Request $request) {
			$filename = $request->input('filename');
			return Excel::download(new ItemExport, $filename.'.xlsx');
		}
		
		public function exportQBFormat(Request $request) {
			$filename = $request->input('filename');
			return Excel::download(new QBExport, $filename.'.xlsx');
		}

		public function exportPOSFormat(Request $request) {
			$filename = $request->input('filename');
		   	return Excel::download(new POSExport, $filename.'.xlsx');
		}

		public function exportBartender(Request $request) {
			$filename = $request->input('filename');
		   	return Excel::download(new BartenderExport, $filename.'.xlsx');
		}		
		
		public function getUploadModule() {
			$this->cbLoader();
			$data['page_title'] = 'Upload Module';
			return view("upload.upload", $data);
		}
	}