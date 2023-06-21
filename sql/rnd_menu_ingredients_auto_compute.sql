CREATE VIEW RND_MENU_INGREDIENTS_AUTO_COMPUTE AS 
	SELECT
	    rnd_menu_ingredients_details.id,
	    rnd_menu_ingredients_details.rnd_menu_items_id,
	    rnd_menu_ingredients_details.item_masters_id,
	    rnd_menu_ingredients_details.menu_as_ingredient_id,
	    rnd_menu_ingredients_details.new_ingredients_id,
	    rnd_menu_ingredients_details.batching_ingredients_id,
	    item_masters.full_item_description,
	    menu_items.menu_item_description,
	    new_ingredients.item_description,
	    batching_ingredients_computed_food_cost.ingredient_description,
	    rnd_menu_ingredients_details.ingredient_name,
	    rnd_menu_ingredients_details.ingredient_group,
	    rnd_menu_ingredients_details.row_id,
	    rnd_menu_ingredients_details.is_existing,
	    rnd_menu_ingredients_details.is_primary,
	    rnd_menu_ingredients_details.is_selected,
	    rnd_menu_ingredients_details.prep_qty,
	    rnd_menu_ingredients_details.uom_id,
	    uoms.uom_description,
	    rnd_menu_ingredients_details.menu_ingredients_preparations_id,
	    packagings.packaging_description,
	    rnd_menu_ingredients_details.yield,
	    rnd_menu_ingredients_details.status,
	    menu_items.food_cost AS food_cost,
	    ROUND(
	        rnd_menu_ingredients_details.yield / 100,
	        4
	    ) as converted_yield,
	    1 as uom_qty,
	    CASE
	        WHEN rnd_menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	        WHEN rnd_menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
	        WHEN rnd_menu_ingredients_details.batching_ingredients_id IS NOT NULL THEN ROUND(
	            batching_ingredients_computed_food_cost.food_cost,
	            4
	        )
	        WHEN rnd_menu_ingredients_details.new_ingredients_id IS NOT NULL THEN new_ingredients.ttp
	        ELSE rnd_menu_ingredients_details.ttp
	    END as ttp,
	    CASE
	        WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
	        WHEN new_ingredients.packaging_size IS NOT NULL THEN new_ingredients.packaging_size
	        WHEN rnd_menu_ingredients_details.packaging_size IS NOT NULL THEN rnd_menu_ingredients_details.packaging_size
	        ELSE 1
	    END as packaging_size,
	    ROUND(
	        prep_qty * (
	            1 + (
	                1 - ROUND(
	                    rnd_menu_ingredients_details.yield / 100,
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
	                WHEN rnd_menu_ingredients_details.packaging_size IS NOT NULL THEN rnd_menu_ingredients_details.packaging_size
	                ELSE 1
	            END
	        ) * prep_qty * (
	            1 + (
	                1 - ROUND(
	                    rnd_menu_ingredients_details.yield / 100,
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
	                    WHEN rnd_menu_ingredients_details.packaging_size IS NOT NULL THEN rnd_menu_ingredients_details.packaging_size
	                    ELSE 1
	                END
	            ) * prep_qty * (
	                1 + (
	                    1 - ROUND(
	                        rnd_menu_ingredients_details.yield / 100,
	                        4
	                    )
	                )
	            ),
	            4
	        ) * CASE
	            WHEN rnd_menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
	            WHEN rnd_menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
	            WHEN rnd_menu_ingredients_details.batching_ingredients_id IS NOT NULL THEN ROUND(
	                batching_ingredients_computed_food_cost.food_cost,
	                4
	            )
	            WHEN rnd_menu_ingredients_details.new_ingredients_id IS NOT NULL THEN new_ingredients.ttp
	            ELSE rnd_menu_ingredients_details.ttp
	        END,
	        4
	    ) as cost
	FROM
	    rnd_menu_ingredients_details
	    LEFT JOIN item_masters ON item_masters.id = rnd_menu_ingredients_details.item_masters_id
	    LEFT JOIN menu_items ON menu_items.id = rnd_menu_ingredients_details.menu_as_ingredient_id
	    LEFT JOIN new_ingredients ON new_ingredients.id = rnd_menu_ingredients_details.new_ingredients_id
	    LEFT JOIN batching_ingredients_computed_food_cost ON batching_ingredients_computed_food_cost.id = rnd_menu_ingredients_details.batching_ingredients_id
	    LEFT JOIN uoms ON uoms.id = COALESCE(
	        item_masters.uoms_id,
	        menu_items.uoms_id,
	        rnd_menu_ingredients_details.uom_id
	    )
	    LEFT JOIN packagings ON packagings.id = COALESCE(
	        item_masters.packagings_id,
	        rnd_menu_ingredients_details.uom_id
	    );
; 

;