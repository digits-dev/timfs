SELECT
    menu_items.id AS menu_items_id,
    menu_items.tasteless_menu_code,
    menu_items.menu_item_description,
    menu_items.portion_size,
    menu_computed_food_cost.computed_food_cost AS recipe_cost_wo_buffer,
    menu_items.buffer,
    ROUND(
        menu_computed_food_cost.computed_food_cost * (1 + (menu_items.buffer / 100)) / menu_items.portion_size,
        4
    ) AS final_recipe_cost,
    menu_computed_packaging_cost.computed_packaging_total_cost AS packaging_cost,
    menu_items.ideal_food_cost,
    ROUND(
        ROUND(
            menu_computed_food_cost.computed_food_cost * (1 + (menu_items.buffer / 100)) / menu_items.portion_size,
            4
        ) / (
            menu_items.ideal_food_cost / 100
        ) * 1.12,
        4
    ) AS suggested_final_srp_w_vat,
    ROUND(
        COALESCE(
            menu_items.menu_price_dine,
            menu_items.menu_price_take
        ) / 1.12,
        4
    ) AS final_srp_wo_vat,
    COALESCE(
        menu_items.menu_price_dine,
        menu_items.menu_price_take
    ) AS final_srp_w_vat_dine_in,
    menu_items.menu_price_take as final_srp_w_vat_take_out,
    menu_items.menu_price_dlv as final_srp_w_vat_delivery,
    ROUND(
        menu_computed_packaging_cost.computed_packaging_total_cost / ROUND(
            COALESCE(
                menu_items.menu_price_dine,
                menu_items.menu_price_take
            ) / 1.12,
            4
        ) * 100,
        2
    ) AS cost_packaging_from_final_srp,
    ROUND(
        ROUND(
            menu_computed_food_cost.computed_food_cost * (1 + (menu_items.buffer / 100)) / menu_items.portion_size,
            4
        ) / ROUND(
            COALESCE(
                menu_items.menu_price_dine,
                menu_items.menu_price_take
            ) / 1.12,
            4
        ) * 100,
        2
    ) AS food_cost_from_final_srp,
    ROUND(
        COALESCE(
            ROUND(
                menu_computed_packaging_cost.computed_packaging_total_cost / ROUND(
                    COALESCE(
                        menu_items.menu_price_dine,
                        menu_items.menu_price_take
                    ) / 1.12,
                    4
                ) * 100,
                2
            ),
            0
        ) + COALESCE(
            ROUND(
                ROUND(
                    menu_computed_food_cost.computed_food_cost * (1 + (menu_items.buffer / 100)) / menu_items.portion_size,
                    4
                ) / ROUND(
                    COALESCE(
                        menu_items.menu_price_dine,
                        menu_items.menu_price_take
                    ) / 1.12,
                    4
                ) * 100,
                2
            ),
            0
        ),
        2
    ) AS total_cost,
    menu_items.status
FROM menu_items
    LEFT JOIN menu_computed_food_cost ON menu_computed_food_cost.id = menu_items.id
    LEFT JOIN menu_computed_packaging_cost ON menu_computed_packaging_cost.id = menu_items.id