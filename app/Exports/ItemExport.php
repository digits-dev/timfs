<?php

namespace App\Exports; 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; 
use CRUDBooster;
use DB;

class ItemExport implements FromQuery, WithHeadings, WithMapping 
{
    use Exportable;

    public function headings(): array {
        $segmentations =  DB::table('segmentations')->where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();

        $header = [
            'Tasteless Code',
            'Co. Last Name',
            'Vendor Type',
            'Supplier Item Code',
            'Full Item Description',
            'Brand Code',
            'Brand Description',
            'Group',
            'Category Code',
            'Category Description',
            'Subcategory Description',
            'Dimension',
            'Packaging Size',
            'Fulfillment Type',
            'UOM',
            'Packaging',
            'SKU Status',
            'Supplier Cost',
            'Currency',
            'VAT Code',
            'MOQ Supplier',
            'MOQ Store',
        ];

        if(CRUDBooster::myColumnView()->ttp){

            array_push($header,'Sales Price');
            array_push($header,'Old Sales Price');
            array_push($header,'Sales Price Change');
            array_push($header,'Sales Price Effective Date');
        }
        if(CRUDBooster::myColumnView()->ttp_percentage){
            
            array_push($header,'TTP Markup Percentage');
            array_push($header,'Old TTP Markup Percentage');
        }
        if(CRUDBooster::myColumnView()->landed_cost){
            
            array_push($header,'Landed Cost');
        }

        if(CRUDBooster::myColumnView()->segmentation){
            foreach($segmentations as $segment){
                array_push($header,$segment->segment_column_description);
            }
        }

        array_push($header,'Created By');
        array_push($header,'Created Date');
        array_push($header,'Updated By');
        array_push($header,'Updated Date');

        return $header;
    }

    public function map($data_item): array {
        $segmentations =  DB::table('segmentations')->where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();
        
        $item_mapping = [
            $data_item->tasteless_code, 
            $data_item->last_name,
            $data_item->vendor_type_description,
            $data_item->supplier_item_code,
            $data_item->full_item_description,
            $data_item->brand_code,
            $data_item->brand_description,
            $data_item->group_description,
            $data_item->category_code,
            $data_item->category_description,
            $data_item->subcategory_description,
            $data_item->packaging_dimension,
            $data_item->packaging_size,
            $data_item->fulfillment_method,
            $data_item->uom_code,
            $data_item->packaging_code,
            $data_item->sku_status_description,
            $data_item->purchase_price,
            $data_item->currency_code,
            $data_item->tax_description,
            $data_item->moq_supplier,
            $data_item->moq_store
        ];

        if(CRUDBooster::myColumnView()->ttp){ 
            
            array_push($item_mapping, $data_item->ttp);
            array_push($item_mapping, $data_item->old_ttp);
            array_push($item_mapping, $data_item->ttp_price_change);
            array_push($item_mapping, $data_item->ttp_price_effective_date);
        }
        if(CRUDBooster::myColumnView()->ttp_percentage){ 
            array_push($item_mapping, $data_item->ttp_percentage);
            array_push($item_mapping, $data_item->old_ttp_percentage);
        }
        if(CRUDBooster::myColumnView()->landed_cost){ 
            array_push($item_mapping, $data_item->landed_cost);
        }
        if(CRUDBooster::myColumnView()->segmentation){
            foreach($segmentations as $segment){
                $seg = $segment->segment_column_name;
                array_push($item_mapping, $data_item->$seg);
            }
        }

        array_push($item_mapping, $data_item->created_by);
        array_push($item_mapping, $data_item->created_at);
        array_push($item_mapping, $data_item->updated_by);
        array_push($item_mapping, $data_item->updated_at);

        return $item_mapping;
    }

