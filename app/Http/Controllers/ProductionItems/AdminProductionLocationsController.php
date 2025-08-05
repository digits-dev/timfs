<?php

namespace App\Http\Controllers\ProductionItems;
 
	use DB;
	use CRUDBooster;
use App\Http\Controllers\Controller;
use App\Models\ProductionItems\ProductionLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class AdminProductionLocationsController extends \crocodicstudio\crudbooster\controllers\CBController
{
	static $requestor = [1,8];
    public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
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
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "production_locations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Production Location Description","name"=>"production_location_description"];
			$this->col[] = ["label"=>"status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name" ];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name" ];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated At","name"=>"updated_at"];  
			
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			 $this->form = [];
    
		 
				$this->form[] = ['label'=>'Production location','name'=>'production_location_description','type'=>'text','validation'=>'required|string|max:255','width'=>'col-sm-10'];
				 
				$this->form[] = [ 'label' => 'Status', 'name' => 'status', 'type' => 'select', 'validation' => 'required|string|max:20', 'width' => 'col-sm-10', 'dataenum' => 'ACTIVE;INACTIVE'];
				
			 
				
			 
			# END FORM DO NOT REMOVE THIS LINE
				
			if (CRUDBooster::getCurrentMethod() != 'getEdit') {
				$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'select','validation'=>'required|integer','width'=>'col-sm-10','datatable'=>'cms_users,name'];
				
 
				$this->form[] = ['label'=>'Updated By','name'=>'updated_by','type'=>'select','validation'=>'required|integer','width'=>'col-sm-10','datatable'=>'cms_users,name'];
				
				$this->form[] = ['label'=>'Created At', 'name'=>'created_at', 'type'=>'datetime', 'validation'=>'required|date_format:Y-m-d H:i:s', 'width'=>'col-sm-10'];
				$this->form[] = ['label'=>'Updated At', 'name'=>'updated_at', 'type'=>'datetime', 'validation'=>'required|date_format:Y-m-d H:i:s', 'width'=>'col-sm-10'];
			}
			# END FORM DO NOT REMOVE THIS LINE

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
			 

			
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	       


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

		public function hook_before_edit(&$postdata,$id) 
		{
			$postdata['updated_by'] = CRUDBooster::myId(); // sets current user ID
		}

		
		public function getAdd() {
			if (!CRUDBooster::isCreate())
				CRUDBooster::redirect(
				CRUDBooster::adminPath(),
				trans('crudbooster.denied_access')
			);

			return $this->view('production-items/add-production-location', ['location' => $location]);
		}
 

	 

		public function addProductionItemsToDB(Request $request)
		{
			
			$message = '';
			$time_stamp_now = date('Y-m-d H:i:s');
			$exists = ProductionLocation::where('production_location_description', $request['production_location_description'])->exists();
			if ($exists) {
				return back()->withErrors(['production_location_description' => 'This description already exists.']);
			}	


			if($request['id']){
				$message = "✔️ Item updated successfully...";
				$productlocation = ProductionLocation::findOrFail($request['id']);
				$time_stamp = $time_stamp_now;
				$productlocation->production_location_description = $request['production_location_description'];
				$productlocation->status = 'ACTIVE'; 
				$productlocation->updated_by = CRUDBooster::myId(); 
				$productlocation->updated_at = $time_stamp;
			}
			else
			{
				
				$message = "✔️ Item Added successfully...";
				$productlocation = new ProductionLocation();
				$time_stamp = $time_stamp_now;
				$productlocation->production_location_description = $request['production_location_description'];
				$productlocation->status = 'ACTIVE';
				$productlocation->created_by = CRUDBooster::myId();
				$productlocation->updated_by = CRUDBooster::myId();
				$productlocation->created_at = $time_stamp;
				$productlocation->updated_at = $time_stamp;		
				
				
				 DB::table('cms_logs')->insert([
					'ipaddress' => request()->ip(),
					'useragent' => request()->userAgent(),
					'url' => request()->fullUrl(),
					'description' => 'Production Location Creation',
					'details'  =>  'new Production Location named "' . $request['production_location_description'] . '" has been created',
					'id_cms_users' => CRUDBooster::myId(),
					'created_at' => now(),
					'updated_at' => now(),
				]);
			}
			
			
			$productlocation->save();

			return redirect(CRUDBooster::mainpath())
				->with([
					'message_type' => 'success',
					'message' => $message,
				])->send();

		}
 
        public function addProductionItems(){
		 
			return view('production-items/add-production-location');
		}
}