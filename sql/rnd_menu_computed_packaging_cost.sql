-- Active: 1678237171731@@127.0.0.1@3306@timfs
SELECT
    rnd_menu_items.id AS id,
    rnd_menu_items.rnd_menu_description AS rnd_menu_description,
    rnd_menu_items.status AS status,
    ROUND(SUM(subquery.cost),
    4) AS computed_packaging_total_cost
FROM
    (
        rnd_menu_items
    JOIN(
        SELECT
            mi.id AS rnd_menu_items_id,
            ig.packaging_group AS packaging_group,
            SUM(
                COALESCE(sic.cost, pic.cost)
            ) AS cost
        FROM
            (
                (
                    (
                        rnd_menu_items mi
                    JOIN(
                        SELECT
                            rnd_menu_packagings_auto_compute.rnd_menu_items_id AS rnd_menu_items_id,
                            rnd_menu_packagings_auto_compute.packaging_group AS packaging_group
                        FROM
                            rnd_menu_packagings_auto_compute
                        GROUP BY
                            rnd_menu_packagings_auto_compute.rnd_menu_items_id,
                            rnd_menu_packagings_auto_compute.packaging_group
                    ) ig
                ON
                    ((mi.id = ig.rnd_menu_items_id))
                    )
                JOIN rnd_menu_packagings_auto_compute pic
                ON
                    (
                        (
                            (
                                mi.id = pic.rnd_menu_items_id
                            ) AND(
                                pic.packaging_group = ig.packaging_group
                            ) AND(pic.is_primary = 'TRUE') AND(pic.status = 'ACTIVE')
                        )
                    )
                )
            LEFT JOIN(
                SELECT
                    rnd_menu_packagings_auto_compute.rnd_menu_items_id AS rnd_menu_items_id,
                    rnd_menu_packagings_auto_compute.packaging_group AS packaging_group,
                    rnd_menu_packagings_auto_compute.cost AS cost
                FROM
                    rnd_menu_packagings_auto_compute
                WHERE
                    (
                        (
                            rnd_menu_packagings_auto_compute.is_selected = 'TRUE'
                        ) AND(
                            rnd_menu_packagings_auto_compute.status = 'ACTIVE'
                        )
                    )
            ) sic
        ON
            (
                (
                    (mi.id = sic.rnd_menu_items_id) AND(
                        sic.packaging_group = ig.packaging_group
                    )
                )
            )
            )
        GROUP BY
            mi.id,
            ig.packaging_group
    ) subquery
ON
    (
        (
            subquery.rnd_menu_items_id = rnd_menu_items.id
        )
    )
    )
GROUP BY
    subquery.rnd_menu_items_id