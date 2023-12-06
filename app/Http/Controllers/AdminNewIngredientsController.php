<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Request as Input;
	use DB;
	use CRUDBooster;
	use App\ItemMaster;
	use Intervention\Image\Facades\Image;
	use Spatie\ImageOptimizer\OptimizerChainFactory;
	use Illuminate\Support\Str;

	class AdminNewIngredientsController extends \crocodicstudio\crudbooster\controllers\CBController {
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
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
			$this->table = "new_ingredients";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Item Type","name"=>"new_item_types_id","join"=>"new_item_types,item_type_description"];
			$this->col[] = ["label"=>"NWI Code","name"=>"nwi_code"];
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
			//$this->form[] = ['label'=>'Item Description','name'=>'item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'Item Type','name'=>'new_item_types_id','type'=>'select','validation'=>'required','width'=>'col-sm-6','datatable'=>'new_item_types,item_type_description'];
			//$this->form[] = ['label'=>'Packaging Size','name'=>'packaging_size','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'UOM','name'=>'uoms_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'TTP','name'=>'ttp','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
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

			$my_privilege = CRUDBooster::myPrivilegeName();

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

			// if (CRUDBooster::isSuperAdmin() || $my_privilege == 'Chef' || $my_privilege == 'Chef Assistant') {
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
			$tagged_items = DB::table('new_ingredients')
				->where('new_ingredients.status', 'ACTIVE')
				->whereNotNull('new_ingredients.item_masters_id')
				->count();

			$pending_items = DB::table('new_ingredients')
				->where('new_ingredients.status', 'ACTIVE')
				->whereNull('new_ingredients.item_masters_id')
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
					function(){location.href=`$admin_path/delete-new-items/new_ingredients/` + dbId}
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
	        $this->style_css = "
				.comment-image {
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
				->leftJoin('new_items_last_comment', 'new_items_last_comment.new_ingredients_id', 'new_ingredients.id')
				->leftJoin('new_items_comments', 'new_items_comments.id', 'new_items_last_comment.new_items_comments_id')
				->leftJoin('cms_users as commenter', 'commenter.id', 'new_items_comments.created_by')
				->addSelect(
					'new_items_comments.created_at as comment_date',
					'commenter.name as comment_by',
					'new_items_comments.comment_content',
					'new_items_comments.filename as comment_image',
				)
				->where('new_ingredients.status', 'ACTIVE')
				->orderBy(DB::raw('new_ingredients.item_masters_id is null'), 'desc')
				->orderBy(DB::raw('new_ingredients.target_date is null'), 'asc')
				->orderBy('new_ingredients.target_date', 'asc');
	            
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

			// dd(Input::all());
			$max_nwi_code = DB::table('new_ingredients')->max('nwi_code');
			$nwi_code_int = (int) explode('-', $max_nwi_code)[1] + 1;
			$nwi_code = 'NWI-' . str_pad($nwi_code_int, 5, '0', STR_PAD_LEFT);
			$implodesegmentations = implode(',', Input::get('segmentations'));

			$postdata['nwi_code'] = $nwi_code;
			$postdata['segmentations'] = $implodesegmentations;
			$postdata['comment'] = Input::get('comment');
			$postdata['new_ingredient_reasons_id'] = Input::get('new_ingredient_reasons_id');
			$postdata['existing_ingredient'] = Input::get('existing_ingredient');
			$postdata['recommended_brand_one'] = Input::get('recommended_brand_one');
			$postdata['recommended_brand_two'] = Input::get('recommended_brand_two');
			$postdata['recommended_brand_three'] = Input::get('recommended_brand_three');
			$postdata['initial_qty_needed'] = Input::get('initial_qty_needed');
			$postdata['initial_qty_uoms_id'] = Input::get('initial_qty_uoms_id');
			$postdata['forecast_qty_needed'] = Input::get('forecast_qty_needed');
			$postdata['forecast_qty_uoms_id'] = Input::get('forecast_qty_uoms_id');
			$postdata['budget_range'] = Input::get('budget_range');
			$postdata['reference_link'] = Input::get('reference_link');
			$postdata['new_ingredient_terms_id'] = Input::get('new_ingredient_terms_id');
			$postdata['duration'] = Input::get('duration');
			$postdata['target_date'] = Input::get('target_date');
			$postdata['item_description'] = strtoupper($postdata['item_description']);
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
			$inserted_item = DB::table('new_ingredients')
				->where('id', $id)
				->first();

			DB::table('new_items_comments')
				->insert([
					'new_ingredients_id' => $id,
					'comment_content' => $inserted_item->comment,
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s'),
				]);

			$notif_config = [
				'content' => CRUDBooster::myName() . ' added an item in Ingredient Sourcing: ' . $inserted_item->item_description,
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
	        DB::table('new_ingredients')->where('id', $id)->update(['status' => 'INACTIVE']);

	    }



	    //By the way, you can still create your own method in here... :) 

		public function getDetail($id) {
			if (!CRUDBooster::isRead())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data['item'] = DB::table('new_ingredients')
				->where('new_ingredients.id', $id)
				->select(
					'*',
					'item.tasteless_code',
					'item_uom.uom_description',
					'new_ingredients.created_at as created_at',
					'new_ingredients.id as new_ingredients_id',
					'creator.name as creator_name',
					'updator.name as updator_name',
					'tagger.name as tagger_name',
					'new_ingredients.created_at',
					'new_ingredients.updated_at',
					'new_ingredients.tagged_at',
					'item.id as item_masters_id',
					'new_ingredients.ttp as ttp',
					'new_ingredients.packaging_size as packaging_size',
					'new_item_types.item_type_description',
					'new_ingredients.segmentations',
					'new_ingredient_reasons.description as reasons_description',
					'existing.full_item_description as existing_ingredient',
					'existing.tasteless_code as existing_ingredient_code',
					'new_ingredients.recommended_brand_one as recommended_brand_one',
					'new_ingredients.recommended_brand_two as recommended_brand_two',
					'new_ingredients.recommended_brand_three as recommended_brand_three',
					'new_ingredients.initial_qty_needed as initial_qty_needed',
					'initial_uoms.uom_description as initial_qty_uoms',
					'new_ingredients.forecast_qty_needed as forecast_qty_needed',
					'forecast_uoms.uom_description as forecast_qty_uoms',
					'new_ingredients.budget_range as budget_range',
					'new_ingredients.reference_link as reference_link', 
					'new_ingredients.duration as duration', 
					'new_ingredient_terms.description as ingredient_terms',

				)
				->leftJoin('uoms as item_uom', 'item_uom.id', '=', 'new_ingredients.uoms_id')
				->leftJoin('cms_users as creator', 'creator.id', '=', 'new_ingredients.created_by')
				->leftJoin('cms_users as updator', 'updator.id', '=', 'new_ingredients.updated_by')
				->leftJoin('cms_users as tagger', 'tagger.id', '=', 'new_ingredients.tagged_by')
				->leftJoin('item_masters as item', 'item.id', '=', 'new_ingredients.item_masters_id')
				->leftJoin('new_item_types', 'new_item_types.id', '=', 'new_ingredients.new_item_types_id')
				->leftJoin('new_ingredient_reasons', 'new_ingredient_reasons.id', '=', 'new_ingredients.new_ingredient_reasons_id')
				->leftJoin('uoms as initial_uoms', 'initial_uoms.id', '=', 'new_ingredients.initial_qty_uoms_id')
				->leftJoin('uoms as forecast_uoms', 'forecast_uoms.id', '=', 'new_ingredients.forecast_qty_uoms_id')
				->leftJoin('new_ingredient_terms', 'new_ingredient_terms.id', '=', 'new_ingredients.new_ingredient_terms_id')
				->leftJoin('item_masters as existing', 'existing.tasteless_code', '=', 'new_ingredients.existing_ingredient')
				->get()
				->first();

			$segmentations = explode(',', $data['item']->segmentations);
			$data['segmentations'] = DB::table('segmentations')
					->whereIn("segment_column_name", $segmentations)
					->pluck('segment_column_description')
					->toArray();

			$data['rnd_count'] = DB::table('rnd_menu_ingredients_details')
					->where('status', 'ACTIVE')
					->where('new_ingredients_id', $id)
					->get()
					->count();

			$data['table'] = 'new_ingredients';

			$data['comments_data'] = self::getNewItemsComments($id, true);

			$data['comment_templates'] = self::getCommentTemplate('ingredient');

			$data['item_usages'] = self::getNewItemUsage($id, 'ingredient');

			return $this->view('new-items/detail-new-ingredients', $data);
		}

		public function searchNewIngredients(Request $request) {
			$search_terms = json_decode($request->content);
			$result = DB::table('new_ingredients')
				->where('new_ingredients.status', 'ACTIVE')
				->where('new_ingredients.item_masters_id', null)
				->where(function($query) use ($search_terms) {
					foreach ($search_terms as $search_term) {
						$query->where('new_ingredients.item_description', 'like', "%{$search_term}%");
					}
				})
				->select('*', 'new_ingredients.id as new_ingredients_id', 'new_ingredients.created_at as created_at')
				->leftJoin('uoms', 'uoms.id', '=', 'new_ingredients.uoms_id')
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

			$data['item'] = DB::table('new_ingredients')
				->where('new_ingredients.id', $id)
				->select(
					'*',
					'created.name as created_name',
					'updated.name as updated_name',
					'new_ingredients.created_at',
					'new_ingredients.updated_at',
					'new_ingredients.id as new_ingredients_id',
					'new_ingredients.packaging_size',
					'new_ingredients.segmentations',
					'reasons.description as reason_description',
					'existing.tasteless_code as existing_tasteless_code',
					'existing.full_item_description as existing_item_description'
				)
				->leftJoin('uoms', 'uoms.id', '=', 'new_ingredients.uoms_id')
				->leftJoin('cms_users as created', 'created.id', '=', 'new_ingredients.created_by')
				->leftJoin('cms_users as updated', 'updated.id', '=', 'new_ingredients.updated_by')
				->leftJoin('new_ingredient_reasons as reasons', 'reasons.id', '=', 'new_ingredients.new_ingredient_reasons_id')
				->leftJoin('item_masters as existing', 'existing.tasteless_code', '=', 'new_ingredients.existing_ingredient')
				->get()
				->first();

			$data['rnd_count'] = DB::table('rnd_menu_ingredients_details')
					->where('status', 'ACTIVE')
					->where('new_ingredients_id', $id)
					->get()
					->count();

			$data['table'] = 'new_ingredients';

			$data['comment_templates'] = self::getCommentTemplate('ingredient');

			$data['comments_data'] = self::getNewItemsComments($id);

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
			
			$data['new_ingredient_reasons'] = DB::table('new_ingredient_reasons')
				->where('new_ingredient_reasons.status', 'ACTIVE')
				->orderBy('new_ingredient_reasons.description')
				->get()
				->toArray();
				
			$data['new_ingredient_uoms'] = DB::table('uoms')
				->where('uoms.status', 'ACTIVE')
				->whereIn('uoms.uom_code',['KGS', 'PCS'])
				->get()
				->toArray();
			
			$data['new_ingredient_terms'] = DB::table('new_ingredient_terms')
				->where('new_ingredient_terms.status', 'ACTIVE')
				->orderBy('description')
				->get()
				->toArray();

			$data['segmentations'] = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->orderBy('segment_column_description')
				->get()
				->toArray();
				

			$data['item_usages'] = self::getNewItemUsage($id, 'ingredient');

			if ($data['item']->item_masters_id) {
				return CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					"This item has already been tagged.", 'danger'
				);
			}


			return $this->view('new-items/edit-new-ingredients', $data);
		}

		public function submitEditNewIngredient(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$new_ingredients_id = $request->get('new_items_id');
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');

			DB::table('new_ingredients')
				->where('new_ingredients.id', $new_ingredients_id)
				->update([
					'new_item_types_id' => $request->get('new_item_types_id'),
					'item_description' => strtoupper($request->get('item_description')),
					'packaging_size' => $request->get('packaging_size'),
					'uoms_id' => $request->get('uoms_id'),
					'ttp' => $request->get('ttp'),
					'target_date' => $request->get('target_date'),
					'segmentations' => implode(',', $request->get('segmentations')),
					'new_ingredient_reasons_id' => $request->get('new_ingredient_reasons_id'),
					'existing_ingredient' => $request->get('existing_ingredient'),
					'recommended_brand_one' => $request->get('recommended_brand_one'),
					'recommended_brand_two' => $request->get('recommended_brand_two'),
					'recommended_brand_three' => $request->get('recommended_brand_three'),
					'initial_qty_needed' => $request->get('initial_qty_needed'),
					'initial_qty_uoms_id' => $request->get('initial_qty_uoms_id'),
					'forecast_qty_needed' => $request->get('forecast_qty_needed'),
					'forecast_qty_uoms_id' => $request->get('forecast_qty_uoms_id'),
					'budget_range' => $request->get('budget_range'),
					'reference_link' => $request->get('reference_link'),
					'new_ingredient_terms_id' => $request->get('new_ingredient_terms_id'),
					'duration' => $request->get('duration'),
					'updated_at' => $time_stamp,
					'updated_by' => $action_by,
				]);
			

			(new AdminMenuItemsController)->updateCostOfOtherMenu();

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => "Item details updated!"
				]);
			
		}

		public function getAdd() {

			$data = [];

			$data['uoms'] = DB::table('uoms')
				->where('uoms.status', 'ACTIVE')
				->orderBy('uoms.uom_description')
				->whereNotIn('uoms.uom_description', ['LTR (LTR)', 'KILOGRAM (KGS)'])
				->get()
				->toArray();

			$data['new_item_types'] = DB::table('new_item_types')
				->where('new_item_types.status', 'ACTIVE')
				->orderBy('item_type_description')
				->get()
				->toArray();
			
			$data['new_ingredient_segmentations'] = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->orderBy('segment_column_description')
				->get()
				->toArray();

			$data['new_ingredient_reasons'] = DB::table('new_ingredient_reasons')
				->where('new_ingredient_reasons.status', 'ACTIVE')
				->orderBy('description','asc')
				->get();

			$data['new_ingredient_uoms'] = DB::table('uoms')
				->where('uoms.status', 'ACTIVE')
				->whereIn('uoms.uom_code',['KGS', 'PCS'])
				->get();

			$data['new_ingredient_terms'] = DB::table('new_ingredient_terms')
				->where('new_ingredient_terms.status', 'ACTIVE')
				->orderBy('description','asc')
				->get();

			$data['comment_templates'] = self::getCommentTemplate('ingredient');

			$data['created_by'] = DB::table('cms_users')
				->where('id', CRUDBooster::myId())
				->get()
				->first();

			$data['created_at'] = date('Y-m-d');

			return $this->view('new-items/add-new-ingredients', $data);
		}

		public function getTag($id) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$data = [];

			$data['item'] = DB::table('new_ingredients')
				->where('new_ingredients.id', $id)
				->select(
					'*',
					'created.name as created_name',
					'updated.name as updated_name',
					'new_ingredients.created_at',
					'new_ingredients.updated_at',
					'new_ingredients.id as new_ingredients_id'
				)
				->leftJoin('uoms', 'uoms.id', '=', 'new_ingredients.uoms_id')
				->leftJoin('cms_users as created', 'created.id', '=', 'new_ingredients.created_by')
				->leftJoin('cms_users as updated', 'updated.id', '=', 'new_ingredients.updated_by')
				->get()
				->first();

			$data['rnd_count'] = DB::table('rnd_menu_ingredients_details')
				->where('status', 'ACTIVE')
				->where('new_ingredients_id', $id)
				->get()
				->count();

			$data['table'] = 'new_ingredients';

			$data['comment_templates'] = self::getCommentTemplate('ingredient');

			$data['comments_data'] = self::getNewItemsComments($id);

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

			$data['item_usages'] = self::getNewItemUsage($id, 'ingredient');

			if ($data['item']->item_masters_id) {
				return CRUDBooster::redirect(
					CRUDBooster::mainPath(),
					"This item has already been tagged.", 'danger'
				);
			}


			return $this->view('new-items/tag-new-item', $data);
		}

		public function tagNewIngredient(Request $request) {
			if (!CRUDBooster::isUpdate())
				CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);

			$new_ingredients_id = $request->get('new_items_id');
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
					CRUDBooster::mainPath('get-tag/' . $new_ingredients_id),
					"I'm sorry, the tasteless code you entered is either not existing or from an inactive item.", 'danger'
				);
			} else {
				DB::table('new_ingredients')
					->where('id', $new_ingredients_id)
					->update([
						'item_masters_id' => $item_masters_id,
						'updated_by' => $action_by,
						'updated_at' => $time_stamp,
						'tagged_by' => $action_by,
						'tagged_at' => $time_stamp,
					]);

				// updating the ingredients of rnd
				DB::table('rnd_menu_ingredients_details')
					->where('new_ingredients_id', $new_ingredients_id)
					->where('status', 'ACTIVE')
					->update([
						'updated_by' => $action_by,
						'updated_at' => $time_stamp,
						'item_masters_id' => $item_masters_id,
						'is_existing' => 'TRUE'
					]);

				
				// updating the ingredients of batching
				DB::table('batching_ingredients_details')
					->where('new_ingredients_id', $new_ingredients_id)
					->where('status', 'ACTIVE')
					->update([
						'updated_by' => $action_by,
						'updated_at' => $time_stamp,
						'item_masters_id' => $item_masters_id,
						'is_existing' => 'TRUE'
					]);
				
				//updating the ingredients of menu
				DB::table('menu_ingredients_details')
					->where('new_ingredients_id', $new_ingredients_id)
					->where('status', 'ACTIVE')
					->update([
						'updated_by' => $action_by,
						'updated_at' => $time_stamp,
						'item_masters_id' => $item_masters_id,
						'is_existing' => 'TRUE'
					]);

				(new AdminMenuItemsController)->updateCostOfOtherMenu();

				return redirect(CRUDBooster::mainpath())
					->with([
						'message_type' => 'success',
						'message' => "Item successfully tagged!"
					]);
			}
			

		}

		public function searchItemForTagging(Request $request) {
			$tasteless_code = $request->get('tasteless_code');

			$response = DB::table('item_masters')
				->where('tasteless_code', $tasteless_code)
				->get()
				->first();

			return json_encode($response);
		}

		public function getNewItemsComments($id, $to_comment = true, $table = 'new_ingredients') {
			$data = [];

			$item = DB::table($table)
				->where($table . '.id', $id)
				->get()
				->first();

			$data['comments'] = DB::table('new_items_comments')
				->where("new_items_comments.$table" . '_id', $id)
				->where('new_items_comments.status', 'ACTIVE')
				->select(
					'cms_users.name', 
					'cms_users.id as cms_users_id', 
					'new_items_comments.created_at as comment_added_at', 
					'new_items_comments.id as comment_id',
					'new_items_comments.filename',
					'new_items_comments.comment_content',
				)
				->leftJoin('cms_users', 'new_items_comments.created_by', '=', 'cms_users.id')
				->orderBy('comment_added_at', 'ASC')
				->get()
				->toArray();

			$data['new_items_id'] = $id;

			$data['table'] = $table;

			$data['item_description'] = ($item->item_description);

			$data['to_comment'] = $to_comment;

			return $data;
		}

		public function addNewItemsComments(Request $request) {
			$comment_content = $request['comment_content'];
			$table = $request['table'];
			$new_items_id = $request['new_items_id'];
			$attached_image = $request['attached_image'];
			$action_by = CRUDBooster::myId();
			$time_stamp = date('Y-m-d H:i:s');


			if ($attached_image) {
				$filename_filler = Str::random(10);
				$img_file = $attached_image;
				$filename = date('Y-m-d') . "-$filename_filler." . $img_file->getClientOriginalExtension();
				$image = Image::make($img_file);
				
				$image->resize(1024, 768, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
	
				// Save the resized image to the public folder
				$image->save(public_path('img/item-sourcing/' . $filename));
				// Optimize the uploaded image
				$optimizerChain = OptimizerChainFactory::create();
				$optimizerChain->optimize(public_path('img/item-sourcing/' . $filename));
			}

			$inserted_id = DB::table('new_items_comments')
				->insertGetId([
					$table . '_id' => $new_items_id,
					'comment_content' => $comment_content,
					'filename' => $filename,
					'created_by' => $action_by,
					'created_at' => $time_stamp,
				]);

			$response = DB::table('new_items_comments')
				->where('new_items_comments.id', $inserted_id)
				->leftJoin('cms_users', 'new_items_comments.created_by', '=', 'cms_users.id')
				->select(
					'cms_users.name', 
					'cms_users.id as cms_users_id', 
					'new_items_comments.created_at as comment_added_at', 
					'new_items_comments.id as comment_id',
					'new_items_comments.filename',
					'new_items_comments.comment_content',
				)
				->get()
				->first();

			$new_item_details = DB::table($table)
				->where('id', $new_items_id)
				->get()
				->first();

			$to_notify = [...$this->to_notify, $new_item_details->created_by];

			$to_notify = array_filter($to_notify, fn ($e) => $e != CRUDBooster::myId());

			$notif_config = [
				'content' => 'New comment: ' . CRUDBooster::myName() . ' added a new comment for item: ' . $new_item_details->item_description,
				'to' => CRUDBooster::adminPath("$table/detail/$new_items_id"),
				'id_cms_users' => $to_notify,
			];

			CRUDBooster::sendNotification($notif_config);

			return json_encode([$response]);
		}

		public function deleteNewItemsComments(Request $request) {
			$comment_id = $request->comment_id;
			$time_stamp = date('Y-m-d H:i:s');

			$response = DB::table('new_items_comments')
				->where('id', $comment_id)
				->update([
					'status' => 'INACTIVE',
					'deleted_at' => $time_stamp,
				]);

			return json_encode($response);
		}

		public function deleteNewItem($table, $id) {
			DB::table($table)
				->where('id', $id)
				->update([
					'status' => 'INACTIVE',
					'deleted_at' => date('Y-m-d H:i:s')
				]);

			return redirect()
				->back()
				->with([
					'message_type' => 'success',
					'message' => "✔️ Item Deleted!"
				]);
		}

		function getCommentTemplate($type) {
			if ($type == 'ingredient') {
				$additional_fields = [
					'SEGMENTATION',
					'REASON (NEW MENU | REPLACEMENT | NEW CONCEPT | RND)',
					'[IF REPLACEMENT] EXISTING INGREDIENT',
					'RECOMMENDED BRAND 1',
					'RECOMMENDED BRAND 2',
					'RECOMMENDED BRAND 3',
					'INITIAL QTY NEEDED (IN KG | PC)',
					'FORECAST QTY NEEDED PER MONTH (IN KG | PC)',
					'BUDGET RANGE',
					'REFERENCE LINKS',
					'TARGET DATE NEEDED',
					'ONE-TIME | REGULAR | SEASONAL',
					'DURATION',
				];
			} else if ($type == 'packaging') {
				$additional_fields = [
					'TYPE (TAKEOUT CONTAINER | STICKER LABEL)',
					'[IF STICKER] STICKER MATERIAL (SATIN | MATTE | LAMINATED | VINYL)',
					'PACKAGING FOR (FOOD | BEVERAGE | NA)',
					'[IF BEVERAGE] BEVERAGE PACKAGING TYPE (CAP | CAP WITH LID | STRAW)',
					'MATERIAL (PAPER | PLASTIC)',
					'[IF PAPER] PAPER TYPE (CRAFT | CORRUGATED)',
					'DESIGN (GENERIC | CUSTOM)',
					'SIZE',
					'BUDGET RANGE',
					'INITIAL QTY NEEDED (IN PC)',
					'FORECAST QTY NEEDED PER MONTH (IN PC)',
					'TARGET DATE NEEDED',
				];
			}
			return $additional_fields;
		}

		function getNewItemUsage($new_item_id, $item_type) {
			if ($item_type == 'ingredient') {
				$menu_items = DB::table('menu_ingredients_details')
					->where('menu_ingredients_details.status', 'ACTIVE')
					->where('menu_ingredients_details.new_ingredients_id', $new_item_id)
					->whereNull('menu_ingredients_details.item_masters_id')
					->whereNotNull('menu_items.tasteless_menu_code')
					->where('menu_items.status', 'ACTIVE')
					->select(
						'menu_items.tasteless_menu_code as item_code',
						'menu_items.menu_item_description as item_description',
						'cms_users.name'
					)
					->leftJoin('menu_items', 'menu_items.id', '=', 'menu_ingredients_details.menu_items_id')
					->leftJoin('cms_users', 'cms_users.id', '=', DB::raw('COALESCE(menu_ingredients_details.updated_by, menu_ingredients_details.created_by)'))
					->orderBy('item_description')
					->get()
					->toArray();

				$rnd_menu_items = DB::table('rnd_menu_ingredients_details')
					->where('rnd_menu_ingredients_details.status', 'ACTIVE')
					->where('rnd_menu_ingredients_details.new_ingredients_id', $new_item_id)
					->whereNull('rnd_menu_ingredients_details.item_masters_id')
					->where('rnd_menu_items.status', 'ACTIVE')
					->select(
						'rnd_menu_items.rnd_code as item_code',
						'rnd_menu_items.rnd_menu_description as item_description',
						'cms_users.name'
					)
					->leftJoin('rnd_menu_items', 'rnd_menu_items.id', '=', 'rnd_menu_ingredients_details.rnd_menu_items_id')
					->leftJoin('cms_users', 'cms_users.id', '=', DB::raw('COALESCE(rnd_menu_ingredients_details.updated_by, rnd_menu_ingredients_details.created_by)'))
					->orderBy('item_description')
					->get()
					->toArray();

				$batching_items = DB::table('batching_ingredients_details')
					->where('batching_ingredients_details.status', 'ACTIVE')
					->where('batching_ingredients_details.new_ingredients_id', $new_item_id)
					->whereNull('batching_ingredients_details.item_masters_id')
					->where('batching_ingredients.status', 'ACTIVE')
					->select(
						'batching_ingredients.bi_code as item_code',
						'batching_ingredients.ingredient_description as item_description',
						'cms_users.name'
					)
					->leftJoin('batching_ingredients', 'batching_ingredients.id', '=', 'batching_ingredients_details.batching_ingredients_id')
					->leftJoin('cms_users', 'cms_users.id', '=', DB::raw('COALESCE(batching_ingredients_details.updated_by, batching_ingredients_details.created_by)'))
					->orderBy('item_description')
					->get()
					->toArray();

				return array_merge($menu_items, $rnd_menu_items, $batching_items);
			} else if ($item_type == 'packaging') {
				$menu_items = DB::table('menu_packagings_details')
					->where('menu_packagings_details.status', 'ACTIVE')
					->where('menu_packagings_details.new_packagings_id', $new_item_id)
					->whereNull('menu_packagings_details.item_masters_id')
					->whereNotNull('menu_items.tasteless_menu_code')
					->where('menu_items.status', 'ACTIVE')
					->select(
						'menu_items.tasteless_menu_code as item_code',
						'menu_items.menu_item_description as item_description',
						'cms_users.name'
					)
					->leftJoin('menu_items', 'menu_items.id', '=', 'menu_packagings_details.menu_items_id')
					->leftJoin('cms_users', 'cms_users.id', '=', DB::raw('COALESCE(menu_packagings_details.updated_by, menu_packagings_details.created_by)'))
					->orderBy('item_description')
					->get()
					->toArray();

				$rnd_menu_items = DB::table('rnd_menu_packagings_details')
					->where('rnd_menu_packagings_details.status', 'ACTIVE')
					->where('rnd_menu_packagings_details.new_packagings_id', $new_item_id)
					->whereNull('rnd_menu_packagings_details.item_masters_id')
					->where('rnd_menu_items.status', 'ACTIVE')
					->select(
						'rnd_menu_items.rnd_code as item_code',
						'rnd_menu_items.rnd_menu_description as item_description',
						'cms_users.name'
					)
					->leftJoin('rnd_menu_items', 'rnd_menu_items.id', '=', 'rnd_menu_packagings_details.rnd_menu_items_id')
					->leftJoin('cms_users', 'cms_users.id', '=', DB::raw('COALESCE(rnd_menu_packagings_details.updated_by, rnd_menu_packagings_details.created_by)'))
					->orderBy('item_description')
					->get()
					->toArray();

				return array_merge($menu_items, $rnd_menu_items);
			}
		}

		public function suggestExistingIngredients(Request $request){
			$term = $request->input('term');
			$suggestions = ItemMaster::where('item_masters.sku_statuses_id', 1)
				->whereNotNull('item_masters.tasteless_code')
				->whereRaw("(item_masters.tasteless_code like '%$term%' or item_masters.full_item_description like '%$term%')")
				->select('item_masters.full_item_description as text', 'item_masters.tasteless_code as id')
				->orderBy('item_masters.full_item_description', 'asc')
				->get();

			return response()->json($suggestions);
		}
	}
	// public function suggestExistingIngredients(){
	// 	$suggestions = ItemMaster::where('item_masters.sku_statuses_id', 1)
	// 		->whereNotNull('item_masters.tasteless_code')
	// 		->select('item_masters.full_item_description as text', 'item_masters.tasteless_code as id')
	// 		->orderBy('item_masters.full_item_description', 'asc')
	// 		->get();

	// 	return response()->json($suggestions);
	// }