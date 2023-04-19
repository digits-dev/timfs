-- Active: 1678237171731@@127.0.0.1@3306@timfs

SELECT *
FROM rnd_menu_items
    LEFT JOIN rnd_menu_computed_food_cost ON rnd_menu_computed_food_cost.id = rnd_menu_items.id
    LEFT JOIN rnd_menu_computed_packaging_cost ON rnd_menu_computed_packaging_cost.id = rnd_menu_items.id