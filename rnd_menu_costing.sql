CREATE VIEW RND_MENU_COSTING AS 
	SELECT
	    rnd_menu_items.id AS rnd_menu_items_id,
	    rnd_menu_items.rnd_menu_description,
	    rnd_menu_items.menu_items_id,
	    menu_items.menu_item_description,
	    menu_items.tasteless_menu_code,
	    rnd_menu_items.rnd_code,
	    rnd_menu_items.portion_size,
	    rnd_menu_computed_food_cost.computed_food_cost AS recipe_cost_wo_buffer,
	    rnd_menu_items.buffer,
	    ROUND(
	        rnd_menu_computed_food_cost.computed_food_cost * (
	            1 + (rnd_menu_items.buffer / 100)
	        ) / rnd_menu_items.portion_size,
	        4
	    ) AS final_recipe_cost,
	    rnd_menu_computed_packaging_cost.computed_packaging_total_cost AS packaging_cost,
	    rnd_menu_items.ideal_food_cost,
	    ROUND(
	        ROUND(
	            rnd_menu_computed_food_cost.computed_food_cost * (
	                1 + (rnd_menu_items.buffer / 100)
	            ) / rnd_menu_items.portion_size,
	            4
	        ) / (
	            rnd_menu_items.ideal_food_cost / 100
	        ) * 1.12,
	        4
	    ) AS suggested_final_srp_w_vat,
	    ROUND(
	        rnd_menu_items.rnd_menu_srp / 1.12,
	        4
	    ) AS final_srp_wo_vat,
	    rnd_menu_items.rnd_menu_srp AS final_srp_w_vat,
	    ROUND(
	        rnd_menu_computed_packaging_cost.computed_packaging_total_cost / ROUND(
	            rnd_menu_items.rnd_menu_srp / 1.12,
	            4
	        ) * 100,
	        2
	    ) AS cost_packaging_from_final_srp,
	    ROUND(
	        ROUND(
	            rnd_menu_computed_food_cost.computed_food_cost * (
	                1 + (rnd_menu_items.buffer / 100)
	            ) / rnd_menu_items.portion_size,
	            4
	        ) / ROUND(
	            rnd_menu_items.rnd_menu_srp / 1.12,
	            4
	        ) * 100,
	        2
	    ) AS food_cost_from_final_srp,
	    ROUND(
	        ROUND(
	            rnd_menu_computed_packaging_cost.computed_packaging_total_cost / ROUND(
	                rnd_menu_items.rnd_menu_srp / 1.12,
	                4
	            ) * 100,
	            2
	        ) + ROUND(
	            ROUND(
	                rnd_menu_computed_food_cost.computed_food_cost * (
	                    1 + (rnd_menu_items.buffer / 100)
	                ) / rnd_menu_items.portion_size,
	                4
	            ) / ROUND(
	                rnd_menu_items.rnd_menu_srp / 1.12,
	                4
	            ) * 100,
	            2
	        ),
	        2
	    ) AS total_cost,
	    rnd_menu_items.status
	FROM rnd_menu_items
	    LEFT JOIN rnd_menu_computed_food_cost ON rnd_menu_computed_food_cost.id = rnd_menu_items.id
	    LEFT JOIN rnd_menu_computed_packaging_cost ON rnd_menu_computed_packaging_cost.id = rnd_menu_items.id
	    LEFT JOIN menu_items ON rnd_menu_items.menu_items_id = (menu_items.id)
; 