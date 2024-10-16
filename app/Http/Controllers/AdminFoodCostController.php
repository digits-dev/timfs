<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class AdminFoodCostController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function __construct()
	{
		DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
	}

	function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "segment_column_name";
		$this->limit = "20";
		$this->orderby = "id,desc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "button_icon";
		$this->button_add = false;
		$this->button_edit = true;
		$this->button_delete = true;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "menu_segmentations";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "Created By", "name" => "created_by"];
		$this->col[] = ["label" => "Segment Column Code", "name" => "segment_column_code"];
		$this->col[] = ["label" => "Segment Column Description", "name" => "segment_column_description"];
		$this->col[] = ["label" => "Segment Column Name", "name" => "segment_column_name"];
		$this->col[] = ["label" => "Status", "name" => "status"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'Created By', 'name' => 'created_by', 'type' => 'number', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Segment Column Code', 'name' => 'segment_column_code', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Segment Column Description', 'name' => 'segment_column_description', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Segment Column Name', 'name' => 'segment_column_name', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Status', 'name' => 'status', 'type' => 'text', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Updated By', 'name' => 'updated_by', 'type' => 'number', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
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
	public function actionButtonSelected($id_selected, $button_name)
	{
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
		//Your code here

	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */
	public function hook_row_index($column_index, &$column_value)
	{
		//Your code here
	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	public function hook_before_add(&$postdata)
	{
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
	public function hook_before_edit(&$postdata, $id)
	{
		//Your code here

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
		//Your code here 

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
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_delete($id)
	{
		//Your code here

	}



	//By the way, you can still create your own method in here... :) 

	public function getIndex($low_cost_value = 30) {
		$low_cost_value = (float) $low_cost_value;

		if (!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
		$my_privilege = CRUDBooster::myPrivilegeName();
		$data = [];

		$menu_and_concepts = self::getMyMenuAndConcepts();

		$menu_items = $menu_and_concepts['menu_query']
			->get()
			->toArray();
		
		$data['concepts'] = $menu_and_concepts['concepts'];
		$data['low_cost_value'] = $low_cost_value;
		$data['menu_items'] = $menu_items;

		return $this->view('menu-items/food-cost', $data);
	}

	public function filterByCost($concept_id, $filter, $low_cost) {
		if (!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
		
		$menu_query = self::getMyMenuAndConcepts()['menu_query'];

		if ($concept_id != 'all') {
			$concept = DB::table('menu_segmentations')->where('id', $concept_id)->first();
			$column_name = $concept->menu_segment_column_name;
			$menu_query = $menu_query
				->where($column_name, '1');
		} 

		if ($filter == 'low') {
			$menu_query = $menu_query
				->where('food_cost', '!=', null)
				->where(function($sub_query) use ($low_cost){
					$sub_query
						->where('menu_price_dine', null)
						->orWhere('menu_price_dine', '0')
						->orWhere(DB::raw('CAST(food_cost_percentage as DECIMAL(14, 2))'), '<=', (float) $low_cost);
				});
		}

		if ($filter == 'high') {
			$menu_query = $menu_query
				->where('food_cost', '!=', null)
				->where('food_cost_percentage', '>', (float) $low_cost);
		}

		if ($filter == 'no') {
			$menu_query = $menu_query
				->where(function($sub_query) {
					$sub_query
						->where('food_cost', '0')
						->orWhere('food_cost', null);
				});
		}

		$data['filter'] = $filter;
		$data['concept'] = $concept;
		$data['column_name'] = $column_name;
		$data['menu_items'] = $menu_query->get()->toArray();
		return $this->view('menu-items/cost-filtered', $data);
	}

	public function getMyMenuAndConcepts() {
		$data = [];

		$concepts = DB::table('menu_segmentations')
			->where('status', 'ACTIVE')
			->orderBy('menu_segment_column_description')
			->select('menu_segment_column_description', 'menu_segment_column_name', 'id')
			->get()
			->toArray();

		$my_privilege = CRUDBooster::myPrivilegeName();

		$segmentation_columns = DB::table('menu_segmentations')
			->where('status', 'ACTIVE')
			->orderBy('menu_segment_column_description')
			->pluck('menu_segment_column_name')
			->toArray();

		$menu_query = DB::table('menu_items')
			->where('status', 'ACTIVE')
			->whereNotNull('menu_items.tasteless_menu_code')
			->select(
				'id',
				'tasteless_menu_code',
				'status',
				'menu_price_dine',
				'menu_price_dlv',
				'menu_price_take',
				'food_cost',
				'food_cost_percentage',
				'menu_item_description',
				...$segmentation_columns
			);

		if ($my_privilege == 'Chef' || $my_privilege == 'Chef Assistant') {
			$concept_access_id = DB::table('user_concept_acess')
				->where('cms_users_id', CRUDBooster::myId())
				->get('menu_segmentations_id')
				->first()
				->menu_segmentations_id;

			$concept_query = DB::table('menu_segmentations')
				->where('status', 'ACTIVE')
				->orderBy('menu_segment_column_description')
				->where('status', 'ACTIVE')
				->whereIn('id', explode(',', $concept_access_id));

			$concepts_columns = $concept_query->pluck('menu_segment_column_name')->toArray();
			
			$menu_query->where(function($sub_query) use ($concepts_columns) {
				foreach ($concepts_columns ?? [] as $concept) {
					$sub_query->orWhere($concept, '1');
				}
			})->where('menu_items.tasteless_menu_code', '!=', null);

			$concepts = $concept_query->get()->toArray();

			if (!$concepts_columns) $menu_query->where('menu_items.id', null);

		}

		$data['concepts'] = $concepts;
		$data['menu_query'] = $menu_query;
		return $data;
	}
}
