<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AdminAddMenuItemsController;
use App\Http\Controllers\AdminMenuItemsController;
use App\Http\Controllers\AdminFoodCostController;
use App\Http\Controllers\AdminExperimentalMenuItemsController;
use App\Http\Controllers\AdminRndMenuItemsController;
use App\Http\Controllers\AdminRndMenuItemsForApprovalController;
use App\Http\Controllers\AdminRndMenuItemsApprovedController;
use App\Http\Controllers\AdminNewIngredientsController;
use App\Http\Controllers\AdminNewPackagingsController;
use App\Http\Controllers\AdminBatchingIngredientsController;
use App\Http\Controllers\AdminItemMastersController;
use App\Http\Controllers\AdminItemApprovalController;
use App\Http\Controllers\AdminSalesPriceChangeHistoriesController;
use App\Http\Controllers\AdminItemMastersFasController;
use App\Http\Controllers\AdminItemMastersFasApprovalController;
use App\Http\Controllers\AdminFaCoaSubCategoriesController;
use App\Http\Controllers\AdminBrandsAssetsController;
use App\Http\Controllers\SystemUpdateController;

Route::get('/', function () {
    return redirect('admin/login');
    //return view('welcome');
});

Route::group(['middleware' => ['web'], 'prefix' => config('crudbooster.ADMIN_PATH')], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('view-change-password', 'AdminCmsUsersController@changePasswordView')->name('show-change-password');
        Route::post('change-password','AdminCmsUsersController@changePass');
        Route::post('waive-change-password', 'AdminCmsUsersController@waiveChangePass');
    });
});

