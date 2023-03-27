CREATE VIEW MENU_INGREDIENTS_AUTO_COMPUTE AS 
	SELECT
	    menu_ingredients_details.id,
	    menu_ingredients_details.menu_items_id,
	    menu_ingredients_details.item_masters_id,
	    menu_ingredients_details.menu_as_ingredient_id,
	    item_masters.full_item_description,
	    menu_items.menu_item_description,
	    menu_ingredients_details.ingredient_name,
	    menu_ingredients_details.ingredient_group,
	    menu_ingredients_details.row_id,
	    menu_ingredients_details.is_existing,
	    menu_ingredients_details.is_primary,
	    menu_ingredients_details.is_selected,
	    menu_ingredients_details.prep_qty,
	    menu_ingredients_details.uom_id,
	    uoms.uom_description,
	    menu_ingredients_details.uom_name,
	    menu_ingredients_details.menu_ingredients_preparations_id,
	    packagings.packaging_description,
	    menu_ingredients_details.yield,
	    menu_ingredients_details.status,
	    menu_items.food_cost AS food_cost,
	    ROUND(
	        menu_ingredients_details.yield / 100,
	        4
	    ) as converted_yield,
	    1 as uom_qty,
	    CASE
	        WHEN menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	        WHEN menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
	        ELSE menu_ingredients_details.ttp
	    END as ttp,
	    CASE
	        WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	        WHEN menu_ingredients_details.packaging_size IS NOT NULL THEN menu_ingredients_details.packaging_size
	        ELSE 1
	    END as packaging_size,
	    ROUND(
	        prep_qty * (
	            1 + (
	                1 - ROUND(
	                    menu_ingredients_details.yield / 100,
	                    4
	                )
	            )
	        )
	    ) as ingredient_qty,
	    ROUND(
	        1 / (
	            CASE
	                WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	                WHEN menu_ingredients_details.packaging_size IS NOT NULL THEN menu_ingredients_details.packaging_size
	                ELSE 1
	            END
	        ) * prep_qty * (
	            1 + (
	                1 - ROUND(
	                    menu_ingredients_details.yield / 100,
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
	                    WHEN menu_ingredients_details.packaging_size IS NOT NULL THEN menu_ingredients_details.packaging_size
	                    ELSE 1
	                END
	            ) * prep_qty * (
	                1 + (
	                    1 - ROUND(
	                        menu_ingredients_details.yield / 100,
	                        4
	                    )
	                )
	            ),
	            4
	        ) * CASE
	            WHEN menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	            WHEN menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
	            ELSE menu_ingredients_details.ttp
	        END,
	        4
	    ) as cost
	FROM
	    menu_ingredients_details
	    LEFT JOIN item_masters ON item_masters.id = menu_ingredients_details.item_masters_id
	    LEFT JOIN menu_items ON menu_items.id = menu_ingredients_details.menu_as_ingredient_id
	    LEFT JOIN uoms ON menu_ingredients_details.uom_id = uoms.id
	    LEFT JOIN packagings ON packagings.id = (
	        menu_ingredients_details.uom_id
	    )
; 