<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use App\Models\ItemMastersFa;
	use App\Models\ItemMastersFasApprovals;
	use App\Models\FaCoaCategories;
	use App\Models\FaSubCategories;
	use App\Models\BrandsAssets;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Redirect;
	use Carbon\Carbon;
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Support\Facades\Storage;
	use Intervention\Image\Facades\Image;
	use Spatie\ImageOptimizer\OptimizerChainFactory;
	use Illuminate\Support\Str;
	
	class AdminItemMastersFasController extends \crocodicstudio\crudbooster\controllers\CBController {
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->diffData = [];
			$this->requestor = ['Purchasing Staff', 'Purchasing Encoder', 'Encoder'];
			$this->approver = ['Purchasing Manager', 'Manager (Purchaser)'];
			$this->to_notify = DB::table('cms_users')
				->where(function($sub_query) {
					$sub_query
						->where('cms_users.id_cms_privileges', '1')
						->orWhere('cms_privileges.name', 'Purchasing Manager');
				})
				->where('cms_users.status', 'ACTIVE')
				->leftJoin('cms_privileges', 'cms_privileges.id', '=', 'cms_users.id_cms_privileges')
				->pluck('cms_users.id')
				->toArray();
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
			$this->button_export = true;
			$this->table = "item_masters_fas";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label" => "Display Photo", "name" => "image_filename","visible" =>  true];
			$this->col[] = ["label"=>"Tasteless Code","name"=>"tasteless_code"];
			$this->col[] = ["label"=>"Item Description","name"=>"item_description"];
			$this->col[] = ["label"=>"COA","name"=>"categories_id","join"=>"fa_coa_categories,description"];
			$this->col[] = ["label"=>"Sub category","name"=>"subcategories_id","join"=>"fa_sub_categories,description"];
			$this->col[] = ["label"=>"Cost","name"=>"cost"];
			$this->col[] = ["label"=>"UPC Code","name"=>"upc_code"];
			$this->col[] = ["label"=>"Supplier Item Code","name"=>"supplier_item_code"];
			$this->col[] = ["label"=>"Brand Name","name"=>"brand_id"];
			$this->col[] = ["label"=>"Vendor 1 Name","name"=>"vendor1_id"];
			$this->col[] = ["label"=>"Model","name"=>"model"];
			$this->col[] = ["label"=>"Size","name"=>"size"];
			$this->col[] = ["label"=>"Color","name"=>"color"];
			$this->col[] = ["label" => "Created Date", "name" => "created_at", "visible" => CRUDBooster::myColumnView()->create_date ? true : false];
			$this->col[] = ["label" => "Created By", "name" => "created_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->create_by ? true : false];
			$this->col[] = ["label" => "Updated Date", "name" => "updated_at", "visible" => CRUDBooster::myColumnView()->update_date ? true : false];
			$this->col[] = ["label" => "Updated By", "name" => "updated_by", "join" => "cms_users,name", "visible" => CRUDBooster::myColumnView()->update_by ? true : false];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

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
			$this->script_js = "
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
			$this->load_js[] = asset("js/zoom.js");
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
			$this->style_css = "
			.item-master-image {
				max-width: 100px;
			}
			
			";
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        $this->load_css[] = asset("css/zoom.css");
	        
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
	        $query->leftJoin('item_masters_fas_approvals', 'item_masters_fas.tasteless_code', '=', 'item_masters_fas_approvals.tasteless_code')
				->addSelect('item_masters_fas_approvals.approval_status as status_of_approval');
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	if ($column_index == 2 && $column_value) {
				$column_value = '<image class="item-master-image" src="'. asset("img/item-master-fa/$column_value") . '" data-action="zoom" width="100" height="100"/>';
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

		public function getDetail($id) {
			if (!CRUDBooster::isRead())
					CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);
			
			$submaster_details = self::getSubmasters();
			$data = [];
			$data['page_title'] = 'Asset Masterfile';
			$item = DB::table('item_masters_fas')
				->where('id', $id)
				->get()
				->first();

			$data['item'] = $item;

			$data['brand'] = DB::table('brands')
				->where('id', $item->brands_id)
				->pluck('brand_description')
				->first();

			$data['supplier'] = DB::table('suppliers')
				->where('id', $item->suppliers_id)
				->pluck('last_name')
				->first();

			$data['subcategory'] = DB::table('subcategories')
				->where('id', $item->subcategories_id)
				->pluck('subcategory_description')
				->first();

			$data = array_merge($data, $submaster_details);

			return $this->view('item-master-fa/detail-item-fa', $data);
		}

		public function getAdd(){
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
			$data['from'] = 'item_masters FA';
			$data['page_title'] = 'Asset Masterfile';
			if ($id) {
				$tasteless_code = ItemMastersFa::where('id', $id)->first()->tasteless_code;
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
			
			return $this->view('item-master-fa/add-edit', $data);
		}

		public function getSubmasters() {

			$data = [];

			$data['coa'] = DB::table('fa_coa_categories')
				->where('status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['sub_categories'] = DB::table('fa_sub_categories')
				->where('status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['currencies'] = DB::table('currencies')
				->where('status', 'ACTIVE')
				->get()
				->toArray();
			
			$data['brands'] = DB::table('brands_assets')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['sku_statuses'] = DB::table('sku_statuses')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			return $data;
		}

		public function submitAddOrEdit(Request $request) {
			$input = $request->all();
			if ($input['item_photo']) {
				$filename_filler = $input['tasteless_code'] ?? 'new_item';
				$random_string = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', Str::random(10));
	
				$img_file = $input['item_photo'];
				$filename = date('Y-m-d') . "-$filename_filler-$random_string." . $img_file->getClientOriginalExtension();
				$image = Image::make($img_file);
				
				$image->resize(1024, 768, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
	
				// Save the resized image to the public folder
				$image->save(public_path('img/item-master-fa/' . $filename));
				// Optimize the uploaded image
				$optimizerChain = OptimizerChainFactory::create();
				$optimizerChain->optimize(public_path('img/item-master-fa/' . $filename));
			}

		
			$tasteless_code = $input['tasteless_code'];
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$my_privilege_id = CRUDBooster::myPrivilegeId();
		
			$data = $request->all();

			unset(
				$data['_token'], 
				$data['item_photo'], 
				$data['item_masters_approvals_id'],
			);
			$brandCode = BrandsAssets::where('id',$data['brand_id'])->first()->brand_code;
			$data['item_description'] = $brandCode. " " .$data['item_description'];
			$data['sku_statuses_id'] = $input['sku_statuses_id'] ?? 1;
			$data['tasteless_code'] = $tasteless_code;
			if ($filename) $data['image_filename'] = $filename;

			$data['approval_status'] = 202;
			
			$is_existing = ItemMastersFasApprovals::where('tasteless_code', $tasteless_code)->exists();

			if ($is_existing && $tasteless_code) {
				$data['updated_by'] = $action_by;
				$data['updated_at'] = $time_stamp;
			} else {
				$data['created_by'] = $action_by;
				$data['created_at'] = $time_stamp;
			}

			if (!$tasteless_code && !$input['item_masters_approvals_id']) {
				$data['action_type'] = 'CREATE';
				$inserted_id = ItemMastersFasApprovals::insertGetId($data);
			} else if ($tasteless_code) {
				$data['action_type'] = 'UPDATE';
				$item_to_be_updated = ItemMastersFasApprovals::where('tasteless_code', $tasteless_code);
				
				$item_to_be_updated->update($data);
				$inserted_id = $item_to_be_updated->first()->id;
			} else {
				$data['action_type'] = 'CREATE';
				ItemMastersFasApprovals::where('id', $input['item_masters_approvals_id'])->update($data);
			}

			$notif_config = [
				'content' => 'An item has been added to pending for approval.',
				'id_cms_users' => $this->to_notify,
				'to' => CRUDBooster::adminPath("item_approval/approve_or_reject/" . ($input['item_masters_approvals_id'] ?? $inserted_id)),
			];

			// CRUDBooster::sendNotification($notif_config);

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => '✔️ Item added to Pending Items...',
				])->send();
			
		}

		public function getItemDetails($tasteless_code) {
			$item = DB::table('item_masters_fas')
				->where('tasteless_code', $tasteless_code)
				->get()
				->first();

			return $item;
		}

		public function getUpdatedItems($secret_key) {
			if ($secret_key != config('api.secret_key')) {
				return response([
					'message' => 'Error: Bad Request',
				], 404);
			}

			$created_items = DB::table('item_masters_fas')
				->where('action_type', 'CREATE')
				->whereBetween(DB::raw('DATE(approved_at)'), [date('Y-m-d',strtotime("-1 days")), date('Y-m-d')])
				->get()
				->toArray();

			$updated_items = DB::table('item_masters_fas')
				->where('action_type', 'UPDATE')
				->whereBetween(DB::raw('DATE(approved_at)'), [date('Y-m-d',strtotime("-1 days")), date('Y-m-d')])
				->get()
				->toArray();

			return response()->json([
				'created_items' => $created_items,
				'updated_items' => $updated_items,
			]);
		}
	}