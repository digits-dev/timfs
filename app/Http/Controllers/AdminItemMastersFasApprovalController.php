<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminItemMastersFasApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {
		public function __construct() {
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
			$this->main_controller = new AdminItemMastersFasController;
			$this->requestor = ['Purchasing Staff', 'Purchasing Encoder', 'Encoder'];
			$this->approver = ['Purchasing Manager', 'Manager (Purchaser)'];
		}
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "image_filename";
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
			$this->table = "item_masters_fas_approvals";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Action Type","name"=>"action_type"];
			$this->col[] = ["label" => "Display Photo", "name" => "image_filename","visible" =>  true];
			$this->col[] = ["label" => "Approval Status", "name" => "approval_status"];
			$this->col[] = ["label"=>"Tasteless Code","name"=>"tasteless_code"];
			$this->col[] = ["label"=>"Item Description","name"=>"item_description"];
			$this->col[] = ["label"=>"Coa","name"=>"categories_id","join"=>"fa_coa_categories,description"];
			$this->col[] = ["label"=>"Sub category","name"=>"subcategories_id","join"=>"fa_sub_categories,description"];
			$this->col[] = ["label"=>"Cost","name"=>"cost"];
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
			$to_edit = in_array($my_privilege, $this->requestor) || CRUDBooster::isSuperAdmin();
			$to_approve = in_array($my_privilege, $this->approver) || CRUDBooster::isSuperAdmin();

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
	        $my_privilege = CRUDBooster::myPrivilegeName();



			$query
				->whereIn('item_masters_fas_approvals.approval_status', ['200', '202', '400'])
				->where(function($sub) {
					$sub
						->where('item_masters_fas_approvals.created_at', '>=', date('2023-06-07'))
						->orWhere('item_masters_fas_approvals.updated_at', '>=', date('2023-06-07'));
				});

			if (in_array($my_privilege, $this->requestor)) {
				$my_id = CRUDBooster::myId();
				$query->whereRaw("
					(item_masters_fas_approvals.action_type = 'CREATE' and item_masters_fas_approvals.created_by = $my_id)
					or
					(item_masters_fas_approvals.action_type = 'UPDATE' and item_masters_fas_approvals.updated_by = $my_id)
				");
			} else if (in_array($my_privilege, $this->approver)) {
				$query->where('item_masters_fas_approvals.approval_status', '202');
			}

			$query
				->orderBy(DB::raw('
					CASE 
						WHEN item_masters_fas_approvals.approval_status = "202" THEN 1
						ELSE 2 
					END
				'))
				->orderBy(DB::raw('COALESCE(item_masters_fas_approvals.updated_at, item_masters_fas_approvals.created_at)'), 'desc');
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	if ($column_index == 3 && $column_value) {
				$column_value = '<image class="item-master-image" src="'. asset("img/item-master-fa/$column_value") . '" data-action="zoom"/>';
			}

			else if ($column_index == 4) {
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

		public function getEdit($id) {
			$my_privilege = CRUDBooster::myPrivilegeName();
			$to_edit = in_array($my_privilege, $this->requestor) || CRUDBooster::isSuperAdmin();
			if (!CRUDBooster::isUpdate() || !$to_edit)
					CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				); 

			$data = [];
			$data['item'] = DB::table('item_masters_fas_approvals')
				->where('id', $id)
				->get()
				->first();

			$data['action'] = 'edit';

			$data['item_masters_approvals_id'] = $id;

			$data['from'] = 'item_masters_fas_approvals';

			if ($data['item']->approval_status == 202) {
				return redirect(CRUDBooster::mainpath())->with([
					'message_type' => 'danger',
					'message' => '✖️ You cannot edit a pending item...',
				]);
			}
			
			$submaster_details = $this->main_controller->getSubmasters();

			$data = array_merge($data, $submaster_details);

			return $this->view('item-master-fa/add-edit', $data);
		}

		public function getApproveOrReject($id) {
			$my_privilege = CRUDBooster::myPrivilegeName();
			$to_approve = in_array($my_privilege, $this->approver) || CRUDBooster::isSuperAdmin();
			if (!CRUDBooster::isUpdate() || !$to_approve)
					CRUDBooster::redirect(
					CRUDBooster::adminPath(),
					trans('crudbooster.denied_access')
				);
			
			$submaster_details = $this->main_controller->getSubmasters();
			$data = [];

			$item_for_approval = DB::table('item_masters_fas_approvals')
				->where('id', $id)
				->get()
				->first();
			$data['item'] = $item_for_approval;
			$data = array_merge($data, $submaster_details);
			return $this->view('item-master-fa/approve-fa-item', $data);
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

				$item_id = $item->id;

				if ($item->approval_status != '202') {
					continue;
				}

				$tasteless_code = $item->tasteless_code;
				if (!$tasteless_code) {
					$groups_id = $item->groups_id;
					$group = Group::find($groups_id);
					$tasteless_code = $this->main_controller->getTastelessCode($group);
					$item->tasteless_code = $tasteless_code;
				}

				if ($action == 'approve') {
					$differences = self::getUpdatedDetails($id);
					$paired_differences = $differences['paired_differences'] ?? [];

					if (array_key_exists('ttp_price_effective_date', $paired_differences)) {
						if ($paired_differences['ttp_price_effective_date']['new'] < date('Y-m-d') && $paired_differences['ttp_price_effective_date']['new']) {
							continue;
						}
					}

					ItemMasterApproval::where('id', $id)->update([
						'approval_status' => '200',
						'tasteless_code' => $tasteless_code,
						'approved_by_1' => $action_by,
						'approved_at_1' => $time_stamp,
					]);

					$item = DB::table('item_master_approvals')
						->where('id', $id)
						->first();

					unset($item->id);

					$inserted_item = ItemMaster::updateOrCreate(
						['tasteless_code' => $item->tasteless_code],
						(array) $item,
					);

					$details_of_item = '<table class="table table-striped"><thead><tr><th>Column Name</th><th>Old Value</th><th>New Value</th></thead><tbody>';
					$new_values = $differences['new_values'];
					$old_values = $differences['old_values'];
					if ($old_values && $new_values) {
						if ((float) $old_values->ttp != (float) $new_values->ttp_price_change ||
							(float) $old_values->ttp != (float) $new_values->ttp ||
							$old_values->ttp_price_effective_date != $new_values->ttp_price_effective_date
						) {
							$sales_price_change_history = [
								'tasteless_code' => $tasteless_code,
								'sales_price' => $old_values->ttp,
								'sales_price_change' => $new_values->ttp_price_change,
								'effective_date' => $new_values->ttp_price_effective_date,
								'status' => 'APPROVED',
								'created_by' => $new_values->updated_by,
								'created_at' => $new_values->updated_at,
								'approved_at' => $time_stamp,
								'approved_by' => $action_by,
							];
							SalesPriceChangeHistory::insert($sales_price_change_history);
						}
					}

					if ($paired_differences || !$old_values) {
						foreach ($paired_differences  as $column_name => $paired_difference) {
							$details_of_item .= "<tr><td>".$column_name."</td><td>".$paired_difference['current']."</td><td>".$paired_difference['new']."</td></tr>";
						}
	
						$details_of_item .= '</tbody></table>';

						if (!$old_values) $details_of_item = 'NEW ITEM';
	
						DB::table('history_item_masterfile')->insert([
							'tasteless_code' =>	$inserted_item->tasteless_code,
							'item_id' => $old_values->id ?? $inserted_item->id,
							'brand_id' => $inserted_item->brands_id,
							'group_id' => $inserted_item->groups_id,
							'action' => $inserted_item->action_type,
							'brand_id' => $inserted_item->brands_id,
							'ttp' => $inserted_item->ttp,
							'ttp_percentage' => $inserted_item->ttp_percentage,
							'old_ttp' => $old_values->ttp,
							'old_ttp_percentage' => $old_values->ttp_percentage,
							'purchase_price' => $inserted_item->purchase_price,
							'old_purchase_price' => $old_values->purchase_price,
							'details' => $details_of_item,
							'created_by' => $inserted_item->created_by,
							'updated_by' => $inserted_item->updated_by,
							'updated_at' => $inserted_item->updated_at
						]);
					}

					if (array_key_exists('ttp', $paired_differences) || !$old_values) {
						DB::table('history_ttps')
							->insert([
								'tasteless_code' => $inserted_item->tasteless_code,
								'item_id' => $old_values->id ?? $inserted_item->id,
								'brand_id' => $inserted_item->brands_id,
								'ttp' => $inserted_item->ttp,
								'ttp_percentage' => $inserted_item->ttp_percentage,
								'created_at' => $time_stamp,
							]);
					}

					if (array_key_exists('purchase_price', $paired_differences) || !$old_values) {
						DB::table('history_purchase_prices')
							->insert([
								'tasteless_code' => $inserted_item->tasteless_code,
								'item_id' => $old_values->id ?? $inserted_item->id,
								'brand_id' => $inserted_item->brands_id,
								'purchase_price' => $inserted_item->purchase_price,
								'currencies_id' => $inserted_item->currencies_id,
								'created_at' => $time_stamp
							]);
					}

					if (array_key_exists('landed_cost', $paired_differences) || !$old_values) {
						DB::table('history_landed_costs')
							->insert([
								'tasteless_code' => $inserted_item->tasteless_code,
								'item_id' => $old_values->id ?? $inserted_item->id,
								'brand_id' => $inserted_item->brands_id,
								'landed_cost' => $inserted_item->landed_cost,
								'created_at' => $time_stamp
							]);
					}

				} else if ($action == 'reject') {
					ItemMasterApproval::where('id', $id)->update([
						'approval_status' => '400'
					]);
				} 

				$notif_config = [
					'content' => ($action == 'approve' ? 'Approved: ' : 'Rejected: ') . $item->full_item_description . ' has been ' . ($action == 'approve' ? 'approved' : 'rejected') . ' by ' . CRUDBooster::myName(),
					'id_cms_users' => [($item->updated_by ?? $item->created_by)],
					'to' => CRUDBooster::mainPath("detail/$item_id"),
				];
	
				CRUDBooster::sendNotification($notif_config);
					
			}

			if ($action == 'approve') {
				$message_type = 'success';
				$message = '✔️ Item successfully approved.';
				self::updateSalesPrice();
			} else if ($action == 'reject') {
				$message_type = 'success';
				$message = '✖️ Item successfully rejected.';
			}

			return CRUDBooster::redirect(
				CRUDBooster::mainPath(), 
				$message, 
				$message_type
			)->send();
		}
	}