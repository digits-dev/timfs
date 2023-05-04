CREATE VIEW RND_MENU_PACKAGINGS_AUTO_COMPUTE AS 
	SELECT
	    rnd_menu_packagings_details.id,
	    rnd_menu_packagings_details.rnd_menu_items_id,
	    rnd_menu_packagings_details.item_masters_id,
	    rnd_menu_packagings_details.item_masters_temp_id,
	    item_masters.full_item_description,
	    item_masters_temp.item_description,
	    rnd_menu_packagings_details.packaging_name,
	    rnd_menu_packagings_details.packaging_group,
	    rnd_menu_packagings_details.row_id,
	    rnd_menu_packagings_details.is_existing,
	    rnd_menu_packagings_details.is_primary,
	    rnd_menu_packagings_details.is_selected,
	    rnd_menu_packagings_details.prep_qty,
	    rnd_menu_packagings_details.uom_id,
	    uoms.uom_description,
	    rnd_menu_packagings_details.menu_ingredients_preparations_id,
	    packagings.packaging_description,
	    rnd_menu_packagings_details.yield,
	    rnd_menu_packagings_details.status,
	    ROUND(
	        rnd_menu_packagings_details.yield / 100,
	        4
	    ) as converted_yield,
	    1 as uom_qty,
	    CASE
	        WHEN rnd_menu_packagings_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	        WHEN rnd_menu_packagings_details.item_masters_temp_id IS NOT NULL THEN item_masters_temp.ttp
	        ELSE rnd_menu_packagings_details.ttp
	    END as ttp,
	    CASE
	        WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	        WHEN item_masters_temp.packaging_size IS NOT NULL THEN item_masters_temp.packaging_size
	        WHEN rnd_menu_packagings_details.packaging_size IS NOT NULL THEN rnd_menu_packagings_details.packaging_size
	        ELSE 1
	    END as packaging_size,
	    ROUND(
	        prep_qty * (
	            1 + (
	                1 - ROUND(
	                    rnd_menu_packagings_details.yield / 100,
	                    4
	                )
	            )
	        ),
	        4
	    ) as packaging_qty,
	    ROUND(
	        1 / (
	            CASE
	                WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	                WHEN item_masters_temp.packaging_size IS NOT NULL THEN item_masters_temp.packaging_size
	                WHEN rnd_menu_packagings_details.packaging_size IS NOT NULL THEN rnd_menu_packagings_details.packaging_size
	                ELSE 1
	            END
	        ) * prep_qty * (
	            1 + (
	                1 - ROUND(
	                    rnd_menu_packagings_details.yield / 100,
	                    4
	                )
	            )
	        ),
	        4
	    ) as modifier,
	    ROUND(
	        ROUND(
	            1 / (
	                CASE
	                    WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	                    WHEN item_masters_temp.packaging_size IS NOT NULL THEN item_masters_temp.packaging_size
	                    WHEN rnd_menu_packagings_details.packaging_size IS NOT NULL THEN rnd_menu_packagings_details.packaging_size
	                    ELSE 1
	                END
	            ) * prep_qty * (
	                1 + (
	                    1 - ROUND(
	                        rnd_menu_packagings_details.yield / 100,
	                        4
	                    )
	                )
	            ),
	            4
	        ) * CASE
	            WHEN rnd_menu_packagings_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	            WHEN rnd_menu_packagings_details.item_masters_temp_id IS NOT NULL THEN item_masters_temp.ttp
	            ELSE rnd_menu_packagings_details.ttp
	        END,
	        4
	    ) as cost
	FROM
	    rnd_menu_packagings_details
	    LEFT JOIN item_masters ON item_masters.id = rnd_menu_packagings_details.item_masters_id
	    LEFT JOIN item_masters_temp ON item_masters_temp.id = rnd_menu_packagings_details.item_masters_temp_id
	    LEFT JOIN uoms ON rnd_menu_packagings_details.uom_id = uoms.id
	    LEFT JOIN packagings ON packagings.id = COALESCE(
	        item_masters.packagings_id,
	        rnd_menu_packagings_details.uom_id
	    )
; 