-- Active: 1678237171731@@127.0.0.1@3306@timfs

CREATE VIEW MENU_INGREDIENTS_AUTO_COMPUTE AS 
	SELECT
	    *,
	    round( (
	            innerQuery.qty * innerQuery.ttp
	        ) / 1000,
	        4
	    ) AS cost
	FROM (
	        SELECT
	            menu_ingredients_details_temp.id,
	            menu_ingredients_details_temp.menu_items_id,
	            menu_ingredients_details_temp.item_masters_id,
	            menu_ingredients_details_temp.menu_as_ingredient_id,
	            item_masters_ttp.full_item_description,
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
	            round( (prep_qty / (yield) * 100), 4) AS qty,
	            CASE
	                WHEN item_masters_ttp.id IS NOT NULL THEN item_masters_ttp.computed_ttp
	                WHEN menu_ingredients_details_temp.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost_temp, 4)
	                ELSE menu_ingredients_details_temp.ttp
	            END AS ttp
	        FROM
	            menu_ingredients_details_temp
	            LEFT JOIN item_masters_ttp ON item_masters_ttp.id = menu_ingredients_details_temp.item_masters_id
	            LEFT JOIN menu_items ON menu_items.id = menu_ingredients_details_temp.menu_as_ingredient_id
	            LEFT JOIN uoms ON menu_ingredients_details_temp.uom_id = uoms.id
	            LEFT JOIN packagings ON packagings.id = menu_ingredients_details_temp.uom_id
	    ) AS innerQuery
