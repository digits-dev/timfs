SELECT
    subquery.id AS rnd_menu_packagings_details_id,
    rnd_menu_items.id as rnd_menu_items_id,
    rnd_menu_items.rnd_code,
    rnd_menu_items.rnd_menu_description,
    item_as_packaging.tasteless_code AS tasteless_code,
    COALESCE(
        packaging_view.full_item_description,
        packaging_view.item_description,
        packaging_view.packaging_name
    ) AS packaging,
    packaging_view.prep_qty AS quantity,
    COALESCE(
        packaging_view.packaging_description,
        packaging_view.uom_description
    ) AS uom,
    COALESCE(subquery.cost, 0) AS cost
FROM rnd_menu_items
    JOIN (
        SELECT
            mi.id AS rnd_menu_items_id,
            COALESCE(sic.id, pic.id) AS id,
            ig.packaging_group,
            SUM(COALESCE(sic.cost, pic.cost)) AS cost
        FROM rnd_menu_items mi
            JOIN (
                SELECT
                    rnd_menu_items_id,
                    packaging_group
                FROM
                    rnd_menu_packagings_auto_compute
                GROUP BY
                    rnd_menu_items_id,
                    packaging_group
            ) ig ON mi.id = ig.rnd_menu_items_id
            JOIN rnd_menu_packagings_auto_compute pic ON mi.id = pic.rnd_menu_items_id
            AND pic.packaging_group = ig.packaging_group
            AND pic.is_primary = 'TRUE'
            AND pic.status = 'ACTIVE'
            LEFT JOIN (
                SELECT
                    id,
                    rnd_menu_items_id,
                    packaging_group,
                    cost
                FROM
                    rnd_menu_packagings_auto_compute
                WHERE
                    is_selected = 'TRUE'
                    AND rnd_menu_packagings_auto_compute.status = 'ACTIVE'
            ) sic ON mi.id = sic.rnd_menu_items_id
            AND sic.packaging_group = ig.packaging_group
            AND (
                sic.id IS NOT NULL
                OR pic.id IS NOT NULL
            )
        GROUP BY
            mi.id,
            COALESCE(sic.id, pic.id),
            ig.packaging_group
    ) subquery ON subquery.rnd_menu_items_id = rnd_menu_items.id
    LEFT JOIN rnd_menu_packagings_auto_compute packaging_view ON packaging_view.id = subquery.id
    LEFT JOIN item_masters item_as_packaging ON item_as_packaging.id = packaging_view.item_masters_id