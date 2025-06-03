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
			$this->button_edit = false;
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
	        $this->addaction = array();
			$my_privilege = CRUDBooster::myPrivilegeId();

				$this->addaction[] = [
					'title'=>'Edit',
					'url'=>CRUDBooster::mainpath('edit/[id]'),
					'icon'=>'fa fa-pencil',
					'color' => ' ',
					"showIf"=>"[status_of_approval] != '202'",
				];
		


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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				if (CRUDBooster::isSuperadmin() || in_array($my_privilege, self::$requestor)) {
					$this->index_button[] = [
						"title"=>"Add Production Location",
                        "label"=>"Add Production Location",
                        "icon"=>"fa fa-plus",
                        "color"=>"success",
                        "url"=>route('add-production-location')
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
			$location = [];
 

			if ($id) {
				$location = ProductionLocation::find($id); 
				if ($data['item']->approval_status == 202) {
					return redirect(CRUDBooster::mainpath())->with([
						'message_type' => 'danger',
						'message' => '✖️ You cannot edit a pending item...',
					]);
				}
			}
 
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