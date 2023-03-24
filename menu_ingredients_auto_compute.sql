CREATE VIEW MENU_INGREDIENTS_AUTO_COMPUTE AS 
	SELECT
	    menu_ingredients_details_temp.id,
	    menu_ingredients_details_temp.menu_items_id,
	    menu_ingredients_details_temp.item_masters_id,
	    menu_ingredients_details_temp.menu_as_ingredient_id,
	    item_masters.full_item_description,
	    menu_items.menu_item_description,
	    menu_ingredients_details_temp.ingredient_name,
	    menu_ingredients_details_temp.ingredient_group,
	    menu_ingredients_details_temp.row_id,
	    menu_ingredients_details_temp.is_existing,
	    menu_ingredients_details_temp.is_primary,
	    menu_ingredients_details_temp.is_selected,
	    menu_ingredients_details_temp.prep_qty,
	    menu_ingredients_details_temp.uom_id,
	    uoms.uom_description,
	    menu_ingredients_details_temp.uom_name,
	    menu_ingredients_details_temp.menu_ingredients_preparations_id,
	    packagings.packaging_description,
	    menu_ingredients_details_temp.yield,
	    menu_ingredients_details_temp.status,
	    menu_items.food_cost_temp AS food_cost,
	    ROUND(
	        menu_ingredients_details_temp.yield / 100,
	        4
	    ) as converted_yield,
	    1 as uom_qty,
	    CASE
	        WHEN menu_ingredients_details_temp.item_masters_id IS NOT NULL THEN item_masters.ttp
	        WHEN menu_ingredients_details_temp.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost_temp, 4)
	        ELSE menu_ingredients_details_temp.ttp
	    END as ttp,
	    CASE
	        WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	        WHEN menu_ingredients_details_temp.packaging_size IS NOT NULL THEN menu_ingredients_details_temp.packaging_size
	        ELSE 1
	    END as packaging_size,
	    ROUND(
	        prep_qty * (
	            1 + (
	                1 - ROUND(
	                    menu_ingredients_details_temp.yield / 100,
	                    4
	                )
	            )
	        )
	    ) as ingredient_qty,
	    ROUND(
	        1 / (
	            CASE
	                WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	                WHEN menu_ingredients_details_temp.packaging_size IS NOT NULL THEN menu_ingredients_details_temp.packaging_size
	                ELSE 1
	            END
	        ) * prep_qty * (
	            1 + (
	                1 - ROUND(
	                    menu_ingredients_details_temp.yield / 100,
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
	                    WHEN menu_ingredients_details_temp.packaging_size IS NOT NULL THEN menu_ingredients_details_temp.packaging_size
	                    ELSE 1
	                END
	            ) * prep_qty * (
	                1 + (
	                    1 - ROUND(
	                        menu_ingredients_details_temp.yield / 100,
	                        4
	                    )
	                )
	            ),
	            4
	        ) * CASE
	            WHEN menu_ingredients_details_temp.item_masters_id IS NOT NULL THEN item_masters.ttp
	            WHEN menu_ingredients_details_temp.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost_temp, 4)
	            ELSE menu_ingredients_details_temp.ttp
	        END,
	        4
	    ) as cost
	FROM
	    menu_ingredients_details_temp
	    LEFT JOIN item_masters ON item_masters.id = menu_ingredients_details_temp.item_masters_id
	    LEFT JOIN menu_items ON menu_items.id = menu_ingredients_details_temp.menu_as_ingredient_id
	    LEFT JOIN uoms ON menu_ingredients_details_temp.uom_id = uoms.id
	    LEFT JOIN packagings ON packagings.id = (
	        menu_ingredients_details_temp.uom_id
	    )
; 