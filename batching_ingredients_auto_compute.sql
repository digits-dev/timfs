-- Active: 1678237171731@@127.0.0.1@3306@timfs

CREATE VIEW BATCHING_INGREDIENTS_AUTO_COMPUTE AS 
	SELECT
	    batching_ingredients_details.id,
	    batching_ingredients_details.batching_ingredients_id,
	    batching_ingredients_details.item_masters_id,
	    batching_ingredients_details.menu_as_ingredient_id,
	    batching_ingredients_details.new_ingredients_id,
	    item_masters.full_item_description,
	    menu_items.menu_item_description,
	    new_ingredients.item_description,
	    batching_ingredients_details.ingredient_name,
	    batching_ingredients_details.ingredient_group,
	    batching_ingredients_details.row_id,
	    batching_ingredients_details.is_existing,
	    batching_ingredients_details.is_primary,
	    batching_ingredients_details.is_selected,
	    batching_ingredients_details.prep_qty,
	    batching_ingredients_details.uom_id,
	    uoms.uom_description,
	    batching_ingredients_details.menu_ingredients_preparations_id,
	    packagings.packaging_description,
	    batching_ingredients_details.yield,
	    batching_ingredients_details.status,
	    menu_items.food_cost AS food_cost,
	    ROUND(
	        batching_ingredients_details.yield / 100,
	        4
	    ) as converted_yield,
	    1 as uom_qty,
	    CASE
	        WHEN batching_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	        WHEN batching_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
	        WHEN batching_ingredients_details.new_ingredients_id IS NOT NULL THEN new_ingredients.ttp
	        ELSE batching_ingredients_details.ttp
	    END as ttp,
	    CASE
	        WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	        WHEN new_ingredients.packaging_size IS NOT NULL THEN new_ingredients.packaging_size
	        WHEN batching_ingredients_details.packaging_size IS NOT NULL THEN batching_ingredients_details.packaging_size
	        ELSE 1
	    END as packaging_size,
	    ROUND(
	        prep_qty * (
	            1 + (
	                1 - ROUND(
	                    batching_ingredients_details.yield / 100,
	                    4
	                )
	            )
	        ),
	        4
	    ) as ingredient_qty,
	    ROUND(
	        1 / (
	            CASE
	                WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	                WHEN new_ingredients.packaging_size IS NOT NULL THEN new_ingredients.packaging_size
	                WHEN batching_ingredients_details.packaging_size IS NOT NULL THEN batching_ingredients_details.packaging_size
	                ELSE 1
	            END
	        ) * prep_qty * (
	            1 + (
	                1 - ROUND(
	                    batching_ingredients_details.yield / 100,
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
	                    WHEN new_ingredients.packaging_size IS NOT NULL THEN new_ingredients.packaging_size
	                    WHEN batching_ingredients_details.packaging_size IS NOT NULL THEN batching_ingredients_details.packaging_size
	                    ELSE 1
	                END
	            ) * prep_qty * (
	                1 + (
	                    1 - ROUND(
	                        batching_ingredients_details.yield / 100,
	                        4
	                    )
	                )
	            ),
	            4
	        ) * CASE
	            WHEN batching_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	            WHEN batching_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
	            WHEN batching_ingredients_details.new_ingredients_id IS NOT NULL THEN new_ingredients.ttp
	            ELSE batching_ingredients_details.ttp
	        END,
	        4
	    ) as cost
	FROM
	    batching_ingredients_details
	    LEFT JOIN item_masters ON item_masters.id = batching_ingredients_details.item_masters_id
	    LEFT JOIN menu_items ON menu_items.id = batching_ingredients_details.menu_as_ingredient_id
	    LEFT JOIN new_ingredients ON new_ingredients.id = batching_ingredients_details.new_ingredients_id
	    LEFT JOIN uoms ON batching_ingredients_details.uom_id = uoms.id
	    LEFT JOIN packagings ON packagings.id = COALESCE(
	        item_masters.packagings_id,
	        batching_ingredients_details.uom_id
	    );
; 

;