    public function query() {
        $segmentations =  DB::table('segmentations')->where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();
   
        $items = DB::query() 
        ->fromSub(self::Union_ItemMaster_ProductionItems(), 'item_masters')
        ->orderBy('item_masters.id')
        ->whereNotNull('tasteless_code')
        ->leftJoin('suppliers','item_masters.suppliers_id','=','suppliers.id')
        ->leftJoin('vendor_types', 'suppliers.vendor_types_id', '=', 'vendor_types.id')
        ->leftJoin('trademarks','item_masters.trademarks_id','=','trademarks.id')
        ->leftJoin('classifications','item_masters.classifications_id','=','classifications.id')
        ->leftJoin('brands','item_masters.brands_id','=','brands.id')
        ->leftJoin('groups','item_masters.groups_id','=','groups.id')
        ->leftJoin('categories','item_masters.categories_id','=','categories.id')
        ->leftJoin('subcategories','item_masters.subcategories_id','=','subcategories.id')
        ->leftJoin('colors','item_masters.colors_id','=','colors.id')
        ->leftJoin('currencies','item_masters.currencies_id','=','currencies.id')
        ->leftJoin('sku_statuses','item_masters.sku_statuses_id','=','sku_statuses.id')
        // ->leftJoin('vendor_types','item_masters.vendor_types_id','=','vendor_types.id')
        ->leftJoin('packagings','item_masters.packagings_id','=','packagings.id')
        ->leftJoin('fulfillment_methods','item_masters.fulfillment_type_id','=','fulfillment_methods.id')
        ->leftJoin('uoms','item_masters.uoms_id','=','uoms.id')
        ->leftJoin('inventory_types','item_masters.inventory_types_id','=','inventory_types.id')
        ->leftJoin('tax_codes','item_masters.tax_codes_id','=','tax_codes.id')
        ->leftJoin('chart_accounts','item_masters.chart_accounts_id','=','chart_accounts.id')
        ->leftJoin('cms_users as user1','item_masters.created_by','=','user1.id')
        ->leftJoin('cms_users as user2','item_masters.updated_by','=','user2.id')
        ->select(
            'item_masters.tasteless_code', 
            'suppliers.last_name',
            'vendor_types.vendor_type_description',
            'item_masters.supplier_item_code',
            'item_masters.full_item_description',
            'brands.brand_code',
            'brands.brand_description',
            'groups.group_description',
            'categories.category_code',
            'categories.category_description',
            'subcategories.subcategory_description',
            'item_masters.packaging_dimension',
            'item_masters.packaging_size',
            'fulfillment_methods.fulfillment_method',
            'uoms.uom_code',
            'packagings.packaging_code',
            'sku_statuses.sku_status_description',
            'item_masters.purchase_price',
            'currencies.currency_code',
            'tax_codes.tax_description',
            'item_masters.moq_supplier',
            'item_masters.moq_store',
        );
         
        if(CRUDBooster::myColumnView()->ttp){  
            $items->addSelect('item_masters.ttp','item_masters.old_ttp','item_masters.ttp_price_change','item_masters.ttp_price_effective_date');
        }
        if(CRUDBooster::myColumnView()->ttp_percentage){ 
            $items->addSelect('item_masters.ttp_percentage','item_masters.old_ttp_percentage');
        }
        if(CRUDBooster::myColumnView()->landed_cost){ 
            $items->addSelect('item_masters.landed_cost');
        }
        if(CRUDBooster::myColumnView()->segmentation){
            foreach($segmentations as $segment){
                $items->addSelect('item_masters.'.$segment->segment_column_name);
            }
        }

        $items->addSelect('user1.name as created_by','item_masters.created_at','user2.name as updated_by','item_masters.updated_at');

        if(!in_array(CRUDBooster::myPrivilegeId(), [1, 13, 8, 3])){
            $items->where('item_masters.sku_statuses_id','!=',2);
        }

        return $items;

    }

 


