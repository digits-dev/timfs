<?php

namespace App\Http\Controllers\ProductionItems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
	use CRUDBooster;
class AdminProductionItemsHistory extends \crocodicstudio\crudbooster\controllers\CBController {
 
       public function cbInit() {

    # START CONFIGURATION DO NOT REMOVE THIS LINE
    $this->title_field = "reference";
    $this->limit = 20;
    $this->orderby = "id,desc";
    $this->global_privilege = false;
    $this->button_table_action = true;
    $this->button_bulk_action = true;
    $this->button_action_style = "button_icon";
    $this->button_add = false;
    $this->button_edit = false;
    $this->button_delete = false;
    $this->button_detail = true;
    $this->button_show = true;
    $this->button_filter = true;
    $this->button_import = false;
    $this->button_export = false;
    $this->table = "production_items_history";
    # END CONFIGURATION DO NOT REMOVE THIS LINE

    # COLUMNS TO SHOW IN TABLE INDEX
    $this->col = [];
    $this->col[] = ['label' => 'Reference', 'name' => 'reference'];
    $this->col[] = ['label' => 'Action', 'name' => 'action'];
    $this->col[] = ["label" => "Updated By","name"=>"updated_by","join"=>"cms_users,name" ];
    $this->col[] = ['label' => 'Description', 'name' => 'description'];
    $this->col[] = ['label' => 'Created At', 'name' => 'created_at', 'callback_php' => 'date("Y-m-d H:i:s", strtotime($row->created_at))'];
    $this->col[] = ['label' => 'Updated At', 'name' => 'updated_at', 'callback_php' => 'date("Y-m-d H:i:s", strtotime($row->updated_at))'];

    # FORM FIELDS
    $this->form = [];
    $this->form[] = ['label' => 'Reference', 'name' => 'reference', 'type' => 'text', 'validation' => 'required|string|max:255', 'width' => 'col-sm-6'];
    $this->form[] = ['label' => 'Action', 'name' => 'action', 'type' => 'text', 'validation' => 'required|string|max:255', 'width' => 'col-sm-6'];
    $this->form[] = ['label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'validation' => 'required|string|max:255', 'width' => 'col-sm-12'];
    $this->form[] = [
        'label' => 'Updated By',
        'name' => 'updated_by',
        'type' => 'select2',          // or 'select'
        'datatable' => 'cms_users,name',  // tells the system to get user names from cms_users table
        'validation' => 'required|integer',
        'width' => 'col-sm-12'
    ];    
    # For 'details', you can use WYSIWYG if you want HTML or plain textarea
    $this->form[] = ['label' => 'Details', 'name' => 'details', 'type' => 'wysiwyg', 'validation' => 'nullable|string', 'width' => 'col-sm-12'];
 
    # INDEX BUTTONS
    $this->index_button = [];
    if (CRUDBooster::getCurrentMethod() == 'getIndex') {
        $this->index_button[] = ['label' => 'Export Items', 'url' => 'javascript:showItemExport()', 'icon' => 'fa fa-download'];
    }

    # SCRIPT JS
    $this->script_js = "
        function showItemExport() {
            $('#modal-items-export').modal('show');
        }
    ";

    # POST INDEX HTML (modal export)
    $this->post_index_html = "
        <div class='modal fade' tabindex='-1' role='dialog' id='modal-items-export'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button class='close' aria-label='Close' type='button' data-dismiss='modal'>
                            <span aria-hidden='true'>×</span></button>
                        <h4 class='modal-title'><i class='fa fa-download'></i> Export Items</h4>
                    </div>
                    <form method='post' target='_blank' action=" . CRUDBooster::mainpath("export-items-history") . ">
                        <input type='hidden' name='_token' value=" . csrf_token() . ">
                        " . CRUDBooster::getUrlParameters() . "
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label>File Name</label>
                                <input type='text' name='filename' class='form-control' required value='Export Assets Items - " . date('Y-m-d H:i:s') . "'/>
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

    # NO TABLE ROW COLOR OR STATISTIC ADDED
    $this->table_row_color = [];
    $this->index_statistic = [];

    # LOAD JS AND CSS
    $this->load_js = [];
    $this->load_css = [];
    $this->style_css = null;
}
public function hook_query_index(&$query) {
    $query->whereNotIn('action', ['Update Production Item lines']);
}

public function exportItemsHistory(Request $request) {
    
        $filename = $request->input('filename') . '.csv';

		$callback = function () {
			$handle = fopen('php://output', 'w');

			// Header row
            fputcsv($handle, [
            'Reference Number',
            'Item Code',
            'Action',
            'Description',
            'key',
            'Old Data', 
            'New Data', 
            'Updated By',
            'Created At',
            'Updated At',
        ]);

			 DB::table('production_items_history')
            ->leftJoin('cms_users', 'cms_users.id', '=', 'production_items_history.updated_by')
            ->select(
                'production_items_history.reference',
                'production_items_history.item_code',
                'production_items_history.action',
                'production_items_history.description',
                'production_items_history.key_old_value',
                'production_items_history.description_old_value', 
                'production_items_history.description_new_value',
                'cms_users.name as updated_by_name',  // alias for clarity
                'production_items_history.details',
                'production_items_history.created_at',
                'production_items_history.updated_at'
            )
            ->cursor()
            ->each(function ($row) use ($handle) {
                fputcsv($handle, [
                    $row->reference,
                    $row->item_code,
                    $row->action,
                    $row->description,
                    $row->key_old_value,
                    $row->description_old_value, 
                    $row->description_new_value,
                    $row->updated_by_name, // include the joined user name here
                    $row->created_at,
                    $row->updated_at,
                ]);
            });

				fclose($handle);
			};

			return response()->streamDownload($callback, $filename, [
				'Content-Type' => 'text/csv',
			]);
}

}
