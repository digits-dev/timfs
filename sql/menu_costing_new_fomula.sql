SELECT
    menu_items.id AS menu_items_id,
    menu_items.tasteless_menu_code,
    menu_items.menu_item_description,
    menu_items.portion_size,
    menu_computed_food_cost.computed_ingredient_total_cost AS recipe_cost_wo_buffer,
    menu_items.buffer,
    menu_computed_food_cost.computed_food_cost AS final_recipe_cost,
    menu_computed_packaging_cost.computed_packaging_total_cost AS packaging_cost,
    menu_items.ideal_food_cost,
    ROUND(
        menu_computed_food_cost.computed_food_cost / (
            menu_items.ideal_food_cost / 100
        ) * 1.12,
        4
    ) + menu_computed_packaging_cost.computed_packaging_total_cost AS suggested_final_srp_w_vat_plus_packaging_cost,
    ROUND(
        menu_items.menu_price_dine / 1.12,
        4
    ) AS final_srp_wo_vat,
    menu_computed_food_cost.computed_food_cost_percentage AS food_cost_from_final_srp,
    menu_computed_food_cost.computed_food_cost_percentage AS food_cost_percentage,
    menu_items.menu_price_dine AS final_srp_w_vat_dine_in,
    menu_items.menu_price_take as final_srp_w_vat_take_out,
    menu_items.menu_price_dlv as final_srp_w_vat_delivery,
    menu_items.status
FROM menu_items
    LEFT JOIN menu_computed_food_cost ON menu_computed_food_cost.id = menu_items.id
    LEFT JOIN menu_computed_packaging_cost ON menu_computed_packaging_cost.id = menu_items.id