    function Union_ItemMaster_ProductionItems()
    {
        return "SELECT
            `id`,
            `action_type`,
            `tasteless_code`,
            `suppliers_id`,
            `trademarks_id`,
            `classifications_id`,
            `supplier_item_code`,
            `myob_item_description`,
            `full_item_description`,
            `brands_id`,
            `groups_id`,
            `categories_id`,
            `subcategories_id`,
            `types_id`,
            `colors_id`,
            `actual_color`,
            `flavor`,
            `packaging_size`,
            `packaging_dimension`,
            `uoms_id`,
            `packagings_id`,
            `vendor_types_id`,
            `inventory_types_id`,
            `sku_statuses_id`,
            `tax_codes_id`,
            `currencies_id`,
            `chart_accounts_id`,
            `fulfillment_type_id`,
            `purchase_price`,
            `ttp`,
            `ttp_price_change`,
            `ttp_price_effective_date`,
            `old_ttp`,
            `ttp_percentage`,
            `old_ttp_percentage`,
            `ttp_percentage_price_change`,
            `landed_cost`,
            `moq_supplier`,
            `moq_store`,
            `moq_value`,
            `moq_currencies_id`,
            `type`,
            `item`,
            `accounts_id`,
            `asset_accounts_id`,
            `cogs_accounts_id`,
            `quantity_on_hand`,
            `accumulated_depreciation`,
            `tax_agency`,
            `price`,
            `reorder_pt`,
            `mpn`,
            `uoms_set_id`,
            `tax_status`,
            `purchase_description`,
            `image_filename`,
            `file_link`,
            `segmentation`,
            `segmentation_any`,
            `segmentation_bbd`,
            `segmentation_eyb`,
            `segmentation_cbl`,
            `segmentation_com`,
            `segmentation_fmr`,
            `segmentation_fwb`,
            `segmentation_fzb`,
            `segmentation_hmk`,
            `segmentation_htw`,
            `segmentation_kkd`,
            `segmentation_lps`,
            `segmentation_lbs`,
            `segmentation_mtd`,
            `segmentation_ppd`,
            `segmentation_pze`,
            `segmentation_psn`,
            `segmentation_ppr`,
            `segmentation_rcf`,
            `segmentation_scb`,
            `segmentation_sch`,
            `segmentation_sdh`,
            `segmentation_smk`,
            `segmentation_twu`,
            `segmentation_tbf`,
            `segmentation_tgd`,
            `segmentation_wkd`,
            `segmentation_wks`,
            `segmentation_wmn`,
            `segmentation_1`,
            `segmentation_2`,
            `segmentation_3`,
            `segmentation_4`,
            `segmentation_5`,
            `segmentation_6`,
            `segmentation_7`,
            `segmentation_8`,
            `segmentation_9`,
            `segmentation_10`,
            `segmentation_11`,
            `segmentation_12`,
            `segmentation_13`,
            `segmentation_14`,
            `segmentation_15`,
            `segmentation_16`,
            `segmentation_17`,
            `segmentation_18`,
            `segmentation_19`,
            `segmentation_20`,
            `segmentation_21`,
            `segmentation_22`,
            `segmentation_slb`,
            `segmentation_dgb`,
            `segmentation_scp`,
            `segmentation_sck`,
            `segmentation_tdc`,
            `segmentation_twn`,
            `segmentation_rcs`,
            `segmentation_bob`,
            `segmentation_drp`,
            `segmentation_bev`,
            `segmentation_ten`,
            `segmentation_ahw`,
            `segmentation_tmt`,
            `segmentation_mks`,
            `segmentation_bkh`,
            `segmentation_mni`,
            `segmentation_mor`,
            `segmentation_kkg`,
            `segmentation_fab`,
            `segmentation_tai`,
            `segmentation_sea`,
            `segmentation_bnh`,
            `segmentation_nom`,
            `segmentation_tbb`,
            `segmentation_wft`,
            `segmentation_spc`,
            `segmentation_spb`,
            `segmentation_crp`,
            `segmentation_cor`,
            `segmentation_cpl`,
            `segmentation_cmp`,
            `approval_status`,
            `encoder_privilege_id`,
            `approver_privilege_id_1`,
            `approved_by_1`,
            `approved_at_1`,
            `approver_privilege_id_2`,
            `approved_by_2`,
            `approved_at_2`,
            `approver_privilege_id_3`,
            `approved_by_3`,
            `approved_at_3`,
            `approver_privilege_id_4`,
            `approved_by_4`,
            `approved_at_4`,
            `created_by`,
            `updated_by`,
            `created_at`,
            `updated_at`,
            `updated_approved_by_1`,
            `updated_approved_at_1`,
            `updated_approver_privilege_id_1`,
            `updated_approved_by_2`,
            `updated_approved_at_2`,
            `updated_approver_privilege_id_2`,
            `deleted_at`
            FROM item_masters
            
            UNION ALL

            SELECT
            `id`,
            `action_type`,
            `reference_number` as tasteless_code,
            `suppliers_id`,
            `trademarks_id`,
            `classifications_id`,
            `supplier_item_code`,
            `myob_item_description`,
            `full_item_description`,
            `brands_id`,
            `groups_id`,
            `categories_id`,
            `subcategories_id`,
            `types_id`,
            `colors_id`,
            `actual_color`,
            `flavor`,
            `packaging_size`,
            `packaging_dimension`,
            `uoms_id`,
            `packagings_id`,
            `vendor_types_id`,
            `inventory_types_id`,
            `sku_statuses_id`,
            `tax_codes_id`,
            `currencies_id`,
            `chart_accounts_id`,
            `fulfillment_type_id`,
            `purchase_price`,
            `ttp`,
            `ttp_price_change`,
            `ttp_price_effective_date`,
            `old_ttp`,
            `ttp_percentage`,
            `old_ttp_percentage`,
            `ttp_percentage_price_change`,
            `landed_cost`,
            `moq_supplier`,
            `moq_store`,
            `moq_value`,
            `moq_currencies_id`,
            `type`,
            `item`,
            `accounts_id`,
            `asset_accounts_id`,
            `cogs_accounts_id`,
            `quantity_on_hand`,
            `accumulated_depreciation`,
            `tax_agency`,
            `price`,
            `reorder_pt`,
            `mpn`,
            `uoms_set_id`,
            `tax_status`,
            `purchase_description`,
            `image_filename`,
            `file_link`,
            `segmentation`,
            `segmentation_any`,
            `segmentation_bbd`,
            `segmentation_eyb`,
            `segmentation_cbl`,
            `segmentation_com`,
            `segmentation_fmr`,
            `segmentation_fwb`,
            `segmentation_fzb`,
            `segmentation_hmk`,
            `segmentation_htw`,
            `segmentation_kkd`,
            `segmentation_lps`,
            `segmentation_lbs`,
            `segmentation_mtd`,
            `segmentation_ppd`,
            `segmentation_pze`,
            `segmentation_psn`,
            `segmentation_ppr`,
            `segmentation_rcf`,
            `segmentation_scb`,
            `segmentation_sch`,
            `segmentation_sdh`,
            `segmentation_smk`,
            `segmentation_twu`,
            `segmentation_tbf`,
            `segmentation_tgd`,
            `segmentation_wkd`,
            `segmentation_wks`,
            `segmentation_wmn`,
            `segmentation_1`,
            `segmentation_2`,
            `segmentation_3`,
            `segmentation_4`,
            `segmentation_5`,
            `segmentation_6`,
            `segmentation_7`,
            `segmentation_8`,
            `segmentation_9`,
            `segmentation_10`,
            `segmentation_11`,
            `segmentation_12`,
            `segmentation_13`,
            `segmentation_14`,
            `segmentation_15`,
            `segmentation_16`,
            `segmentation_17`,
            `segmentation_18`,
            `segmentation_19`,
            `segmentation_20`,
            `segmentation_21`,
            `segmentation_22`,
            `segmentation_slb`,
            `segmentation_dgb`,
            `segmentation_scp`,
            `segmentation_sck`,
            `segmentation_tdc`,
            `segmentation_twn`,
            `segmentation_rcs`,
            `segmentation_bob`,
            `segmentation_drp`,
            `segmentation_bev`,
            `segmentation_ten`,
            `segmentation_ahw`,
            `segmentation_tmt`,
            `segmentation_mks`,
            `segmentation_bkh`,
            `segmentation_mni`,
            `segmentation_mor`,
            `segmentation_kkg`,
            `segmentation_fab`,
            `segmentation_tai`,
            `segmentation_sea`,
            `segmentation_bnh`,
            `segmentation_nom`,
            `segmentation_tbb`,
            `segmentation_wtf`,
            `segmentation_spc`,
            `segmentation_spb`,
            `segmentation_crp`,
            `segmentation_cor`,
            `segmentation_cpl`,
            `segmentation_cmp`,
            `approval_status`,
            NULL AS  `encoder_privilege_id`,
            NULL AS  `approver_privilege_id_1`,
            NULL AS `approved_at_1`,
            NULL AS `approved_by_1`,
            NULL AS `approver_privilege_id_2`,
            NULL AS `approved_by_2`,
            NULL AS `approved_at_2`,
            NULL AS `approver_privilege_id_3`,
            NULL AS `approved_by_3`,
            NULL AS `approved_at_3`,
            NULL AS `approver_privilege_id_4`,
            NULL AS `approved_by_4`,
            NULL AS `approved_at_4`,
            `created_by`,
            `updated_by`,
            `created_at`,
            `updated_at`,
            NULL AS `updated_approved_by_1`,
            NULL AS `updated_approved_at_1`,
            NULL AS `updated_approver_privilege_id_1`,
            NULL AS `updated_approved_by_2`,
            NULL AS `updated_approved_at_2`,
            NULL AS `updated_approver_privilege_id_2`,
            NULL AS `deleted_at`
            FROM production_items";
    }
}
