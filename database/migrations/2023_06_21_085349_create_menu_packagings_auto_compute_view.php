<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuPackagingsAutoComputeView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW menu_packagings_auto_compute AS 
            SELECT
                menu_packagings_details.id,
                menu_packagings_details.menu_items_id,
                menu_packagings_details.item_masters_id,
                menu_packagings_details.new_packagings_id,
                item_masters.full_item_description,
                new_packagings.item_description,
                menu_packagings_details.packaging_name,
                menu_packagings_details.packaging_group,
                menu_packagings_details.row_id,
                menu_packagings_details.is_existing,
                menu_packagings_details.is_primary,
                menu_packagings_details.is_selected,
                menu_packagings_details.prep_qty,
                menu_packagings_details.uom_id,
                uoms.uom_description,
                menu_packagings_details.menu_ingredients_preparations_id,
                packagings.packaging_description,
                menu_packagings_details.yield,
                menu_packagings_details.status,
                ROUND(
                    menu_packagings_details.yield / 100,
                    4
                ) as converted_yield,
                1 as uom_qty,
                CASE
                    WHEN menu_packagings_details.item_masters_id IS NOT NULL THEN item_masters.ttp
                    WHEN menu_packagings_details.new_packagings_id IS NOT NULL THEN new_packagings.ttp
                    ELSE menu_packagings_details.ttp
                END as ttp,
                CASE
                    WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                    WHEN new_packagings.packaging_size IS NOT NULL THEN new_packagings.packaging_size
                    WHEN menu_packagings_details.packaging_size IS NOT NULL THEN menu_packagings_details.packaging_size
                    ELSE 1
                END as packaging_size,
                ROUND(
                    prep_qty / (
                        ROUND(
                            menu_packagings_details.yield / 100,
                            4
                        )
                    ),
                    4
                ) as packaging_qty,
                ROUND(
                    1 / (
                        CASE
                            WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                            WHEN new_packagings.packaging_size IS NOT NULL THEN new_packagings.packaging_size
                            WHEN menu_packagings_details.packaging_size IS NOT NULL THEN menu_packagings_details.packaging_size
                            ELSE 1
                        END
                    ) * prep_qty / (
                        ROUND(
                            menu_packagings_details.yield / 100,
                            4
                        )
                    ),
                    4
                ) as modifier,
                ROUND(
                    ROUND(
                        1 / (
                            CASE
                                WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                                WHEN new_packagings.packaging_size IS NOT NULL THEN new_packagings.packaging_size
                                WHEN menu_packagings_details.packaging_size IS NOT NULL THEN menu_packagings_details.packaging_size
                                ELSE 1
                            END
                        ) * prep_qty / (
                            ROUND(
                                menu_packagings_details.yield / 100,
                                4
                            )
                        ),
                        4
                    ) * CASE
                        WHEN menu_packagings_details.item_masters_id IS NOT NULL THEN item_masters.ttp
                        WHEN menu_packagings_details.new_packagings_id IS NOT NULL THEN new_packagings.ttp
                        ELSE menu_packagings_details.ttp
                    END,
                    4
                ) as cost
            FROM menu_packagings_details
                LEFT JOIN item_masters ON item_masters.id = menu_packagings_details.item_masters_id
                LEFT JOIN new_packagings ON new_packagings.id = menu_packagings_details.new_packagings_id
                LEFT JOIN uoms ON uoms.id = COALESCE(
                    item_masters.uoms_id, menu_packagings_details.uom_id
                )
                LEFT JOIN packagings ON packagings.id = COALESCE(
                    item_masters.packagings_id, menu_packagings_details.uom_id
                );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS menu_packagings_auto_compute;");
    }
}
