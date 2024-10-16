<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use Maatwebsite\Excel\Facades\Excel;
	use App\Exports\BatchingIngredientsExport;

	class AdminBatchingIngredientsController extends \crocodicstudio\crudbooster\controllers\CBController {
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->recurse_count = 0;
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
			$this->table = "batching_ingredients";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"BI Code","name"=>"bi_code"];
			$this->col[] = ["label"=>"Prepared by","name"=>"batching_ingredients_prepared_by_id","join"=>"batching_ingredients_prepared_by,prepared_by"];
			$this->col[] = ["label"=>"Batching Description","name"=>"ingredient_description"];
			$this->col[] = ["label"=>"Quantity","name"=>"quantity"];
			$this->col[] = ["label"=>"UOM","name"=>"uoms_id","join"=>"packagings,packaging_description"];
			$this->col[] = ["label"=>"Concept","name"=>"segmentations_id","join"=>"segmentations,segment_column_description"];
			$this->col[] = ["label"=>"Total Cost","name"=>"id","join"=>"batching_ingredients_computed_food_cost,ingredient_total_cost","join_id"=>"id"];
			$this->col[] = ["label"=>"Mark Up","name"=>"mark_up_percent"];
			$this->col[] = ["label"=>"TTP","name"=>"ttp"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated At","name"=>"updated_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Ingredient Description','name'=>'ingredient_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Uoms Id','name'=>'uoms_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'uoms,id'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Ingredient Description","name"=>"ingredient_description","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Uoms Id","name"=>"uoms_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"uoms,id"];
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
			$this->addaction = array();
			
			$my_id = CRUDBooster::myId();
			$is_admin = CRUDBooster::isSuperAdmin();
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
				"showIf"=>"[created_by] == $my_id || $is_admin || [is_update] == '1'",
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
			if (CRUDBooster::getCurrentMethod() == 'getIndex') {
				$this->index_button[] = [
					'label'=>'Export Batching Ingredients',
					'url'=>"javascript:batchingIngredientsExport()",
					'icon'=>'fa fa-download'
				];
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
	        $this->script_js = "
				function batchingIngredientsExport() {
					$('#modal-batching-ingredients-export').modal('show');
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
	        $this->post_index_html = "
			<div class='modal fade' tabindex='-1' role='dialog' id='modal-batching-ingredients-export'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button class='close' aria-label='Close' type='button' data-dismiss='modal'>
								<span aria-hidden='true'>×</span></button>
							<h4 class='modal-title'><i class='fa fa-download'></i> Export Batching Ingredients</h4>
						</div>

						<form method='post' target='_blank' action=".CRUDBooster::mainpath("export-batching-ingredients").">
                        <input type='hidden' name='_token' value=".csrf_token().">
                        ".CRUDBooster::getUrlParameters()."
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Batching Ingredients "." - ".date('Y-m-d H:i:s')."'/>
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
			self::updateTtp();
			$my_concepts = (new AdminRndMenuItemsController)->getMyConcepts();
			$my_concepts = implode(',', $my_concepts);
			$is_update = (new AdminRndMenuItemsController)->isEdit();
			$query->where('batching_ingredients.status', 'ACTIVE');

			if ($is_update) {
				$query->addSelect(DB::raw("
					CASE
					WHEN batching_ingredients.segmentations_id IN ($my_concepts) THEN '1'
					ELSE '0'
					END AS is_update
				"));
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
			if ($column_index == 9) {
				$column_value = (float) $column_value . '%';
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
	        DB::table('batching_ingredients')->where('id', $id)->update(['status' => 'INACTIVE']);

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

			$data = [];
			$data['item'] = DB::table('batching_ingredients')
				->where('batching_ingredients.id', $id)
				->select(
					'*',
					'batching_ingredients.created_at',
					'batching_ingredients.status',
				)
				->leftJoin('cms_users', 'cms_users.id', 'batching_ingredients.created_by')
				->leftJoin('batching_ingredients_computed_food_cost', 'batching_ingredients_computed_food_cost.id', '=', 'batching_ingredients.id')
				->leftJoin('batching_ingredients_prepared_by', 'batching_ingredients_prepared_by.id', '=', 'batching_ingredients.batching_ingredients_prepared_by_id')
				->leftJoin('packagings', 'packagings.id', '=', 'batching_ingredients.uoms_id')
				->get()
				->first();

			$ingredients = DB::table('batching_ingredients_auto_compute')
				->where('batching_ingredients_id', $id)
				->where('batching_ingredients_auto_compute.status', 'ACTIVE')
				->select('tasteless_code',
					DB::raw('COALESCE(
						item_masters.tasteless_code,
						menu_items.tasteless_menu_code,
						batching_as_ingredient.bi_code,
						new_ingredients.nwi_code
					) AS item_code'),
					'menu_items.status as menu_item_status',
					'sku_statuses.sku_status_description as item_status',
					'new_ingredients.status as new_ingredient_status',
					'batching_as_ingredient.status as batching_ingredient_status',
					'batching_ingredients_auto_compute.item_masters_id',
					'batching_ingredients_auto_compute.menu_item_description',
					'batching_as_ingredient.ingredient_description',
					'tasteless_menu_code',
					'ingredient_name',
					'prep_qty',
					'ingredient_group',
					'row_id',
					'is_primary',
					'is_selected',
					'batching_ingredients_auto_compute.packaging_size',
					'batching_ingredients_auto_compute.full_item_description',
					'menu_ingredients_preparations.preparation_desc',
					'ingredient_qty',
					'batching_ingredients_auto_compute.uom_description',
					'batching_ingredients_auto_compute.packaging_description',
					'yield',
					'batching_ingredients_auto_compute.ttp',
					'cost',
					'item_masters.updated_at',
					'item_masters.created_at',
					'batching_ingredients_auto_compute.item_description')
				->leftJoin('item_masters', 'batching_ingredients_auto_compute.item_masters_id', '=', 'item_masters.id')
				->leftJoin('menu_items', 'batching_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
				->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
				->leftJoin('menu_ingredients_preparations', 'batching_ingredients_auto_compute.menu_ingredients_preparations_id', '=', 'menu_ingredients_preparations.id')
				->leftJoin('new_ingredients', 'new_ingredients.id', '=', 'batching_ingredients_auto_compute.new_ingredients_id')
				->leftJoin('batching_ingredients as batching_as_ingredient', 'batching_as_ingredient.id', '=', 'batching_ingredients_auto_compute.batching_as_ingredient_id')
				->orderby('ingredient_group', 'asc')
				->orderby('row_id', 'asc')
				->get()
				->toArray();

			$data['ingredients'] = array_map(fn ($object) => (object) array_filter((array) $object), $ingredients);

			return $this->view('new-items/batching-ingredients-detail', $data);
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

			$data['item'] = DB::table('batching_ingredients')
				->where('batching_ingredients.id', $id)
				->leftJoin(
					'batching_ingredients_computed_food_cost', 
					'batching_ingredients_computed_food_cost.id', 
					'=', 
					'batching_ingredients.id'
				)
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

			$data['prepared_bys'] = DB::table('batching_ingredients_prepared_by')
				->where('status', 'ACTIVE')
				->get()
				->toArray();

			$data['segmentations'] = DB::table('segmentations')
				->where('status', 'ACTIVE')
				->select('id', 'segment_column_description')
				->orderBy('segment_column_description')
				->get()
				->toArray();

			if ($id) {
				$data['ingredients'] = DB::table('batching_ingredients_auto_compute')
					->where('batching_ingredients_id', $id)
					->where('batching_ingredients_auto_compute.status', 'ACTIVE')
					->select(\DB::raw('item_masters.id as item_masters_id'),
						'ingredient_name',
						'batching_ingredients_computed_food_cost.ingredient_description',
						'batching_ingredients_computed_food_cost.id as batching_ingredients_id',
						'menu_as_ingredient_id',
						'batching_ingredients_auto_compute.menu_item_description',
						'is_selected',
						'is_primary',
						'is_existing',
						'batching_ingredients_auto_compute.packaging_size',
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
						'batching_ingredients_auto_compute.ttp',
						'batching_ingredients_auto_compute.ttp as ingredient_cost',
						'item_masters.full_item_description',
						'sku_status_description as item_status',
						'menu_items.status as menu_status',
						'new_ingredients.status as new_ingredient_status',
						'batching_ingredients_computed_food_cost.status as batching_ingredient_status',
						'item_masters.updated_at',
						'item_masters.created_at',
						'batching_ingredients_auto_compute.new_ingredients_id',
						'batching_ingredients_auto_compute.item_description')
					->leftJoin('item_masters', 'item_masters.id', '=', 'batching_ingredients_auto_compute.item_masters_id')
					->leftJoin('menu_items', 'batching_ingredients_auto_compute.menu_as_ingredient_id', '=', 'menu_items.id')
					->leftJoin('sku_statuses', 'item_masters.sku_statuses_id', '=', 'sku_statuses.id')
					->leftJoin('new_ingredients', 'new_ingredients.id', 'batching_ingredients_auto_compute.new_ingredients_id')
					->leftJoin('batching_ingredients_computed_food_cost', 'batching_ingredients_computed_food_cost.id', 'batching_ingredients_auto_compute.batching_as_ingredient_id')
					->orderBy('ingredient_group', 'ASC')
					->orderBy('row_id', 'ASC')
					->get()
					->toArray();

			}

			return $this->view('new-items/batching-ingredients-add', $data);
		}

		public function editBatchingIngredient(Request $request) {
			$batching_ingredients_id = $request->get('batching_ingredients_id');
			$ingredient_description = strtoupper($request->get('ingredient_description'));
			$quantity = $request->get('quantity');
			$uoms_id = $request->get('uoms_id');
			$segmentations_id = $request->get('segmentations_id');
			$batching_ingredients_ttp = $request->get('ttp');
			$mark_up_percent = $request->get('mark_up_percent');
			$batching_ingredients_prepared_by_id = $request->get('batching_ingredients_prepared_by_id');
			$ingredients = json_decode($request->get('ingredients'));
			$time_stamp = date('Y-m-d H:i:s');
			$action_by = CRUDBooster::myId();
			$max_bi_code = DB::table('batching_ingredients')->max('bi_code');
			$bi_code_int = (int) explode('-', $max_bi_code)[1] + 1;
			$bi_code = 'BI-' . str_pad($bi_code_int, 5, '0', STR_PAD_LEFT);

			if (!$batching_ingredients_id) {
				// inserting new rnd menu item and getting the id
				$batching_ingredients_id = DB::table('batching_ingredients')
					->insertGetId([
						'ingredient_description' => $ingredient_description,
						'batching_ingredients_prepared_by_id' => $batching_ingredients_prepared_by_id,
						'bi_code' => $bi_code,
						'quantity' => $quantity,
						'uoms_id' => $uoms_id,
						'segmentations_id' => $segmentations_id,
						'ttp' => $batching_ingredients_ttp,
						'mark_up_percent' => $mark_up_percent,
						'created_by' => $action_by,
						'created_at' => $time_stamp
					]);

				$message = "✔️ New Batching Ingredient Created: $ingredient_description";
			} else {
				//update details for rnd menu item
				DB::table('batching_ingredients')
					->where('id', $batching_ingredients_id)
					->update([
						'ingredient_description' => $ingredient_description,
						'batching_ingredients_prepared_by_id' => $batching_ingredients_prepared_by_id,
						'quantity' => $quantity,
						'uoms_id' => $uoms_id,
						'segmentations_id' => $segmentations_id,
						'ttp' => $batching_ingredients_ttp,
						'mark_up_percent' => $mark_up_percent,
						'updated_at' => $time_stamp,
						'updated_by' => $action_by
					]);

				$message = "✔️ Batching Ingredient Details of $ingredient_description Updated!";
			}

			//inactivating all active ingredients of rnd menu item
			DB::table('batching_ingredients_details')
				->where('status', 'ACTIVE')
				->where('batching_ingredients_id', $batching_ingredients_id)
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
					$is_existing = DB::table('batching_ingredients_details')
						->where([
							'batching_ingredients_id' => $batching_ingredients_id,
							'item_masters_id' => $ingredient['item_masters_id'],
							'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id'],
							'new_ingredients_id' => $ingredient['new_ingredients_id'],
							'batching_as_ingredient_id' => $ingredient['batching_as_ingredient_id'],
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
					DB::table('batching_ingredients_details')->updateOrInsert([
						'batching_ingredients_id' => $batching_ingredients_id,
						'item_masters_id' => $ingredient['item_masters_id'],
						'ingredient_name' => $ingredient['ingredient_name'],
						'menu_as_ingredient_id' => $ingredient['menu_as_ingredient_id'],
						'new_ingredients_id' => $ingredient['new_ingredients_id'],
						'batching_as_ingredient_id' => $ingredient['batching_as_ingredient_id'],
					], $ingredient);
				}
			}

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => $message,
				]);
		}

		public function exportBatchingIngredients(Request $request) {
			$filename = $request->input('filename');
			return Excel::download(new BatchingIngredientsExport, $filename.'.xlsx');
		}

		public function updateTtp() {
			$to_update = DB::table('batching_ingredients')
				->where('batching_ingredients.status', 'ACTIVE')
				->where('batching_ingredients.ttp', '!=', DB::raw('batching_ingredients_computed_food_cost.computed_ttp'))
				->leftJoin('batching_ingredients_computed_food_cost', 'batching_ingredients_computed_food_cost.id', 'batching_ingredients.id')
				->pluck('batching_ingredients.id')
				->toArray();

			if (!$to_update || $this->recurse_count > 10) return;

			DB::table('batching_ingredients')
				->whereIn('batching_ingredients.id', $to_update)
				->leftJoin('batching_ingredients_computed_food_cost', 'batching_ingredients_computed_food_cost.id', 'batching_ingredients.id')
				->update([
					'batching_ingredients.ttp' => DB::raw('batching_ingredients_computed_food_cost.computed_ttp')
				]);

			$this->recurse_count++;

			self::updateTtp();
		}


	}