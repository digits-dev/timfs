SET @segmentation = 'segmentation_any';

SET
    @sql = CONCAT(
        "
        select
            t1.tasteless_code,
            t1.full_item_description,
            t2.full_item_description,
            t1.",
        @segmentation,
        ", t2.",
        @segmentation,
        " from
            tfgph_imfs.item_masters t1
            left join tfgph_trs.items t2 on t1.tasteless_code = t2.tasteless_code
        WHERE
            t1.",
        @segmentation,
        " != t2.",
        @segmentation
    );

PREPARE stmt FROM @sql;

EXECUTE stmt;

DEALLOCATE PREPARE stmt;