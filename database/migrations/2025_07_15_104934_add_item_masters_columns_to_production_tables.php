<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemMastersColumnsToProductionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('production_items_approvals', function (Blueprint $table) {
            // Adding all item_masters columns except 'id'
            $table->string('action_type', 10)->nullable()->after('final_value_vatinc');
            $table->string('tasteless_code', 15)->nullable()->after('action_type');
            $table->unsignedInteger('suppliers_id')->nullable()->after('tasteless_code');
            $table->unsignedInteger('trademarks_id')->nullable()->after('suppliers_id');
            $table->unsignedInteger('classifications_id')->nullable()->after('trademarks_id');
            $table->string('supplier_item_code', 50)->nullable()->after('classifications_id');
            $table->string('myob_item_description', 255)->nullable()->after('supplier_item_code');
            $table->string('full_item_description', 255)->nullable()->after('myob_item_description');
            $table->unsignedInteger('brands_id')->nullable()->after('full_item_description');
            $table->unsignedInteger('groups_id')->nullable()->after('brands_id');
            $table->unsignedInteger('categories_id')->nullable()->after('groups_id');
            $table->unsignedInteger('subcategories_id')->nullable()->after('categories_id');
            $table->unsignedInteger('types_id')->nullable()->after('subcategories_id');
            $table->unsignedInteger('colors_id')->nullable()->after('types_id');
            $table->string('actual_color', 50)->nullable()->after('colors_id');
            $table->string('flavor', 30)->nullable()->after('actual_color');
            $table->decimal('packaging_size', 16, 2)->default(1.00)->after('flavor');
            $table->string('packaging_dimension', 50)->nullable()->after('packaging_size');
            $table->unsignedInteger('uoms_id')->nullable()->after('packaging_dimension');
            $table->unsignedInteger('packagings_id')->nullable()->after('uoms_id');
            $table->unsignedInteger('vendor_types_id')->nullable()->after('packagings_id');
            $table->unsignedInteger('inventory_types_id')->nullable()->after('vendor_types_id');
            $table->unsignedInteger('sku_statuses_id')->nullable()->after('inventory_types_id');
            $table->unsignedInteger('tax_codes_id')->nullable()->after('sku_statuses_id');
            $table->unsignedInteger('currencies_id')->nullable()->after('tax_codes_id');
            $table->integer('chart_accounts_id')->nullable()->after('currencies_id');
            $table->unsignedInteger('fulfillment_type_id')->nullable()->after('chart_accounts_id');
            $table->decimal('purchase_price', 18, 5)->nullable()->after('fulfillment_type_id');
            $table->decimal('ttp', 18, 5)->nullable()->after('purchase_price');
            $table->decimal('ttp_price_change', 18, 5)->nullable()->after('ttp');
            $table->date('ttp_price_effective_date')->nullable()->after('ttp_price_change');
            $table->decimal('old_ttp', 18, 5)->nullable()->after('ttp_price_effective_date');
            $table->decimal('ttp_percentage', 18, 2)->nullable()->after('old_ttp');
            $table->decimal('old_ttp_percentage', 18, 2)->nullable()->after('ttp_percentage');
            $table->decimal('ttp_percentage_price_change', 16, 5)->nullable()->after('old_ttp_percentage');
            $table->decimal('landed_cost', 18, 5)->nullable()->after('ttp_percentage_price_change');
            $table->decimal('moq_supplier', 18, 2)->nullable()->after('landed_cost');
            $table->decimal('moq_store', 18, 2)->nullable()->after('moq_supplier');
            $table->decimal('moq_value', 18, 2)->nullable()->after('moq_store');
            $table->unsignedInteger('moq_currencies_id')->nullable()->after('moq_value');
            $table->string('type', 20)->nullable()->after('moq_currencies_id');
            $table->string('item', 255)->nullable()->after('type');
            $table->integer('accounts_id')->nullable()->after('item');
            $table->integer('asset_accounts_id')->nullable()->after('accounts_id');
            $table->integer('cogs_accounts_id')->nullable()->after('asset_accounts_id');
            $table->decimal('quantity_on_hand', 18, 2)->default(0.00)->after('cogs_accounts_id');
            $table->decimal('accumulated_depreciation', 18, 2)->default(0.00)->after('quantity_on_hand');
            $table->text('tax_agency')->nullable()->after('accumulated_depreciation');
            $table->decimal('price', 18, 5)->nullable()->after('tax_agency');
            $table->decimal('reorder_pt', 18, 2)->nullable()->after('price');
            $table->text('mpn')->nullable()->after('reorder_pt');
            $table->integer('uoms_set_id')->nullable()->after('mpn');
            $table->integer('tax_status')->nullable()->after('uoms_set_id');
            $table->string('purchase_description', 255)->nullable()->after('tax_status');
            $table->text('image_filename')->nullable()->after('purchase_description');
            $table->mediumText('file_link')->nullable()->after('image_filename'); 
            $table->string('segmentation', 30)->nullable()->after('file_link');
            $table->string('segmentation_any', 30)->nullable()->after('segmentation');
            $table->string('segmentation_bbd', 30)->nullable()->after('segmentation_any');
            $table->string('segmentation_eyb', 30)->nullable()->after('segmentation_bbd');
            $table->string('segmentation_cbl', 30)->nullable()->after('segmentation_eyb');
            $table->string('segmentation_com', 30)->nullable()->after('segmentation_cbl');
            $table->string('segmentation_fmr', 30)->nullable()->after('segmentation_com');
            $table->string('segmentation_fwb', 30)->nullable()->after('segmentation_fmr');
            $table->string('segmentation_fzb', 30)->nullable()->after('segmentation_fwb');
            $table->string('segmentation_hmk', 30)->nullable()->after('segmentation_fzb');
            $table->string('segmentation_htw', 30)->nullable()->after('segmentation_hmk');
            $table->string('segmentation_kkd', 30)->nullable()->after('segmentation_htw');
            $table->string('segmentation_lps', 30)->nullable()->after('segmentation_kkd');
            $table->string('segmentation_lbs', 30)->nullable()->after('segmentation_lps');
            $table->string('segmentation_mtd', 30)->nullable()->after('segmentation_lbs');
            $table->string('segmentation_ppd', 30)->nullable()->after('segmentation_mtd');
            $table->string('segmentation_pze', 30)->nullable()->after('segmentation_ppd');
            $table->string('segmentation_psn', 30)->nullable()->after('segmentation_pze');
            $table->string('segmentation_ppr', 30)->nullable()->after('segmentation_psn');
            $table->string('segmentation_rcf', 30)->nullable()->after('segmentation_ppr');
            $table->string('segmentation_scb', 30)->nullable()->after('segmentation_rcf');
            $table->string('segmentation_sch', 30)->nullable()->after('segmentation_scb');
            $table->string('segmentation_sdh', 30)->nullable()->after('segmentation_sch');
            $table->string('segmentation_smk', 30)->nullable()->after('segmentation_sdh');
            $table->string('segmentation_twu', 30)->nullable()->after('segmentation_smk');
            $table->string('segmentation_tbf', 30)->nullable()->after('segmentation_twu');
            $table->string('segmentation_tgd', 30)->nullable()->after('segmentation_tbf');
            $table->string('segmentation_wkd', 30)->nullable()->after('segmentation_tgd');
            $table->string('segmentation_wks', 30)->nullable()->after('segmentation_wkd');
            $table->string('segmentation_wmn', 30)->nullable()->after('segmentation_wks');
            $table->string('segmentation_1', 30)->nullable()->after('segmentation_wmn');
            $table->string('segmentation_2', 30)->nullable()->after('segmentation_1');
            $table->string('segmentation_3', 30)->nullable()->after('segmentation_2');
            $table->string('segmentation_4', 30)->nullable()->after('segmentation_3');
            $table->string('segmentation_5', 30)->nullable()->after('segmentation_4');
            $table->string('segmentation_6', 30)->nullable()->after('segmentation_5');
            $table->string('segmentation_7', 30)->nullable()->after('segmentation_6');
            $table->string('segmentation_8', 30)->nullable()->after('segmentation_7');
            $table->string('segmentation_9', 30)->nullable()->after('segmentation_8');
            $table->string('segmentation_10', 30)->nullable()->after('segmentation_9');
            $table->string('segmentation_11', 30)->nullable()->after('segmentation_10');
            $table->string('segmentation_12', 30)->nullable()->after('segmentation_11');
            $table->string('segmentation_13', 30)->nullable()->after('segmentation_12');
            $table->string('segmentation_14', 30)->nullable()->after('segmentation_13');
            $table->string('segmentation_15', 30)->nullable()->after('segmentation_14');
            $table->string('segmentation_16', 30)->nullable()->after('segmentation_15');
            $table->string('segmentation_17', 30)->nullable()->after('segmentation_16');
            $table->string('segmentation_18', 30)->nullable()->after('segmentation_17');
            $table->string('segmentation_19', 30)->nullable()->after('segmentation_18');
            $table->string('segmentation_20', 30)->nullable()->after('segmentation_19');
            $table->string('segmentation_21', 30)->nullable()->after('segmentation_20');
            $table->string('segmentation_22', 30)->nullable()->after('segmentation_21');
            $table->string('segmentation_slb', 30)->nullable()->after('segmentation_22');
            $table->string('segmentation_dgb', 30)->nullable()->after('segmentation_slb');
            $table->string('segmentation_scp', 30)->nullable()->default('X')->after('segmentation_dgb');
            $table->string('segmentation_sck', 30)->nullable()->default('X')->after('segmentation_scp');
            $table->string('segmentation_tdc', 30)->default('X')->after('segmentation_sck');
            $table->string('segmentation_twn', 30)->nullable()->after('segmentation_tdc');
            $table->string('segmentation_rcs', 30)->nullable()->after('segmentation_twn');
            $table->string('segmentation_bob', 30)->nullable()->after('segmentation_rcs');
            $table->string('segmentation_drp', 30)->nullable()->after('segmentation_bob');
            $table->string('segmentation_bev', 30)->nullable()->after('segmentation_drp');
            $table->string('segmentation_ten', 30)->nullable()->after('segmentation_bev');
            $table->string('segmentation_ahw', 30)->nullable()->after('segmentation_ten');
            $table->string('segmentation_tmt', 30)->nullable()->after('segmentation_ahw');
            $table->string('segmentation_mks', 30)->nullable()->default('X')->after('segmentation_tmt');
            $table->string('segmentation_bkh', 30)->nullable()->default('X')->after('segmentation_mks');
            $table->string('segmentation_mni', 30)->nullable()->default('X')->after('segmentation_bkh');
            $table->string('segmentation_mor', 30)->nullable()->default('X')->after('segmentation_mni');
            $table->string('segmentation_kkg', 30)->nullable()->default('X')->after('segmentation_mor');
            $table->string('segmentation_fab', 30)->default('X')->after('segmentation_kkg');
            $table->string('segmentation_tai', 30)->default('X')->after('segmentation_fab');
            $table->string('segmentation_sea', 30)->default('X')->after('segmentation_tai');
            $table->string('segmentation_bnh', 30)->default('X')->after('segmentation_sea');
            $table->string('segmentation_nom', 30)->default('X')->after('segmentation_bnh');
            $table->string('segmentation_tbb', 30)->nullable()->after('segmentation_nom');
            $table->string('segmentation_wtf', 30)->nullable()->after('segmentation_tbb');
            $table->string('segmentation_spc', 30)->nullable()->after('segmentation_wtf');
            $table->string('segmentation_spb', 30)->nullable()->after('segmentation_spc');
            $table->string('segmentation_crp', 30)->nullable()->after('segmentation_spb');
            $table->string('segmentation_cor', 30)->nullable()->after('segmentation_crp');
            $table->string('segmentation_cpl', 30)->nullable()->after('segmentation_cor');
            $table->string('segmentation_cmp', 30)->nullable()->after('segmentation_cpl');
        });

        Schema::table('production_items', function (Blueprint $table) { 
            $table->string('action_type', 10)->nullable()->after('final_value_vatinc');
            $table->string('tasteless_code', 15)->nullable()->after('action_type');
            $table->unsignedInteger('suppliers_id')->nullable()->after('tasteless_code');
            $table->unsignedInteger('trademarks_id')->nullable()->after('suppliers_id');
            $table->unsignedInteger('classifications_id')->nullable()->after('trademarks_id');
            $table->string('supplier_item_code', 50)->nullable()->after('classifications_id');
            $table->string('myob_item_description', 255)->nullable()->after('supplier_item_code');
            $table->string('full_item_description', 255)->nullable()->after('myob_item_description');
            $table->unsignedInteger('brands_id')->nullable()->after('full_item_description');
            $table->unsignedInteger('groups_id')->nullable()->after('brands_id');
            $table->unsignedInteger('categories_id')->nullable()->after('groups_id');
            $table->unsignedInteger('subcategories_id')->nullable()->after('categories_id');
            $table->unsignedInteger('types_id')->nullable()->after('subcategories_id');
            $table->unsignedInteger('colors_id')->nullable()->after('types_id');
            $table->string('actual_color', 50)->nullable()->after('colors_id');
            $table->string('flavor', 30)->nullable()->after('actual_color');
            $table->decimal('packaging_size', 16, 2)->default(1.00)->after('flavor');
            $table->string('packaging_dimension', 50)->nullable()->after('packaging_size');
            $table->unsignedInteger('uoms_id')->nullable()->after('packaging_dimension');
            $table->unsignedInteger('packagings_id')->nullable()->after('uoms_id');
            $table->unsignedInteger('vendor_types_id')->nullable()->after('packagings_id');
            $table->unsignedInteger('inventory_types_id')->nullable()->after('vendor_types_id');
            $table->unsignedInteger('sku_statuses_id')->nullable()->after('inventory_types_id');
            $table->unsignedInteger('tax_codes_id')->nullable()->after('sku_statuses_id');
            $table->unsignedInteger('currencies_id')->nullable()->after('tax_codes_id');
            $table->integer('chart_accounts_id')->nullable()->after('currencies_id');
            $table->unsignedInteger('fulfillment_type_id')->nullable()->after('chart_accounts_id');
            $table->decimal('purchase_price', 18, 5)->nullable()->after('fulfillment_type_id');
            $table->decimal('ttp', 18, 5)->nullable()->after('purchase_price');
            $table->decimal('ttp_price_change', 18, 5)->nullable()->after('ttp');
            $table->date('ttp_price_effective_date')->nullable()->after('ttp_price_change');
            $table->decimal('old_ttp', 18, 5)->nullable()->after('ttp_price_effective_date');
            $table->decimal('ttp_percentage', 18, 2)->nullable()->after('old_ttp');
            $table->decimal('old_ttp_percentage', 18, 2)->nullable()->after('ttp_percentage');
            $table->decimal('ttp_percentage_price_change', 16, 5)->nullable()->after('old_ttp_percentage');
            $table->decimal('landed_cost', 18, 5)->nullable()->after('ttp_percentage_price_change');
            $table->decimal('moq_supplier', 18, 2)->nullable()->after('landed_cost');
            $table->decimal('moq_store', 18, 2)->nullable()->after('moq_supplier');
            $table->decimal('moq_value', 18, 2)->nullable()->after('moq_store');
            $table->unsignedInteger('moq_currencies_id')->nullable()->after('moq_value');
            $table->string('type', 20)->nullable()->after('moq_currencies_id');
            $table->string('item', 255)->nullable()->after('type');
            $table->integer('accounts_id')->nullable()->after('item');
            $table->integer('asset_accounts_id')->nullable()->after('accounts_id');
            $table->integer('cogs_accounts_id')->nullable()->after('asset_accounts_id');
            $table->decimal('quantity_on_hand', 18, 2)->default(0.00)->after('cogs_accounts_id');
            $table->decimal('accumulated_depreciation', 18, 2)->default(0.00)->after('quantity_on_hand');
            $table->text('tax_agency')->nullable()->after('accumulated_depreciation');
            $table->decimal('price', 18, 5)->nullable()->after('tax_agency');
            $table->decimal('reorder_pt', 18, 2)->nullable()->after('price');
            $table->text('mpn')->nullable()->after('reorder_pt');
            $table->integer('uoms_set_id')->nullable()->after('mpn');
            $table->integer('tax_status')->nullable()->after('uoms_set_id');
            $table->string('purchase_description', 255)->nullable()->after('tax_status');
            $table->text('image_filename')->nullable()->after('purchase_description');
            $table->mediumText('file_link')->nullable()->after('image_filename'); 
            $table->string('segmentation', 30)->nullable()->after('file_link');
            $table->string('segmentation_any', 30)->nullable()->after('segmentation');
            $table->string('segmentation_bbd', 30)->nullable()->after('segmentation_any');
            $table->string('segmentation_eyb', 30)->nullable()->after('segmentation_bbd');
            $table->string('segmentation_cbl', 30)->nullable()->after('segmentation_eyb');
            $table->string('segmentation_com', 30)->nullable()->after('segmentation_cbl');
            $table->string('segmentation_fmr', 30)->nullable()->after('segmentation_com');
            $table->string('segmentation_fwb', 30)->nullable()->after('segmentation_fmr');
            $table->string('segmentation_fzb', 30)->nullable()->after('segmentation_fwb');
            $table->string('segmentation_hmk', 30)->nullable()->after('segmentation_fzb');
            $table->string('segmentation_htw', 30)->nullable()->after('segmentation_hmk');
            $table->string('segmentation_kkd', 30)->nullable()->after('segmentation_htw');
            $table->string('segmentation_lps', 30)->nullable()->after('segmentation_kkd');
            $table->string('segmentation_lbs', 30)->nullable()->after('segmentation_lps');
            $table->string('segmentation_mtd', 30)->nullable()->after('segmentation_lbs');
            $table->string('segmentation_ppd', 30)->nullable()->after('segmentation_mtd');
            $table->string('segmentation_pze', 30)->nullable()->after('segmentation_ppd');
            $table->string('segmentation_psn', 30)->nullable()->after('segmentation_pze');
            $table->string('segmentation_ppr', 30)->nullable()->after('segmentation_psn');
            $table->string('segmentation_rcf', 30)->nullable()->after('segmentation_ppr');
            $table->string('segmentation_scb', 30)->nullable()->after('segmentation_rcf');
            $table->string('segmentation_sch', 30)->nullable()->after('segmentation_scb');
            $table->string('segmentation_sdh', 30)->nullable()->after('segmentation_sch');
            $table->string('segmentation_smk', 30)->nullable()->after('segmentation_sdh');
            $table->string('segmentation_twu', 30)->nullable()->after('segmentation_smk');
            $table->string('segmentation_tbf', 30)->nullable()->after('segmentation_twu');
            $table->string('segmentation_tgd', 30)->nullable()->after('segmentation_tbf');
            $table->string('segmentation_wkd', 30)->nullable()->after('segmentation_tgd');
            $table->string('segmentation_wks', 30)->nullable()->after('segmentation_wkd');
            $table->string('segmentation_wmn', 30)->nullable()->after('segmentation_wks');
            $table->string('segmentation_1', 30)->nullable()->after('segmentation_wmn');
            $table->string('segmentation_2', 30)->nullable()->after('segmentation_1');
            $table->string('segmentation_3', 30)->nullable()->after('segmentation_2');
            $table->string('segmentation_4', 30)->nullable()->after('segmentation_3');
            $table->string('segmentation_5', 30)->nullable()->after('segmentation_4');
            $table->string('segmentation_6', 30)->nullable()->after('segmentation_5');
            $table->string('segmentation_7', 30)->nullable()->after('segmentation_6');
            $table->string('segmentation_8', 30)->nullable()->after('segmentation_7');
            $table->string('segmentation_9', 30)->nullable()->after('segmentation_8');
            $table->string('segmentation_10', 30)->nullable()->after('segmentation_9');
            $table->string('segmentation_11', 30)->nullable()->after('segmentation_10');
            $table->string('segmentation_12', 30)->nullable()->after('segmentation_11');
            $table->string('segmentation_13', 30)->nullable()->after('segmentation_12');
            $table->string('segmentation_14', 30)->nullable()->after('segmentation_13');
            $table->string('segmentation_15', 30)->nullable()->after('segmentation_14');
            $table->string('segmentation_16', 30)->nullable()->after('segmentation_15');
            $table->string('segmentation_17', 30)->nullable()->after('segmentation_16');
            $table->string('segmentation_18', 30)->nullable()->after('segmentation_17');
            $table->string('segmentation_19', 30)->nullable()->after('segmentation_18');
            $table->string('segmentation_20', 30)->nullable()->after('segmentation_19');
            $table->string('segmentation_21', 30)->nullable()->after('segmentation_20');
            $table->string('segmentation_22', 30)->nullable()->after('segmentation_21');
            $table->string('segmentation_slb', 30)->nullable()->after('segmentation_22');
            $table->string('segmentation_dgb', 30)->nullable()->after('segmentation_slb');
            $table->string('segmentation_scp', 30)->nullable()->default('X')->after('segmentation_dgb');
            $table->string('segmentation_sck', 30)->nullable()->default('X')->after('segmentation_scp');
            $table->string('segmentation_tdc', 30)->default('X')->after('segmentation_sck');
            $table->string('segmentation_twn', 30)->nullable()->after('segmentation_tdc');
            $table->string('segmentation_rcs', 30)->nullable()->after('segmentation_twn');
            $table->string('segmentation_bob', 30)->nullable()->after('segmentation_rcs');
            $table->string('segmentation_drp', 30)->nullable()->after('segmentation_bob');
            $table->string('segmentation_bev', 30)->nullable()->after('segmentation_drp');
            $table->string('segmentation_ten', 30)->nullable()->after('segmentation_bev');
            $table->string('segmentation_ahw', 30)->nullable()->after('segmentation_ten');
            $table->string('segmentation_tmt', 30)->nullable()->after('segmentation_ahw');
            $table->string('segmentation_mks', 30)->nullable()->default('X')->after('segmentation_tmt');
            $table->string('segmentation_bkh', 30)->nullable()->default('X')->after('segmentation_mks');
            $table->string('segmentation_mni', 30)->nullable()->default('X')->after('segmentation_bkh');
            $table->string('segmentation_mor', 30)->nullable()->default('X')->after('segmentation_mni');
            $table->string('segmentation_kkg', 30)->nullable()->default('X')->after('segmentation_mor');
            $table->string('segmentation_fab', 30)->default('X')->after('segmentation_kkg');
            $table->string('segmentation_tai', 30)->default('X')->after('segmentation_fab');
            $table->string('segmentation_sea', 30)->default('X')->after('segmentation_tai');
            $table->string('segmentation_bnh', 30)->default('X')->after('segmentation_sea');
            $table->string('segmentation_nom', 30)->default('X')->after('segmentation_bnh');
            $table->string('segmentation_tbb', 30)->nullable()->after('segmentation_nom');
            $table->string('segmentation_wtf', 30)->nullable()->after('segmentation_tbb');
            $table->string('segmentation_spc', 30)->nullable()->after('segmentation_wtf');
            $table->string('segmentation_spb', 30)->nullable()->after('segmentation_spc');
            $table->string('segmentation_crp', 30)->nullable()->after('segmentation_spb');
            $table->string('segmentation_cor', 30)->nullable()->after('segmentation_crp');
            $table->string('segmentation_cpl', 30)->nullable()->after('segmentation_cor');
            $table->string('segmentation_cmp', 30)->nullable()->after('segmentation_cpl');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
          Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->dropColumn([
                'action_type', 'tasteless_code', 'suppliers_id', 'trademarks_id', 'classifications_id',
                'supplier_item_code', 'myob_item_description', 'full_item_description', 'brands_id',
                'groups_id', 'categories_id', 'subcategories_id', 'types_id', 'colors_id', 'actual_color',
                'flavor', 'packaging_size', 'packaging_dimension', 'uoms_id', 'packagings_id', 'vendor_types_id',
                'inventory_types_id', 'sku_statuses_id', 'tax_codes_id', 'currencies_id', 'chart_accounts_id',
                'fulfillment_type_id', 'purchase_price', 'ttp', 'ttp_price_change', 'ttp_price_effective_date',
                'old_ttp', 'ttp_percentage', 'old_ttp_percentage', 'ttp_percentage_price_change', 'landed_cost',
                'moq_supplier', 'moq_store', 'moq_value', 'moq_currencies_id', 'type', 'item', 'accounts_id',
                'asset_accounts_id', 'cogs_accounts_id', 'quantity_on_hand', 'accumulated_depreciation',
                'tax_agency', 'price', 'reorder_pt', 'mpn', 'uoms_set_id', 'tax_status', 'purchase_description',
                'image_filename', 'file_link', 'segmentation', 'segmentation_any', 'segmentation_bbd',
                'segmentation_eyb', 'segmentation_cbl', 'segmentation_com', 'segmentation_fmr',
                'segmentation_fwb', 'segmentation_fzb', 'segmentation_hmk', 'segmentation_htw',
                'segmentation_kkd', 'segmentation_lps', 'segmentation_lbs', 'segmentation_mtd',
                'segmentation_ppd', 'segmentation_pze', 'segmentation_psn', 'segmentation_ppr',
                'segmentation_rcf', 'segmentation_scb', 'segmentation_sch', 'segmentation_sdh',
                'segmentation_smk', 'segmentation_twu', 'segmentation_tbf', 'segmentation_tgd',
                'segmentation_wkd', 'segmentation_wks', 'segmentation_wmn', 'segmentation_1',
                'segmentation_2', 'segmentation_3', 'segmentation_4', 'segmentation_5', 'segmentation_6',
                'segmentation_7', 'segmentation_8', 'segmentation_9', 'segmentation_10', 'segmentation_11',
                'segmentation_12', 'segmentation_13', 'segmentation_14', 'segmentation_15', 'segmentation_16',
                'segmentation_17', 'segmentation_18', 'segmentation_19', 'segmentation_20', 'segmentation_21',
                'segmentation_22', 'segmentation_slb', 'segmentation_dgb', 'segmentation_scp', 'segmentation_sck',
                'segmentation_tdc', 'segmentation_twn', 'segmentation_rcs', 'segmentation_bob', 'segmentation_drp',
                'segmentation_bev', 'segmentation_ten', 'segmentation_ahw', 'segmentation_tmt', 'segmentation_mks',
                'segmentation_bkh', 'segmentation_mni', 'segmentation_mor', 'segmentation_kkg', 'segmentation_fab',
                'segmentation_tai', 'segmentation_sea', 'segmentation_bnh', 'segmentation_nom', 'segmentation_tbb',
                'segmentation_wtf', 'segmentation_spc', 'segmentation_spb', 'segmentation_crp', 'segmentation_cor',
                'segmentation_cpl', 'segmentation_cmp'
            ]);
        });

        Schema::table('production_items', function (Blueprint $table) {
            $table->dropColumn([
                'action_type', 'tasteless_code', 'suppliers_id', 'trademarks_id', 'classifications_id',
                'supplier_item_code', 'myob_item_description', 'full_item_description', 'brands_id',
                'groups_id', 'categories_id', 'subcategories_id', 'types_id', 'colors_id', 'actual_color',
                'flavor', 'packaging_size', 'packaging_dimension', 'uoms_id', 'packagings_id', 'vendor_types_id',
                'inventory_types_id', 'sku_statuses_id', 'tax_codes_id', 'currencies_id', 'chart_accounts_id',
                'fulfillment_type_id', 'purchase_price', 'ttp', 'ttp_price_change', 'ttp_price_effective_date',
                'old_ttp', 'ttp_percentage', 'old_ttp_percentage', 'ttp_percentage_price_change', 'landed_cost',
                'moq_supplier', 'moq_store', 'moq_value', 'moq_currencies_id', 'type', 'item', 'accounts_id',
                'asset_accounts_id', 'cogs_accounts_id', 'quantity_on_hand', 'accumulated_depreciation',
                'tax_agency', 'price', 'reorder_pt', 'mpn', 'uoms_set_id', 'tax_status', 'purchase_description',
                'image_filename', 'file_link', 'segmentation', 'segmentation_any', 'segmentation_bbd',
                'segmentation_eyb', 'segmentation_cbl', 'segmentation_com', 'segmentation_fmr',
                'segmentation_fwb', 'segmentation_fzb', 'segmentation_hmk', 'segmentation_htw',
                'segmentation_kkd', 'segmentation_lps', 'segmentation_lbs', 'segmentation_mtd',
                'segmentation_ppd', 'segmentation_pze', 'segmentation_psn', 'segmentation_ppr',
                'segmentation_rcf', 'segmentation_scb', 'segmentation_sch', 'segmentation_sdh',
                'segmentation_smk', 'segmentation_twu', 'segmentation_tbf', 'segmentation_tgd',
                'segmentation_wkd', 'segmentation_wks', 'segmentation_wmn', 'segmentation_1',
                'segmentation_2', 'segmentation_3', 'segmentation_4', 'segmentation_5', 'segmentation_6',
                'segmentation_7', 'segmentation_8', 'segmentation_9', 'segmentation_10', 'segmentation_11',
                'segmentation_12', 'segmentation_13', 'segmentation_14', 'segmentation_15', 'segmentation_16',
                'segmentation_17', 'segmentation_18', 'segmentation_19', 'segmentation_20', 'segmentation_21',
                'segmentation_22', 'segmentation_slb', 'segmentation_dgb', 'segmentation_scp', 'segmentation_sck',
                'segmentation_tdc', 'segmentation_twn', 'segmentation_rcs', 'segmentation_bob', 'segmentation_drp',
                'segmentation_bev', 'segmentation_ten', 'segmentation_ahw', 'segmentation_tmt', 'segmentation_mks',
                'segmentation_bkh', 'segmentation_mni', 'segmentation_mor', 'segmentation_kkg', 'segmentation_fab',
                'segmentation_tai', 'segmentation_sea', 'segmentation_bnh', 'segmentation_nom', 'segmentation_tbb',
                'segmentation_wtf', 'segmentation_spc', 'segmentation_spb', 'segmentation_crp', 'segmentation_cor',
                'segmentation_cpl', 'segmentation_cmp'
            ]);
        });
    }
}
