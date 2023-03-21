SELECT
    menu_items.id,
    menu_items.tasteless_menu_code,
    menu_items.menu_item_description,
    SUM() as food_cost
FROM menu_items
    LEFT JOIN (
        SELECT
            menu_items_id,
            menu_item_description,
            SUM(
                CASE
                    WHEN is_primary = 'TRUE' THEN cost
                    ELSE 0
                END
            ) as primary_cost,
            SUM(
                CASE
                    WHEN is_selected = 'TRUE' THEN cost
                    ELSE 0
                END
            ) as selected_cost
        FROM (
                SELECT
                    menu_items.id as menu_items_id,
                    menu_items.menu_item_description as menu_description,
                    menu_items.tasteless_menu_code,
                    menu_ingredients_auto_compute.ttp,
                    menu_ingredients_auto_compute.menu_item_description,
                    menu_ingredients_auto_compute.ingredient_group,
                    menu_ingredients_auto_compute.full_item_description,
                    menu_ingredients_auto_compute.is_primary,
                    menu_ingredients_auto_compute.is_selected,
                    menu_ingredients_auto_compute.is_existing,
                    menu_ingredients_auto_compute.cost
                FROM
                    menu_ingredients_auto_compute
                    LEFT JOIN menu_items on menu_items.id = menu_ingredients_auto_compute.menu_items_id
                WHERE
                    menu_ingredients_auto_compute.is_primary = 'TRUE'
                    OR menu_ingredients_auto_compute.is_selected = 'TRUE'
            ) as innerQuery
        GROUP BY
            ingredient_group
    ) as sub_query
GROUP BY menu_items_id