SELECT
    menu_items.id,
    menu_items.tasteless_menu_code,
    menu_items.menu_item_description,
    menu_items.status,
    menu_items.portion_size,
    menu_items.ingredient_total_cost,
    menu_items.food_cost,
    menu_items.food_cost_percentage,
    subquery.computed_ingredient_total_cost,
    ROUND(
        subquery.computed_ingredient_total_cost * (1 + (menu_items.buffer / 100)) / menu_items.portion_size,
        4
    ) AS computed_food_cost,
    ROUND(
        ROUND(
            subquery.computed_ingredient_total_cost * (1 + (menu_items.buffer / 100)) / menu_items.portion_size,
            4
        ) / (
            ROUND( (
                    NULLIF(
                        COALESCE(
                            menu_items.menu_price_dine,
                            menu_items.menu_price_take
                        ),
                        0
                    ) - COALESCE(
                        menu_computed_packaging_cost.computed_packaging_total_cost,
                        0
                    )
                ) / 1.12,
                4
            )
        ) * 100,
        2
    ) AS computed_food_cost_percentage
FROM menu_items
    JOIN (
        SELECT
            menu_primary_ingredients.menu_items_id,
            SUM(
                menu_primary_ingredients.cost
            ) AS computed_ingredient_total_cost
        FROM
            menu_primary_ingredients
        GROUP BY
            menu_primary_ingredients.menu_items_id
    ) subquery ON subquery.menu_items_id = menu_items.id
    LEFT JOIN menu_computed_packaging_cost ON menu_computed_packaging_cost.id = menu_items.id