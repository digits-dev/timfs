<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Request as Input;
	use DB;
	use CRUDBooster;

	class AdminNewPackagingsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->mainController = new AdminNewIngredientsController;
			$this->tagger = ['Purchasing Staff', 'Purchasing Encoder', 'Encoder'];
			$this->to_notify = DB::table('cms_users')
				->where(function($sub_query) {
					$sub_query
						->where('cms_users.id_cms_privileges', '1')
						->orWhereIn('cms_privileges.name', ['Purchasing Encoder', 'Purchasing Manager', 'Purchasing Staff']);
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
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "new_packagings";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Approval Status","name"=>"item_approval_statuses_id","join"=>"item_approval_statuses,status_description","callback"=>function($row) {
				if ($row->approval_status) {
					foreach ($this->status_badges as $key => $badge) {
						if (in_array($row->approval_status, $badge)) {
							return "<span class='label label-$key'>$row->approval_status</span>";
						}
					}
				}
			}];
			$this->col[] = ["label"=>"Souring Status","name"=>"item_sourcing_statuses_id","join"=>"item_sourcing_statuses,status_description","callback"=>function($row) {
				if ($row->sourcing_status) {
					foreach ($this->status_badges as $key => $badge) {
						if (in_array($row->sourcing_status, $badge)) {
							return "<span class='label label-$key'>$row->sourcing_status</span>";
						}
					}
				}
			}];
			$this->col[] = ["label"=>"Item Type","name"=>"new_item_types_id","join"=>"new_item_types,item_type_description"];
			$this->col[] = ["label"=>"NWP Code","name"=>"nwp_code"];
			$this->col[] = ["label"=>"Tasteless Code","name"=>"item_masters_id","join"=>"item_masters,tasteless_code"];
			$this->col[] = ["label"=>"Last Comment","name"=>"id", "callback" => function($row) {
				$comment = $row->comment_content;
				if ($comment && strlen($comment) > 50) {
					$comment = substr($comment, 0, 49) . '...';
				}
				$value = "";
				if ($row->comment_by) {
					$value .= "<div class='comment-data comment-by'>$row->comment_by</div>";
				}
				if ($row->comment_date) {
					$value .= "<div class='comment-data comment-date'><span class='timeago' datetime='$row->comment_date'>$row->comment_date</span></div>";
				}
				if ($comment) {
					$value .= "<div class='comment-data comment-content'>$comment</div>";
				}
				if ($row->comment_image) {
					$url = asset('img/item-sourcing/' . $row->comment_image);
					$img = "<img class='comment-image' src='$url' />";
					$value .= $img;
				}
				return $value;
			}];
			$this->col[] = ["label"=>"Item Type","name"=>"new_item_types_id","join"=>"new_item_types,item_type_description"];
			$this->col[] = ["label"=>"NWP Code","name"=>"nwp_code"];
			$this->col[] = ["label"=>"Tasteless Code","name"=>"item_masters_id","join"=>"item_masters,tasteless_code"];
			$this->col[] = ["label"=>"Item Description","name"=>"item_description"];
			$this->col[] = ["label"=>"Packaging Size","name"=>"packaging_size"];
			$this->col[] = ["label"=>"UOM","name"=>"uoms_id","join"=>"uoms,uom_description"];
			$this->col[] = ["label"=>"TTP","name"=>"ttp"];
			$this->col[] = ["label"=>"Target Date","name"=>"target_date"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Tagged By","name"=>"tagged_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Tagged Date","name"=>"tagged_at"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Item Description','name'=>'item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Item Type','name'=>'new_item_types_id','type'=>'select2','validation'=>'required','width'=>'col-sm-6','datatable'=>'new_item_types,item_type_description'];
			$this->form[] = ['label'=>'Packaging Size','name'=>'packaging_size','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'UOM','name'=>'uoms_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-6','datatable'=>'uoms,uom_description'];
			$this->form[] = ['label'=>'TTP','name'=>'ttp','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Item Masters Id","name"=>"item_masters_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"item_masters,id"];
			//$this->form[] = ["label"=>"Item Masters Tasteless Code","name"=>"item_masters_tasteless_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Item Description","name"=>"item_description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Packaging Size","name"=>"packaging_size","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Uoms Id","name"=>"uoms_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"uoms,id"];
			//$this->form[] = ["label"=>"Ttp","name"=>"ttp","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
			$my_privilege = CRUDBooster::myPrivilegeName();

			$this->addaction[] = [
				'title'=>'Detail',
				'url'=>CRUDBooster::mainpath('detail/[id]'),
				'icon'=>'fa fa-eye',
				'color' => ' ',
			];

			if (CRUDBooster::isUpdate() || CRUDBooster::isSuperAdmin()) {
				$this->addaction[] = [
					'title'=>'Edit',
					'url'=>CRUDBooster::mainpath('edit/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => ' ',
					"showIf"=>"[item_masters_id] == null && ([created_by] == CRUDBooster::myId() || CRUDBooster::isSuperAdmin())"
				];
			}

			if (in_array($my_privilege, $this->tagger) || CRUDBooster::isSuperAdmin()) {
				$this->addaction[] = [
					'title'=>'Tag',
					'url'=>CRUDBooster::mainpath('get-tag/[id]'),
					'icon'=>'fa fa-tag',
					'color' => ' ',
					"showIf"=>"[item_masters_id] == null"
				];
			}

			// if (CRUDBooster::isSuperAdmin() || $my_privilege == 'Marketing Encoder') {
			// 	$this->addaction[] = [
			// 		'title'=>'Delete',
			// 		'url' => '#[id]',
			// 		'icon'=>'fa fa-trash',
			// 		'color' => ' delete-rnd-menu',
			// 		"showIf"=>"[item_masters_id] == null"
			// 	];
			// }


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
			$tagged_items = DB::table('new_packagings')
			->where('new_packagings.status', 'ACTIVE')
			->whereNotNull('new_packagings.item_masters_id')
			->count();

			$pending_items = DB::table('new_packagings')
				->where('new_packagings.status', 'ACTIVE')
				->whereNull('new_packagings.item_masters_id')
				->count();

			$this->index_statistic[] = [
				'label' => 'Pending Items',
				'count' => $pending_items,
				'icon' => 'fa fa-hourglass',
				'color' => 'orange',
			];

			$this->index_statistic[] = [
				'label' => 'Tagged Items',
				'count' => $tagged_items,
				'icon' => 'fa fa-tag',
				'color' => 'green',
			];



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $admin_path = CRUDBooster::adminPath();
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
					function(){location.href=`$admin_path/delete-new-items/new_packagings/` + dbId}
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
			$this->load_js[] = "https://unpkg.com/timeago.js/dist/timeago.min.js";
			$this->load_js[] = asset('js/item-sourcing.js');
	        
	        
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
	        $this->load_css[] = asset('css/item-sourcing.css');
	        
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
				->leftJoin('new_items_last_comment', 'new_items_last_comment.new_packagings_id', 'new_packagings.id')
				->leftJoin('new_items_comments', 'new_items_comments.id', 'new_items_last_comment.new_items_comments_id')
				->leftJoin('cms_users as commenter', 'commenter.id', 'new_items_comments.created_by')
				->addSelect(
					'new_items_comments.created_at as comment_date',
					'new_packagings.item_masters_id',
					'commenter.name as comment_by',
					'new_items_comments.comment_content',
					'new_items_comments.filename as comment_image',
					'item_approval_statuses.status_description as approval_status',
					'item_sourcing_statuses.status_description as sourcing_status',
				)
				->where('new_packagings.status', 'ACTIVE')
				->orderBy(DB::raw('item_masters_id is null'), 'desc')
				->orderBy(DB::raw('target_date is null'), 'asc')
				->orderBy('target_date', 'asc');   
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
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
			$max_nwp_code = DB::table('new_packagings')->max('nwp_code');
			$nwp_code_int = (int) explode('-', $max_nwp_code)[1] + 1;
			$nwp_code = 'NWP-' . str_pad($nwp_code_int, 5, '0', STR_PAD_LEFT);

			$postdata['nwp_code'] = $nwp_code;
			$postdata['item_description'] = strtoupper($postdata['item_description']);
			$postdata['comment'] = Input::get('comment');
			$postdata['target_date'] = Input::get('target_date');
			$postdata['packaging_types_id'] = Input::get('packaging_types_id');
			$postdata['sticker_types_id'] = Input::get('sticker_types_id');
			$postdata['packaging_uses_id'] = Input::get('packaging_uses_id');
			$postdata['packaging_beverage_types_id'] = Input::get('packaging_beverage_types_id');
			$postdata['packaging_material_types_id'] = Input::get('packaging_material_types_id');
			$postdata['packaging_paper_types_id'] = Input::get('packaging_paper_types_id');
			$postdata['packaging_design_types_id'] = Input::get('packaging_design_types_id');
			$postdata['size'] = Input::get('size');
			$postdata['budget_range'] = Input::get('budget_range');
			$postdata['initial_qty_needed'] = Input::get('initial_qty_needed');
			$postdata['initial_qty_uoms_id'] = Input::get('initial_qty_uoms_id');
			$postdata['forecast_qty_needed'] = Input::get('forecast_qty_needed');
			$postdata['forecast_qty_uoms_id'] = Input::get('forecast_qty_uoms_id');
			$postdata['created_by'] = CRUDBooster::myId();
			$postdata['created_at'] = date('Y-m-d H:i:s');
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        $inserted_item = DB::table('new_packagings')
				->where('id', $id)
				->first();

			DB::table('new_items_comments')
				->insert([
					'new_packagings_id' => $id,
					'comment_content' => $inserted_item->comment,
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s'),
				]);

			$notif_config = [
				'content' => CRUDBooster::myName() . ' added an item in Packaging Sourcing: ' . $inserted_item->item_description,
				'id_cms_users' => $this->to_notify,
				'to' => CRUDBooster::mainPath("detail/$inserted_item->id"),
			];

			CRUDBooster::sendNotification($notif_config);
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
	        DB::table('new_packagings')->where('id', $id)->update(['status' => 'INACTIVE']);

	    }

	    //By the way, you can still create your own method in here... :) 

		public function getDetail($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data['item'] = self::getSourcingDetails($id);

			$data['rnd_count'] = DB::table('rnd_menu_packagings_details')
					->where('status', 'ACTIVE')
					->where('new_packagings_id', $id)
					->get()
					->count();

			$data['table'] = 'new_packagings';

			$data['comments_data'] = (new AdminNewIngredientsController)->getNewItemsComments($id, true, 'new_packagings');

			$data['comment_templates'] = (new AdminNewIngredientsController)->getCommentTemplate('packaging');

			$data['item_usages'] = $this->mainController->getNewItemUsage($id, 'packaging');

			return $this->view('new-items/detail-new-packaging', $data);
		}

		public function getSourcingDetails($id) {
			$item = DB::table('new_packagings')
				->where('new_packagings.id', $id)
				->select(
					'*',
					'new_packagings.created_at as created_at',
					'new_packagings.id as new_packagings_id',
					'creator.name as creator_name',
					'updator.name as updator_name',
					'tagger.name as tagger_name',
					'new_packagings.created_at',
					'new_packagings.updated_at',
					'new_packagings.tagged_at',
					'item_masters.id as item_masters_id',
					'new_packagings.ttp as ttp',
					'new_packagings.packaging_size as packaging_size',
					'new_packagings.size as size',
					'new_packagings.budget_range as budget_range',
					'new_packagings.initial_qty_needed as initial_qty_needed',
					'initial_uoms.uom_description as initial_qty_uoms',
					'new_packagings.forecast_qty_needed as forecast_qty_needed',
					'forecast_uoms.uom_description as forecast_qty_uoms',
					'new_item_types.item_type_description',
					'packaging_types.description as packaging_description',
					'packaging_stickers.description as packaging_stickers',
					'packaging_uses.description as packaging_uses',
					'packaging_beverage_types.description as packaging_beverage',
					'packaging_material_types.description as packaging_material',
					'packaging_paper_types.description as packaging_paper',
					'packaging_designs.description as packaging_design',
				)
				->leftJoin('uoms', 'uoms.id', '=', 'new_packagings.uoms_id')
				->leftJoin('cms_users as creator', 'creator.id', '=', 'new_packagings.created_by')
				->leftJoin('cms_users as updator', 'updator.id', '=', 'new_packagings.updated_by')
				->leftJoin('cms_users as tagger', 'tagger.id', '=', 'new_packagings.tagged_by')
				->leftJoin('item_masters', 'item_masters.id', '=', 'new_packagings.item_masters_id')
				->leftJoin('new_item_types', 'new_item_types.id', '=', 'new_packagings.new_item_types_id')
				->leftJoin('packaging_types', 'packaging_types.id', '=', 'new_packagings.packaging_types_id')
				->leftJoin('packaging_stickers', 'packaging_stickers.id', '=', 'new_packagings.sticker_types_id')
				->leftJoin('packaging_uses', 'packaging_uses.id', '=', 'new_packagings.packaging_uses_id')
				->leftJoin('packaging_beverage_types', 'packaging_beverage_types.id', '=', 'new_packagings.packaging_beverage_types_id')
				->leftJoin('packaging_material_types', 'packaging_material_types.id', '=', 'new_packagings.packaging_material_types_id')
				->leftJoin('packaging_paper_types', 'packaging_paper_types.id', '=', 'new_packagings.packaging_paper_types_id')
				->leftJoin('packaging_designs', 'packaging_designs.id', '=', 'new_packagings.packaging_design_types_id')
				->leftJoin('uoms as initial_uoms', 'initial_uoms.id', '=', 'new_packagings.initial_qty_uoms_id')
				->leftJoin('uoms as forecast_uoms', 'forecast_uoms.id', '=', 'new_packagings.forecast_qty_uoms_id')
				->get()
				->first();
				return $item;

		}

		public function searchNewPackagings(Request $request) {
			$search_terms = json_decode($request->content);
			$result = DB::table('new_packagings')
				->where('new_packagings.status', 'ACTIVE')
				->where('new_packagings.item_masters_id', null)
				->where(function($query) use ($search_terms) {
					foreach ($search_terms as $search_term) {
						$query->where('new_packagings.item_description', 'like', "%{$search_term}%");
					}
				})
				->select('*', 'new_packagings.id as new_packagings_id', 'new_packagings.created_at as created_at')
				->leftJoin('uoms', 'uoms.id', '=', 'new_packagings.uoms_id')
				->get()
				->toArray();

			return json_encode($result);
		}

		public function getEdit($id) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];

			$data['item'] = DB::table('new_packagings')
				->where('new_packagings.id', $id)
				->select(
					'*',
					'created.name as created_name',
					'updated.name as updated_name',
					'new_packagings.created_at',
					'new_packagings.updated_at',
					'new_packagings.updated_at',
					'new_packagings.id as new_packagings_id',
					'packaging_types.description as packaging_description',
					'packaging_stickers.description as packaging_stickers',
					'packaging_uses.description as packaging_uses',
					'packaging_beverage_types.description as packaging_beverage',
					'packaging_material_types.description as packaging_material',
					'packaging_paper_types.description as packaging_paper',
					'packaging_designs.description as packaging_design',
				)
				->leftJoin('uoms', 'uoms.id', '=', 'new_packagings.uoms_id')
				->leftJoin('cms_users as created', 'created.id', '=', 'new_packagings.created_by')
				->leftJoin('cms_users as updated', 'updated.id', 'new_packagings.updated_by')
				->leftJoin('packaging_types', 'packaging_types.id', '=', 'new_packagings.packaging_types_id')
				->leftJoin('packaging_stickers', 'packaging_stickers.id', '=', 'new_packagings.sticker_types_id')
				->leftJoin('packaging_uses', 'packaging_uses.id', '=', 'new_packagings.packaging_uses_id')
				->leftJoin('packaging_beverage_types', 'packaging_beverage_types.id', '=', 'new_packagings.packaging_beverage_types_id')
				->leftJoin('packaging_material_types', 'packaging_material_types.id', '=', 'new_packagings.packaging_material_types_id')
				->leftJoin('packaging_paper_types', 'packaging_paper_types.id', '=', 'new_packagings.packaging_paper_types_id')
				->leftJoin('packaging_designs', 'packaging_designs.id', '=', 'new_packagings.packaging_design_types_id')
				->leftJoin('uoms as initial_uoms', 'initial_uoms.id', '=', 'new_packagings.initial_qty_uoms_id')
				->leftJoin('uoms as forecast_uoms', 'forecast_uoms.id', '=', 'new_packagings.forecast_qty_uoms_id')
				->get()
				->first();
			$data['rnd_count'] = DB::table('rnd_menu_packagings_details')
					->where('status', 'ACTIVE')
					->where('new_packagings_id', $id)
					->get()
					->count();
			
			$data['table'] = 'new_packagings';

			$data['comment_templates'] = $this->mainController->getCommentTemplate('packaging');

			$data['comments_data'] = $this->mainController->getNewItemsComments($id, true, 'new_packagings');

			$data['new_item_types'] = DB::table('new_item_types')
				->where('new_item_types.status', 'ACTIVE')
				->orderBy('item_type_description')
				->get()
				->toArray();

			$data['uoms'] = DB::table('uoms')
				->where('uoms.status', 'ACTIVE')
				->orderBy('uoms.uom_description')
				->whereNotIn('uoms.uom_description', ['LTR (LTR)', 'KILOGRAM (KGS)'])
				->get()
				->toArray();

			$data['item_usages'] = $this->mainController->getNewItemUsage($id, 'packaging');

			$submasters = self::getSubmasters();				

			if ($data['item']->item_masters_id) {
				return CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					"This item has already been tagged.", 'danger'
				);
			}
			$data = array_merge($data, $submasters);

			return $this->view('new-items/edit-new-packaging', $data);
		}

		public function submitEditNewPackaging(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);
			$new_packagings_id = $request->get('new_items_id');
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');
			DB::table('new_packagings')
				->where('new_packagings.id', $new_packagings_id)
				->update([
					'new_item_types_id' => $request->get('new_item_types_id'),
					'item_description' => strtoupper($request->get('item_description')),
					'packaging_size' => $request->get('packaging_size'),
					'uoms_id' => $request->get('uoms_id'),
					'ttp' => $request->get('ttp'),
					'target_date' => $request->get('target_date'),
					'packaging_types_id' => $request->get('packaging_types_id'),
					'sticker_types_id' => $request->get('sticker_types_id'),
					'packaging_uses_id' => $request->get('packaging_uses_id'),
					'packaging_beverage_types_id' => $request->get('packaging_beverage_types_id'),
					'packaging_material_types_id' => $request->get('packaging_material_types_id'),
					'packaging_paper_types_id' => $request->get('packaging_paper_types_id'),
					'packaging_design_types_id' => $request->get('packaging_design_types_id'),
					'size' => $request->get('size'),
					'ttp' => $request->get('ttp'),
					'budget_range' => $request->get('budget_range'),
					'initial_qty_needed' => $request->get('initial_qty_needed'),
					'initial_qty_uoms_id' => $request->get('initial_qty_uoms_id'),
					'forecast_qty_needed' => $request->get('forecast_qty_needed'),
					'forecast_qty_uoms_id' => $request->get('forecast_qty_uoms_id'),
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);
			
			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "Item details updated!"
				]);
		}

		public function getAdd() {
			$data = [];
			$submasters = self::getSubmasters();
			$data['created_at'] = date('Y-m-d');
			$data = array_merge($data, $submasters);
			return $this->view('new-items/add-new-packaging', $data);
		}

		public function getSubmasters() {

			$data = [];
			$data['uoms'] = DB::table('uoms')
				->where('uoms.status', 'ACTIVE')
				->orderBy('uoms.uom_description')
				->whereNotIn('uoms.uom_description', ['LTR (LTR)', 'KILOGRAM (KGS)'])
				->get()
				->toArray();

			$data['new_ingredient_uoms'] = DB::table('uoms')
				->where('uoms.status', 'ACTIVE')
				->whereIn('uoms.uom_code', ['PCS'])
				->get();

			$data['new_item_types'] = DB::table('new_item_types')
				->where('new_item_types.status', 'ACTIVE')
				->orderBy('item_type_description')
				->get()
				->toArray();

			$data['packaging_types'] = DB::table('packaging_types')
				->where('packaging_types.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['packaging_stickers'] = DB::table('packaging_stickers')
				->where('packaging_stickers.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['packaging_uses'] = DB::table('packaging_uses')
				->where('packaging_uses.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['packaging_beverage_types'] = DB::table('packaging_beverage_types')
				->where('packaging_beverage_types.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['packaging_material_types'] = DB::table('packaging_material_types')
				->where('packaging_material_types.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['packaging_paper_types'] = DB::table('packaging_paper_types')
				->where('packaging_paper_types.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['packaging_designs'] = DB::table('packaging_designs')
				->where('packaging_designs.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['comment_templates'] = $this->mainController->getCommentTemplate('packaging');

			$data['created_by'] = DB::table('cms_users')
				->where('id', CRUDBooster::myId())
				->get()
				->first();

			return $data;
		}

		public function getTag($id) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];

			$data['item'] = DB::table('new_packagings')
				->where('new_packagings.id', $id)
				->select(
					'*',
					'created.name as created_name',
					'updated.name as updated_name',
					'new_packagings.created_at',
					'new_packagings.updated_at',
					'new_packagings.id as new_packagings_id'
				)
				->leftJoin('uoms', 'uoms.id', '=', 'new_packagings.uoms_id')
				->leftJoin('cms_users as created', 'created.id', '=', 'new_packagings.created_by')
				->leftJoin('cms_users as updated', 'updated.id', '=', 'new_packagings.updated_by')
				->get()
				->first();

			$data['rnd_count'] = DB::table('rnd_menu_ingredients_details')
				->where('status', 'ACTIVE')
				->where('new_ingredients_id', $id)
				->get()
				->count();

			$data['table'] = 'new_packagings';

			$data['comment_templates'] = $this->mainController->getCommentTemplate('packaging');

			$data['comments_data'] = $this->mainController->getNewItemsComments($id, true, 'new_packagings');

			$data['new_item_types'] = DB::table('new_item_types')
				->where('new_item_types.status', 'ACTIVE')
				->orderBy('item_type_description')
				->get()
				->toArray();

			$data['uoms'] = DB::table('uoms')
				->where('uoms.status', 'ACTIVE')
				->orderBy('uoms.uom_description')
				->whereNotIn('uoms.uom_description', ['LTR (LTR)', 'KILOGRAM (KGS)'])
				->get()
				->toArray();

			$data['item_usages'] = $this->mainController->getNewItemUsage($id, 'ingredient');

			if ($data['item']->item_masters_id) {
				return CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					"This item has already been tagged.", 'danger'
				);
			}


			return $this->view('new-items/tag-new-item', $data);
		}

		public function tagNewPackagings(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$new_packagings_id = $request->get('new_items_id');
			$tasteless_code = $request->get('tasteless_code');
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');
			
			$item_masters_id = DB::table('item_masters')
				->where('sku_statuses_id', '!=', '2')
				->where('tasteless_code', $tasteless_code)
				->get()
				->first()
				->id;

			if (!$item_masters_id) {
				return CRUDBooster::redirect(
					CRUDBooster::mainPath('get-tag/' . $new_packagings_id),
					"I'm sorry, the tasteless code you entered is either not existing or from an inactive item.", 'danger'
				);
			} else {
				DB::table('new_packagings')
					->where('id', $new_packagings_id)
					->update([
						'item_masters_id' => $item_masters_id,
						'updated_by' => $action_by,
						'updated_at' => $time_stamp,
						'tagged_by' => $action_by,
						'tagged_at' => $time_stamp,
					]);

				DB::table('rnd_menu_packagings_details')
					->where('new_packagings_id', $new_packagings_id)
					->where('status', 'ACTIVE')
					->update([
						'updated_by' => $action_by,
						'updated_at' => $time_stamp,
						'item_masters_id' => $item_masters_id,
						'is_existing' => 'TRUE'
					]);

				DB::table('menu_packagings_details')
					->where('new_packagings_id', $new_packagings_id)
					->update([
						'updated_by' => $action_by,
						'updated_at' => $time_stamp,
						'item_masters_id' => $item_masters_id,
						'is_existing' => 'TRUE'
					]);

				return redirect(CRUDBooster::mainpath())
					->with([
						'message_type' => 'success',
						'message' => "Item successfully tagged!"
					]);
			}
			

		}

	}