Route::group(['middleware' => ['web','\crocodicstudio\crudbooster\middlewares\CBBackend','check.user']], function() {
    
    //menu items
    Route::get('/admin/menu_items/upload-view','AdminMenuItemsController@uploadView')->name('menu-items.view');
    Route::get('/admin/menu_items/upload-update-view','AdminMenuItemsController@uploadUpdateView')->name('menu-items.update-view');
    Route::get('/admin/menu_items/upload-template','AdminMenuItemsController@uploadTemplate')->name('menu-items.template');
    Route::get('/admin/menu_items/upload-update-template','AdminMenuItemsController@uploadUpdateTemplate')->name('menu-items.update-template');
    Route::post('/admin/menu_items/upload','AdminMenuItemsController@uploadItems')->name('menu-items.upload');
    Route::post('/admin/menu_items/export','AdminMenuItemsController@exportItems')->name('menu-items.export');
    
    //item master
    Route::get('admin/item_masters/getBrandData/{id}','AdminItemMastersController@getBrandData')->name('getBrandData');
    Route::get('admin/item_masters/getEdit/{id}','AdminItemMastersController@getEdit');
    //subcategory master
    Route::get('admin/subcategories/getCategoryCode/{id}','AdminSubcategoriesController@getCategoryCode');
    //item export
	Route::post('admin/item_masters/bartender','AdminItemMastersController@exportBartender')->name('bartender');
	Route::post('admin/item_masters/posformat','AdminItemMastersController@exportPOSFormat')->name('posformat');
	Route::post('admin/item_masters/qbformat','AdminItemMastersController@exportQBFormat')->name('qbformat');
	Route::post('admin/item_masters/item-export','AdminItemMastersController@exportItems')->name('export-items');
	
	//----added by cris 20201006----
    
    Route::get('/admin/item_masters/update-imfs','AdminItemMastersController@updateImfs')->name('updateImfs');
    Route::post('/admin/item_masters/update-imfs-upload','AdminItemMastersController@imfsUpdate')->name('update.imfs');
    
    Route::get('/admin/suppliers/update-vendor','AdminSuppliersController@updateVendor')->name('updateVendor');
    Route::post('/admin/suppliers/update-vendor-upload','AdminSuppliersController@vendorUpdate')->name('update.vendor');

    Route::get('/admin/customer_myobs/update-customer','AdminCustomerMyobsController@updateCustomer')->name('updateCustomer');
    Route::post('/admin/customer_myobs/update-customer-upload','AdminCustomerMyobsController@customerUpdate')->name('update.customer');
    //-----------------------------
	
	Route::get('admin/customer_myobs/export-trs','AdminCustomerMyobsController@customExportExcelTRS');
    
    Route::get('/admin/item_masters/upload-module','AdminItemMastersController@getUploadModule')->name('getUploadModule');
    
    Route::get('/admin/history_item_masterfile/export-ttp-history','AdminHistoryItemMasterfileController@exportSalePrice')->name('exportSalePrice');
    Route::get('/admin/history_item_masterfile/export-purchase-price-history','AdminHistoryItemMasterfileController@exportPurchasePrice')->name('exportPurchasePrice');

    Route::post('/admin/item_masters/update-items-upload','ItemUploadController@store')->name('update.imfs');
    Route::get('/admin/item_masters/update-items','ItemUploadController@create')->name('getUpdateItems');
    Route::get('/admin/item_masters/download-item-template','ItemUploadController@downloadItemTemplate')->name('downloadItemTemplate');
    
    //bulk upload fulfillment type
    Route::post('/admin/item_masters/upload_fulfillment_type','ItemFulfillmentTypeUploadController@store')->name('uploadFulfillmentType');
    Route::get('/admin/item_masters/update-items-fulfillment-type','ItemFulfillmentTypeUploadController@create')->name('getUpdateItemsFulfillmentType');
    Route::get('/admin/item_masters/download-fulfillment-type-template','ItemFulfillmentTypeUploadController@downloadFulfillmentTypeTemplate')->name('downloadFulfillmentTypeTemplate');
    
    //bulk upload segmentation
    Route::post('/admin/item_masters/upload_sku_legend','ItemSegmentationUploadController@store')->name('uploadSKULegend');
    Route::get('/admin/item_masters/upload-items-sku-legend','ItemSegmentationUploadController@create')->name('getUpdateItemsSkuLegend');
    Route::get('/admin/item_masters/download-sku-template','ItemSegmentationUploadController@downloadSKULegendTemplate')->name('downloadSKULegendTemplate');
    
    //bulk upload cost
    Route::post('/admin/item_masters/upload-items-costing','ItemPriceUploadController@store')->name('uploadItemPrice');
    Route::get('/admin/item_masters/update-items-price','ItemPriceUploadController@create')->name('getUpdateItemsPrice');
    Route::get('/admin/item_masters/download-price-template','ItemPriceUploadController@downloadPriceTemplate')->name('downloadPriceTemplate');

    //bulk upload cost price

    Route::post('/admin/item_masters/upload-items-cost-price','ItemCostPriceUploadController@store')->name('uploadCostPrice');
    Route::get('/admin/item_masters/update-items-cost-price','ItemCostPriceUploadController@create')->name('getUpdateItemsCostPrice');
    Route::get('/admin/item_masters/download-cost-price-template','ItemCostPriceUploadController@downloadPriceTemplate')->name('downloadCostPriceTemplate');

    //menu items
    Route::post('/admin/menu_items/edit', [AdminMenuItemsController::class, 'submitEdit'])->name('edit_menu_item');
    Route::post('/admin/food_cost/filter', [AdminFoodCostController::class, 'filterByCost'])->name('filter_by_cost');
    Route::post('/admin/experimental_menu_items/edit', [AdminExperimentalMenuItemsController::class, 'submitEdit'])->name('edit_experimental_menu_item');
    Route::post('/admin/menu_items/search', [AdminMenuItemsController::class, 'searchIngredient'])->name('search_ingredient');
    Route::get('/admin/menu_items/costing-detail/{id}', [AdminMenuItemsController::class, 'getCostingDetails']);
    Route::get('/admin/menu_items/detail/{id}', [AdminMenuItemsController::class, 'getDetail']);
    Route::get('/admin/menu_items/packaging-detail/{id}', [AdminMenuItemsController::class, 'getPackagingDetail']);
    Route::get('/admin/menu_items/menu-data/{id}', [AdminMenuItemsController::class, 'getMenuDataDetail']);
    Route::get('/admin/menu_items/edit/{id}/{to_edit}', [AdminMenuItemsController::class, 'getEdit']);
    Route::get('/admin/menu_items/add-non-trade-item', [AdminMenuItemsController::class, 'addNonTradeItem'])->name('add_non_trade_item');
    Route::post('/admin/menu_items/submit-non-trade-item', [AdminMenuItemsController::class, 'submitNonTradeItem'])->name('submit_non_trade_item');
    Route::post('/admin/menu_items/submit-packaging', [AdminMenuItemsController::class, 'submitPackagings'])->name('menu_item_submit_packaging');
    Route::post('/admin/menu_items/submit-costing', [AdminMenuItemsController::class, 'submitCosting'])->name('menu_item_submit_costing');
    Route::post('/admin/menu_items/submit-menu-data', [AdminMenuItemsController::class, 'submitMenuData'])->name('menu_item_submit_menu_data');
    Route::post('/admin/menu_items/export-menu-ingredients', [AdminMenuItemsController::class, 'exportMenuIngredients'])->name('export_menu_ingredients');

    Route::get('admin/food_cost/{low_cost_value}', [AdminFoodCostController::class, 'getIndex']);
    Route::get('/admin/food_cost/{concept}/{filter}/{low_cost}', [AdminFoodCostController::class, 'filterByCost'])->name('filter_by_cost');

    // add menu items
    Route::get('/admin/add_menu_items/add', [AdminAddMenuItemsController::class, 'getAdd']);
    Route::post('/add_menu_items', [AdminAddMenuItemsController::class, 'groupSku']);
    Route::get('/admin/add_menu_items/edit/{id}', [AdminAddMenuItemsController::class, 'getEdit']);
    Route::get('/admin/add_menu_items/detail/{id}', [AdminAddMenuItemsController::class, 'getDetail']);

    //rnd menu items
    Route::post('/admin/rnd_menu_items/edit', [AdminRndMenuItemsController::class, 'editRNDMenu'])->name('edit_rnd_menu');
    Route::post('/admin/rnd_menu_items/publish', [AdminRndMenuItemsController::class, 'publishRNDMenu'])->name('publish_rnd_menu');
    Route::post('/admin/rnd_menu_items/food-tasting', [AdminRndMenuItemsController::class, 'foodTastingRNDMenu'])->name('food_tasting_rnd_menu');
    Route::post('/admin/rnd_menu_items/archive', [AdminRndMenuItemsController::class, 'archiveRNDMenu'])->name('archive_rnd_menu');
    Route::get('/admin/rnd_menu_items/delete-rnd-menu/{id}', [AdminRndMenuItemsController::class, 'deleteRndMenuItem']);
    Route::post('/admin/rnd_menu_items/search-ingredients/', [AdminRndMenuItemsController::class, 'searchAllIngredients'])->name('search_all_ingredients');
    Route::post('/admin/for_approval_rnd_menu/edit/add-menu-item', [AdminRndMenuItemsForApprovalController::class, 'addNewMenu'])->name('add_new_menu');
    Route::post('/admin/for_approval_rnd_menu/edit/edit-menu-item/{id}', [AdminRndMenuItemsForApprovalController::class, 'editNewMenu'])->name('edit_new_menu');
    Route::post('/admin/for_approval_rnd_menu/edit/submit-costing', [AdminRndMenuItemsForApprovalController::class, 'submitCosting'])->name('submit_costing');
    Route::post('/admin/for_approval_rnd_menu/edit/approve_by_marketing', [AdminRndMenuItemsForApprovalController::class, 'approveByMarketing'])->name('approve_by_marketing');
    Route::post('/admin/for_approval_rnd_menu/edit/approve_by_accounting', [AdminRndMenuItemsForApprovalController::class, 'approveByAccounting'])->name('approve_by_accounting');
    Route::post('/admin/for_approval_rnd_menu/edit/search-temp-items', [AdminRndMenuItemsController::class, 'searchTempItems'])->name('search_temp_items');
    Route::post('/admin/for_approval_rnd_menu/edit/add-packaging', [AdminRndMenuItemsForApprovalController::class, 'addPackaging'])->name('add_packaging');
    Route::post('/admin/for_approval_rnd_menu/edit/add-comment', [AdminRndMenuItemsForApprovalController::class, 'addComment'])->name('add_rnd_comment');
    Route::post('/admin/for_approval_rnd_menu/edit/delete-comment', [AdminRndMenuItemsForApprovalController::class, 'deleteComment'])->name('delete_rnd_comment');
    Route::post('/admin/approved_rnd_menu/edit/add-release-date', [AdminRndMenuItemsApprovedController::class, 'addReleaseDate'])->name('add_release_date');
    Route::post('/admin/for_approval_rnd_menu/edit/return-item', [AdminRndMenuItemsForApprovalController::class, 'returnRNDMenu'])->name('return_rnd_menu');
    Route::post('/admin/approved_rnd_menu/edit/add-pos-update', [AdminRndMenuItemsApprovedController::class, 'addPosUpdate'])->name('add_pos_update');

    //ITEM SOURCING: new items (new ingredients and packagings)
    Route::get('/admin/delete-new-items/{table}/{id}', [AdminNewIngredientsController::class, 'deleteNewItem']);

    Route::post('/admin/new_ingredients/search-new-ingredients', [AdminNewIngredientsController::class, 'searchNewIngredients'])->name('search_new_ingredient');
    Route::get('/admin/new_ingredients/get-tag/{id}', [AdminNewIngredientsController::class, 'getTag']);
    Route::post('/admin/new_ingredients/tag-new-ingredient/{id}', [AdminNewIngredientsController::class, 'tagNewIngredient'])->name('tag_new_ingredient');
    Route::post('/admin/new_ingredients/search-item-for-tagging', [AdminNewIngredientsController::class, 'searchItemForTagging'])->name('search_item_for_tagging');
    Route::post('/admin/new_ingredients/submit-edit', [AdminNewIngredientsController::class, 'submitEditNewIngredient'])->name('submit_edit_new_ingredient');
    Route::get('/admin/new_ingredients/suggest-existing-ingredients', [AdminNewIngredientsController::class, 'suggestExistingIngredients'])->name('suggest_existing_ingredients');
    Route::post('/admin/new_ingredients/export', [AdminNewIngredientsController::class, 'exportData'])->name('new_ingredients.export');

    Route::post('/admin/new_ingredients/add-new-items-comments', [AdminNewIngredientsController::class, 'addNewItemsComments'])->name('add_new_items_comments');
    Route::post('/admin/new_ingredients/delete-new-items-comments', [AdminNewIngredientsController::class, 'deleteNewItemsComments'])->name('delete_new_items_comments');
    Route::get('/admin/new_ingredients/approve-or-reject/{id}', [AdminNewIngredientsController::class, 'approveOrReject']);
    Route::post('/admin/new_ingredients/approve-or-reject/', [AdminNewIngredientsController::class, 'submitApproveOrReject'])->name('new_ingredients_submit_approve_or_reject');
    Route::post('/admin/new_ingredients/submit-sourcing-status/{id}', [AdminNewIngredientsController::class, 'submitSourcingStatus'])->name('new_ingredients_submit_sourcing_status');
    
    Route::post('/admin/new_packagings/search-new-packagings', [AdminNewPackagingsController::class, 'searchNewPackagings'])->name('search_new_packaging');
    Route::get('/admin/new_packagings/approve-or-reject/{id}', [AdminNewPackagingsController::class, 'approveOrReject']);
    Route::post('/admin/new_packagings/approve-or-reject', [AdminNewPackagingsController::class, 'submitApproveOrReject'])->name('new_packagings_submit_approve_or_reject');
    Route::get('/admin/new_packagings/get-tag/{id}', [AdminNewPackagingsController::class, 'getTag']);
    Route::post('/admin/new_packagings/tag-new-packaging/{id}', [AdminNewPackagingsController::class, 'tagNewPackagings'])->name('tag_new_packaging');
    Route::post('/admin/new_packagings/submit-edit', [AdminNewpackagingsController::class, 'submitEditNewPackaging'])->name('submit_edit_new_packaging');
    Route::post('/admin/new_packagings/submit-sourcing-status/{id}', [AdminNewpackagingsController::class, 'submitSourcingStatus'])->name('new_packagings_submit_sourcing_status');
    Route::post('/admin/new_packagings/export', [AdminNewpackagingsController::class, 'exportData'])->name('new_packagings.export');

    
    // batching ingredients
    Route::post('/admin/batching_ingredients/edit-batching-ingredient', [AdminBatchingIngredientsController::class, 'editBatchingIngredient'])->name('edit_batching_ingredient');
    Route::post('/admin/batching_ingredients/export-batching-ingredients', [AdminBatchingIngredientsController::class, 'exportBatchingIngredients'])->name('export_batching_ingredients');

    //item master
    Route::post('/admin/item_masters/submit-add-or-edit', [AdminItemMastersController::class, 'submitAddOrEdit'])->name('item_maters_submit_add_or_edit');
    Route::post('/admin/item_masters/approve-or-reject', [AdminItemApprovalController::class, 'approveOrReject'])->name('item_maters_approve_or_reject');
    Route::post('/admin/item_master_approvals/submit-edit', [AdminItemApprovalController::class, 'submitEdit'])->name('item_mater_approvals_submit_edit');
    Route::get('/admin/item_approval/approve_or_reject/{id}', [AdminItemApprovalController::class, 'getApproveOrReject']);
    Route::get('/admin/item_masters/get/{table}', [AdminItemMastersController::class, 'getAjaxSubmaster'])->name('getAjaxSubmaster');

    //sales price change history
    Route::post('/admin/sales_price_change_histories/export-history', [AdminSalesPriceChangeHistoriesController::class, 'exportDataHistory'])->name('sales_price_change_histories_export_data');

    //item master FA
    Route::post('/admin/item_masters_fas/submit-add-or-edit', [AdminItemMastersFasController::class, 'submitAddOrEdit'])->name('item_maters_fa_submit_add_or_edit');
    Route::post('/admin/item_masters_fas/approve-or-reject', [AdminItemMastersFasApprovalController::class, 'approveOrReject'])->name('item_maters_fa_approve_or_reject');
    Route::post('/admin/item_masters_fas_approvals/submit-edit', [AdminItemMastersFasApprovalController::class, 'submitEdit'])->name('item_mater_fa_approvals_submit_edit');
    Route::get('/admin/item_masters_fas_approvals/approve_or_reject/{id}', [AdminItemMastersFasApprovalController::class, 'getApproveOrReject']);
    Route::post('/admin/fa_coa_sub_categories/sub-categories', [AdminFaCoaSubCategoriesController::class, 'getCategories'])->name('fetch-categories');
    //FA Export
    Route::post('admin/item_masters_fas/item-export','AdminItemMastersFasController@exportItems')->name('export-items');
    //Upload Assets
    Route::get('/admin/item_masters_fas/upload-assets', [AdminItemMastersFasController::class, 'uploadAssets']);
    Route::post('/admin/item_masters_fas/assets-upload-save',[AdminItemMastersFasController::class, 'assetsUploadSave'])->name('assets-upload-save');
    Route::get('/admin/item_masters_fas/upload-assets-template',[AdminItemMastersFasController::class, 'uploadAssetsTemplate']);
    //submaster assets upload
    Route::get('/admin/brands_assets/upload-brands', [AdminBrandsAssetsController::class, 'uploadBrand']);
    Route::post('/admin/brands_assets/brand-upload-save',[AdminBrandsAssetsController::class, 'brandUploadSave'])->name('brand-upload-save');

});

Route::get('/item_masters/api/get-items/{secret_key}', [AdminItemMastersController::class, 'getUpdatedItems'])->name('get_updated_items');
Route::get('/item_masters_fas/api/get-items/{secret_key}', [AdminItemMastersFasController::class, 'getUpdatedItems'])->name('get_updated_items');