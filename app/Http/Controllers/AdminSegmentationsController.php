<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Brand;

	class AdminSegmentationsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}
		
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "tasteless_code";
			$this->limit = "20";
			$this->orderby = "segment_column_name,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = true;
			$this->button_export = true;
			$this->table = "segmentations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Segment Column Name","name"=>"segment_column_name"];
			$this->col[] = ["label"=>"Segment Column Code","name"=>"segment_column_code"];
			$this->col[] = ["label"=>"Segment Column Description","name"=>"segment_column_description"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave','getDetail'])) {
			    $this->form[] = ['label'=>'Segment Column Code','name'=>'segment_column_code','type'=>'text','validation'=>'required|unique:segmentations|min:3|max:15','width'=>'col-sm-4'];
			    $this->form[] = ['label'=>'Segment Column Description','name'=>'segment_column_description','type'=>'text','validation'=>'required|min:3|max:50','width'=>'col-sm-4'];
				$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'ACTIVE;INACTIVE'];
			}else{
			    $this->form[] = ['label'=>'Segment Column Name','name'=>'segment_column_name','type'=>'text','readonly'=>true,'validation'=>'required|unique:segmentations|min:3|max:35','width'=>'col-sm-4'];
			    $this->form[] = ['label'=>'Segment Column Code','name'=>'segment_column_code','type'=>'text','validation'=>'required|unique:segmentations|min:3|max:3','width'=>'col-sm-4'];
			    $this->form[] = ['label'=>'Segment Column Description','name'=>'segment_column_description','type'=>'text','validation'=>'required|min:3|max:50','width'=>'col-sm-4']; 
			}
			if(CRUDBooster::getCurrentMethod() == 'getDetail'){
				$this->form[] = ["label"=>"Created By","name"=>"created_by",'type'=>'select',"datatable"=>"cms_users,name"];
				$this->form[] = ['label'=>'Created Date','name'=>'created_at', 'type'=>'datetime'];
				$this->form[] = ["label"=>"Updated By","name"=>"updated_by",'type'=>'select',"datatable"=>"cms_users,name"];
				$this->form[] = ['label'=>'Updated Date','name'=>'updated_at', 'type'=>'datetime'];
			}

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
			if(CRUDBooster::isUpdate())
	        {
	        	$this->button_selected[] = ['label'=>'Set Status ACTIVE ','icon'=>'fa fa-check-circle','name'=>'set_status_ACTIVE'];
				$this->button_selected[] = ['label'=>'Set Status INACTIVE','icon'=>'fa fa-times','name'=>'set_status_INACTIVE'];
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
			$this->table_row_color[] = ["condition"=>"[status] == 'INACTIVE'","color"=>"danger"];
	        
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
	        $this->load_js[] = asset("js/segmentation.js");
	        
	        
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
	        if($button_name == 'set_status_ACTIVE') {
				Brand::whereIn('id',$id_selected)->update([
					'status'=>'ACTIVE', 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
			}
			elseif($button_name == 'set_status_INACTIVE') {
				Brand::whereIn('id',$id_selected)->update([
					'status'=>'INACTIVE', 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
			}    
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
	    	//Your code here
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
			
			//get last segmentation
			$last_segmentation = DB::table('segmentations')->orderBy('id','DESC')->first();
			
			//item master file
			DB::statement('ALTER TABLE item_masters ADD '.$postdata["segment_column_name"].' VARCHAR(30) NOT NULL DEFAULT "X" AFTER '.$last_segmentation->segment_column_name);
			
			//approval item master file
			DB::statement('ALTER TABLE item_master_approvals  ADD '.$postdata["segment_column_name"].' VARCHAR(30) NOT NULL DEFAULT "X" AFTER '.$last_segmentation->segment_column_name);
			
			//replenistment item master file
		    DB::connection('mysql_trs')->statement('ALTER TABLE items ADD '.$postdata["segment_column_name"].' VARCHAR(30) NOT NULL DEFAULT "X" AFTER '.$last_segmentation->segment_column_name);
		    
			$segments = [
				'segmentation_column1' => $postdata["segment_column_name"],
				'segmentation_column2' => $postdata["segment_column_name"],
				'segmentation_code'    => str_replace(" ","_",$postdata["segment_column_description"]),
				'segmentation_label'   => $postdata["segment_column_description"],
				'segmentation_status'  => "ACTIVE",
				'updated_by'           => CRUDBooster::myId(),
				'updated_at'           => date('Y-m-d H:i:s')
			];
			//replenishment adding data in segmentation table
		    DB::connection('mysql_trs')->table('segmentation')->insert($segments);
			//reimbursement adding data in segmentation table
			DB::connection('mysql_trm')->table('segmentation')->insert($segments);
			
		    //disconnect connection
			DB::disconnect('mysql_trs');
			DB::disconnect('mysql_trm');

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
	    public function hook_before_edit(&$postdata,$id) 
        {        
	        //Your code here
			$postdata["updated_by"]=CRUDBooster::myId();

            //replenishment update data in segmentation table
			DB::connection('mysql_trs')->table('segmentation')->where('id',$id)->update([
				'segmentation_code'    => str_replace(" ","_",$postdata["segment_column_description"]),
				'segmentation_label'   => $postdata["segment_column_description"],
				'segmentation_status'  => $postdata["status"],
				'updated_by'           => CRUDBooster::myId(),
				'updated_at'           => date('Y-m-d H:i:s'),
			]);

			//disconnect connection
			DB::disconnect('mysql_trs');
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


	}