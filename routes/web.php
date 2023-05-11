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
use App\Http\Controllers\AdminItemMastersTempController;
use App\Http\Controllers\AdminNewIngredientsController;
use App\Http\Controllers\AdminNewPackagingsController;
use App\Http\Controllers\AdminBatchingIngredientsController;

Route::get('/', function () {
    return redirect('admin/login');
    //return view('welcome');
});

Route::group(['middleware' => ['web','\crocodicstudio\crudbooster\middlewares\CBBackend']], function() {
    
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
    
    //bulk upload fulfillment type
    Route::post('/admin/item_masters/update-items-upload','ItemUploadController@store')->name('update.imfs');
    Route::get('/admin/item_masters/update-items','ItemUploadController@create')->name('getUpdateItems');
    Route::get('/admin/item_masters/download-item-template','ItemUploadController@downloadItemTemplate')->name('downloadItemTemplate');
    
    //bulk upload segmentation
    Route::post('/admin/item_masters/upload_sku_legend','ItemSegmentationUploadController@store')->name('uploadSKULegend');
    Route::get('/admin/item_masters/upload-items-sku-legend','ItemSegmentationUploadController@create')->name('getUpdateItemsSkuLegend');
    Route::get('/admin/item_masters/download-sku-template','ItemSegmentationUploadController@downloadSKULegendTemplate')->name('downloadSKULegendTemplate');
    
    //bulk upload cost
    Route::post('/admin/item_masters/upload-items-costing','ItemPriceUploadController@store')->name('uploadCostPrice');
    Route::get('/admin/item_masters/update-items-price','ItemPriceUploadController@create')->name('getUpdateItemsPrice');
    Route::get('/admin/item_masters/download-price-template','ItemPriceUploadController@downloadPriceTemplate')->name('downloadPriceTemplate');

    //bulk upload cost price

    Route::post('/admin/item_masters/upload-items-cost-price','ItemCostPriceUploadController@store')->name('uploadCostPrice');
    Route::get('/admin/item_masters/update-items-cost-price','ItemCostPriceUploadController@create')->name('getUpdateItemsCostPrice');
    Route::get('/admin/item_masters/download-cost-price-template','ItemCostPriceUploadController@downloadPriceTemplate')->name('downloadPriceTemplate');

    //menu items
    Route::post('/admin/menu_items/edit', [AdminMenuItemsController::class, 'submitEdit'])->name('edit_menu_item');
    Route::post('/admin/food_cost/filter', [AdminFoodCostController::class, 'filterByCost'])->name('filter_by_cost');
    Route::post('/admin/experimental_menu_items/edit', [AdminExperimentalMenuItemsController::class, 'submitEdit'])->name('edit_experimental_menu_item');
    Route::post('/admin/menu_items/search', [AdminMenuItemsController::class, 'searchIngredient'])->name('search_ingredient');

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
    Route::get('/admin/rnd_menu_items/delete-rnd-menu/{id}', [AdminRndMenuItemsController::class, 'deleteRndMenuItem']);
    Route::post('/admin/for_approval_rnd_menu/edit/add-menu-item', [AdminRndMenuItemsForApprovalController::class, 'addNewMenu'])->name('add_new_menu');
    Route::post('/admin/for_approval_rnd_menu/edit/edit-menu-item/{id}', [AdminRndMenuItemsForApprovalController::class, 'editNewMenu'])->name('edit_new_menu');
    Route::post('/admin/for_approval_rnd_menu/edit/submit-costing', [AdminRndMenuItemsForApprovalController::class, 'submitCosting'])->name('submit_costing');
    Route::post('/admin/for_approval_rnd_menu/edit/approve_by_marketing', [AdminRndMenuItemsForApprovalController::class, 'approveByMarketing'])->name('approve_by_marketing');
    Route::post('/admin/for_approval_rnd_menu/edit/approve_by_accounting', [AdminRndMenuItemsForApprovalController::class, 'approveByAccounting'])->name('approve_by_accounting');
    Route::post('/admin/for_approval_rnd_menu/edit/search-temp-items', [AdminRndMenuItemsController::class, 'searchTempItems'])->name('search_temp_items');
    Route::post('/admin/for_approval_rnd_menu/edit/add-packaging', [AdminRndMenuItemsForApprovalController::class, 'addPackaging'])->name('add_packaging');
    Route::post('/admin/for_approval_rnd_menu/edit/add-comment', [AdminRndMenuItemsForApprovalController::class, 'addComment'])->name('add_rnd_comment');
    Route::post('/admin/for_approval_rnd_menu/edit/delete-comment', [AdminRndMenuItemsForApprovalController::class, 'deleteComment'])->name('delete_rnd_comment');

    //new items (new ingredients and packagings)
    Route::get('/admin/delete-new-items/{table}/{id}', [AdminNewIngredientsController::class, 'deleteNewItem']);

    Route::post('/admin/new_ingredients/search-new-ingredients', [AdminNewIngredientsController::class, 'searchNewIngredients'])->name('search_new_ingredient');
    Route::post('/admin/new_ingredients/edit-new-ingredients', [AdminNewIngredientsController::class, 'editNewIngredients'])->name('edit_new_ingredients');
    Route::post('/admin/new_ingredients/search-item-for-tagging', [AdminNewIngredientsController::class, 'searchItemForTagging'])->name('search_item_for_tagging');

    Route::post('/admin/new_ingredients/add-new-items-comments', [AdminNewIngredientsController::class, 'addNewItemsComments'])->name('add_new_items_comments');
    Route::post('/admin/new_ingredients/delete-new-items-comments', [AdminNewIngredientsController::class, 'deleteNewItemsComments'])->name('delete_new_items_comments');
    
    Route::post('/admin/new_packagings/search-new-packagings', [AdminNewPackagingsController::class, 'searchNewPackagings'])->name('search_new_packaging');
    Route::post('/admin/new_packagings/edit-new-packagings', [AdminNewpackagingsController::class, 'editNewPackagings'])->name('edit_new_packagings');
    
    // batching ingredients
    Route::post('/admin/batching_ingredients/edit-batching-ingredient', [AdminBatchingIngredientsController::class, 'editBatchingIngredient'])->name('edit_batching_ingredient');
    
});
