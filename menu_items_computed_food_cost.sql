CREATE VIEW MENU_COMPUTED_FOOD_COST AS 
	SELECT
	    menu_items.id,
	    menu_items.tasteless_menu_code,
	    menu_items.menu_item_description,
	    menu_items.status,
	    menu_items.portion_size,
	    menu_items.ingredient_total_cost,
	    menu_items.food_cost,
	    menu_items.food_cost_percentage,
	    ROUND(SUM(subquery.cost), 4) as computed_ingredient_total_cost,
	    ROUND(
	        ROUND(SUM(subquery.cost), 4) / menu_items.portion_size,
	        4
	    ) AS computed_food_cost,
	    ROUND(
	        ROUND(
	            ROUND(SUM(subquery.cost), 4) / menu_items.portion_size,
	            4
	        ) / menu_items.menu_price_dine * 100,
	        2
	    ) AS computed_food_cost_percentage
	FROM menu_items
	    JOIN (
	        SELECT
	            mi.id AS menu_items_id,
	            ig.ingredient_group,
	            SUM(COALESCE(sic.cost, pic.cost)) AS cost
	        FROM menu_items mi
	            JOIN (
	                SELECT
	                    menu_items_id,
	                    ingredient_group
	                FROM
	                    menu_ingredients_auto_compute
	                GROUP BY
	                    menu_items_id,
	                    ingredient_group
	            ) ig ON mi.id = ig.menu_items_id
	            JOIN menu_ingredients_auto_compute pic ON mi.id = pic.menu_items_id
	            AND pic.ingredient_group = ig.ingredient_group
	            AND pic.is_primary = 'TRUE'
	            AND pic.status = 'ACTIVE'
	            LEFT JOIN (
	                SELECT
	                    menu_items_id,
	                    ingredient_group,
	                    cost
	                FROM
	                    menu_ingredients_auto_compute
	                WHERE
	                    is_selected = 'TRUE'
	                    AND menu_ingredients_auto_compute.status = 'ACTIVE'
	            ) sic ON mi.id = sic.menu_items_id
	            AND sic.ingredient_group = ig.ingredient_group
	        GROUP BY
	            mi.id,
	            ig.ingredient_group
	    ) subquery ON subquery.menu_items_id = menu_items.id
	GROUP BY (subquery.menu_items_id)
; 