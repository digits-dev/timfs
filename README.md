# 📄 TIMFS - Tasteless Item Masterfile System

## ✅ TODOS:

- [x] Show the SRP of the menu on the front end
- [x] Add new column `packaging_uom` in item_masters - should not be included on import QB template // Already exists (`packagings_id`)
- [x] Add 'Recipe' text
- [x] Change 'Ingredient SRP' to 'Ingredient Cost'
- [x] AutoFill the Ingredient UOM and Ingredient Cost
- [x] Implement the conversion // Not necessary because of the `packagings_id`
- [x] Implement cost vs. srp percentage
- [x] Implement option to add substitute ingredient
- [x] Add new column `ingredient_cost` in item_masters
- [x] brand searching
- [x] Fix the logic of the delete button
- [x] Add food cost on database
- [x] Add food cost when extracting menu items
- [x] Dashboard summary of Low cost and High cost per concept
- [x] When table data is clicked, it should redirect to a page of list of menu items
- [x] Update the localhost system like the one on production
- [x] Update the code uom_desc should be from the packagings_desc
- [x] Add experimental menu
- [x] Show the percentage of food cost vs srp on the list [new column]
- [x] Show the percentage of food cost vs srp on the details page of menu item
- [x] Add percentage on database and exporting
- [x] Add new column (No Cost)
- [x] fix the hacky way of cost filter (use form instead)
- [x] Use the `ttp` / `packaging_size` \* qty formula instead for cost
- [x] Update the view details page also
- [x] Implement the workflow of menu item
- [x] Show the version of the ingredient lists
- [x] Show when was the last price update
- [x] Update ingredient cost if imfs is updated
- [x] Implement the lastest formula
- [x] Turn the UOM to select
- [x] Add necessary inputs for the ingredients entered by users
- [x] Create a new migration file for new columns in database
- [x] Save the new fields to database
- [x] Render the new saved ingredients to the database
- [x] Improve the brand searching

---

## 🔄️ Workflow

- [x] Create various privileges (chef, marketing(approver), accounting(approver))

  - Only the chef can edit the ingredients

- [x] Create new column `temp_qty` and `temp_cost` in `menu_ingredients_details`
- [x] Create new table `menu_ingredients_approval`

  - Once the edit is saved, the qty and cost will be inserted to the temp cost and qty

- [x] Restrict the chef to edit again the menu items until approved by both or rejected by any or both of the approvers
- [x] Implement notification if any of the approvers approved or rejected the menu item.

  - Only marketing and accounting can approve or reject the edited menu items
  - The approval of the edit (by: marketing and accounting) can be done in any order
  - If both of the approvers approved the menu item, the food cost should be updated in menu items masterfile.

---

## 🟰 Ingredient Cost Formula

- `uom_qty` = 1
- `packaging_size` = from the database || from the user if new || 1
- `ttp` = from the database || from the user if new
- `prep_qty` = from the user
- `yield` = from the user
- `ingredient_modifier` = `uom_qty` / `packaging_size` \* `prep_qty` \* (1 + (1 - `yield`))
- `ingredient_cost` = `ingredient_modifier` \* `ttp`
- `ingredient_qty` = `prep_qty` \* (1 + (1 - `yield`))

---

## 📁 Files that need to be copied to timfs-prod

- database\migrations\2023_03_17_085417_create_menu_ingredients_preparations_table.php
- database\migrations\2023_03_17_132659_add_columns_for_preparation_to_menu_ingredients_details.php
- database\migrations\2023_03_24_094654_add_packaging_size_column_in_menu_ingredients_details.php
- database\migrations\2023_03_20_160426_create_menu_ingredients_auto_compute_view.php
- app\Http\Controllers\AdminMenuIngredientsPreparationsController.php
- database\migrations\2023_03_24_154943_add_portion_size_to_menu_items_table.php
- database\migrations\2023_03_27_090335_create_menu_food_cost_view.php

---

## ✏️ RND - Research and Development

- ### ✅ TODOS:

  - [x] Add packaging page for marketing
  - [x] Remove the RND Menu SRP in the costing page
  - [x] Add the details of newly created menu in the costing page
  - [ ] Add the details of newly created menu in the approver's page
  - [x] Restrict the user to publish w/o ingredient(s)
  - [x] Add Final SRP with VAT (Dine In)
  - [x] Add Final SRP with VAT (Take Out)
  - [x] Add Final SRP with VAT (Delivery)
  - [x] Thicken the divider line in the table
  - [x] Switch the position of percentage and final srp(s)
  - [ ] Add chat-box like comment section

- ### 📅 Tables

  - [ ] `rnd_menu_items`

    - `id`
    - `rnd_menu_description`
    - `rnd_code`
    - `rnd_tasteless_code`
    - `portion_size`
    - `rnd_menu_srp`
    - `rnd_menu_srp`
    - `status`

  - [ ] `rnd_menu_ingredients_details`

    - `id`
    - `rnd_menu_items_id`
    - `item_masters_id`
    - `menu_as_ingredient_id`
    - `ingredient_name`
    - `row_id`
    - `ingredient_group`
    - `is_primary`
    - `is_selected`
    - `is_primary`
    - `is_existing`
    - `status`
    - `packaging_size`
    - `prep-qty`
    - `qty`
    - `uom_id`
    - `menu_ingredients_preparations_id`
    - `yield`
    - `ttp`
    - `cost`

  - [ ] `rnd_menu_ingredients_auto_compute`

    ```sql
    CREATE VIEW rnd_menu_ingredients_auto_compute AS
                SELECT
                    rnd_menu_ingredients_details.id,
                    rnd_menu_ingredients_details.rnd_menu_items_id,
                    rnd_menu_ingredients_details.item_masters_id,
                    rnd_menu_ingredients_details.menu_as_ingredient_id,
                    item_masters.full_item_description,
                    menu_items.menu_item_description,
                    rnd_menu_ingredients_details.ingredient_name,
                    rnd_menu_ingredients_details.ingredient_group,
                    rnd_menu_ingredients_details.row_id,
                    rnd_menu_ingredients_details.is_existing,
                    rnd_menu_ingredients_details.is_primary,
                    rnd_menu_ingredients_details.is_selected,
                    rnd_menu_ingredients_details.prep_qty,
                    rnd_menu_ingredients_details.uom_id,
                    uoms.uom_description,
                    rnd_menu_ingredients_details.menu_ingredients_preparations_id,
                    packagings.packaging_description,
                    rnd_menu_ingredients_details.yield,
                    rnd_menu_ingredients_details.status,
                    menu_items.food_cost AS food_cost,
                    ROUND(
                        rnd_menu_ingredients_details.yield / 100,
                        4
                    ) as converted_yield,
                    1 as uom_qty,
                    CASE
                        WHEN rnd_menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
                        WHEN rnd_menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
                        ELSE rnd_menu_ingredients_details.ttp
                    END as ttp,
                    CASE
                        WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                        WHEN rnd_menu_ingredients_details.packaging_size IS NOT NULL THEN rnd_menu_ingredients_details.packaging_size
                        ELSE 1
                    END as packaging_size,
                    ROUND(
                        prep_qty * (
                            1 + (
                                1 - ROUND(
                                    rnd_menu_ingredients_details.yield / 100,
                                    4
                                )
                            )
                        ),
                        4
                    ) as ingredient_qty,
                    ROUND(
                        1 / (
                            CASE
                                WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                                WHEN rnd_menu_ingredients_details.packaging_size IS NOT NULL THEN rnd_menu_ingredients_details.packaging_size
                                ELSE 1
                            END
                        ) * prep_qty * (
                            1 + (
                                1 - ROUND(
                                    rnd_menu_ingredients_details.yield / 100,
                                    4
                                )
                            )
                        ),
                        4
                    ) as modifier,
                    ROUND(
                        ROUND(
                            1 / (
                                CASE
                                    WHEN item_masters.packaging_size IS NOT NULL THEN item_masters.packaging_size
                                    WHEN rnd_menu_ingredients_details.packaging_size IS NOT NULL THEN rnd_menu_ingredients_details.packaging_size
                                    ELSE 1
                                END
                            ) * prep_qty * (
                                1 + (
                                    1 - ROUND(
                                        rnd_menu_ingredients_details.yield / 100,
                                        4
                                    )
                                )
                            ),
                            4
                        ) * CASE
                            WHEN rnd_menu_ingredients_details.item_masters_id IS NOT NULL THEN item_masters.ttp
                            WHEN rnd_menu_ingredients_details.menu_as_ingredient_id IS NOT NULL THEN ROUND(menu_items.food_cost, 4)
                            ELSE rnd_menu_ingredients_details.ttp
                        END,
                        4
                    ) as cost
                FROM
                    rnd_menu_ingredients_details
                    LEFT JOIN item_masters ON item_masters.id = rnd_menu_ingredients_details.item_masters_id
                    LEFT JOIN menu_items ON menu_items.id = rnd_menu_ingredients_details.menu_as_ingredient_id
                    LEFT JOIN uoms ON rnd_menu_ingredients_details.uom_id = uoms.id
                    LEFT JOIN packagings ON packagings.id = (
                        rnd_menu_ingredients_details.uom_id
                    )
            ;

    ```

  - [ ] `rnd_menu_computed_food_cost`

    ```sql
     CREATE VIEW rnd_menu_computed_food_cost AS
                SELECT
                    rnd_menu_items.id,
                    rnd_menu_items.rnd_menu_description,
                    rnd_menu_items.status,
                    rnd_menu_items.portion_size,
                    ROUND(SUM(subquery.cost), 4) as computed_ingredient_total_cost,
                    ROUND(
                        ROUND(SUM(subquery.cost), 4) / rnd_menu_items.portion_size,
                        4
                    ) AS computed_food_cost,
                    ROUND(
                        ROUND(
                            ROUND(SUM(subquery.cost), 4) / rnd_menu_items.portion_size,
                            4
                        ) / rnd_menu_items.rnd_menu_srp * 100,
                        2
                    ) AS computed_food_cost_percentage
                FROM rnd_menu_items
                    JOIN (
                        SELECT
                            mi.id AS rnd_menu_items_id,
                            ig.ingredient_group,
                            SUM(COALESCE(sic.cost, pic.cost)) AS cost
                        FROM rnd_menu_items mi
                            JOIN (
                                SELECT
                                    rnd_menu_items_id,
                                    ingredient_group
                                FROM
                                    rnd_menu_ingredients_auto_compute
                                GROUP BY
                                    rnd_menu_items_id,
                                    ingredient_group
                            ) ig ON mi.id = ig.rnd_menu_items_id
                            JOIN rnd_menu_ingredients_auto_compute pic ON mi.id = pic.rnd_menu_items_id
                            AND pic.ingredient_group = ig.ingredient_group
                            AND pic.is_primary = 'TRUE'
                            AND pic.status = 'ACTIVE'
                            LEFT JOIN (
                                SELECT
                                    rnd_menu_items_id,
                                    ingredient_group,
                                    cost
                                FROM
                                    rnd_menu_ingredients_auto_compute
                                WHERE
                                    is_selected = 'TRUE'
                                    AND rnd_menu_ingredients_auto_compute.status = 'ACTIVE'
                            ) sic ON mi.id = sic.rnd_menu_items_id
                            AND sic.ingredient_group = ig.ingredient_group
                        GROUP BY
                            mi.id,
                            ig.ingredient_group
                    ) subquery ON subquery.rnd_menu_items_id = rnd_menu_items.id
                GROUP BY (subquery.rnd_menu_items_id)
            ;
    ```

  - [ ] `rnd_menu_approvals`
    - `id`
    - `rnd_menu_approval_status`
    - `rnd_menu_items_id`
    - `published_by`
    - `published_at`
    - `marketing_approved_by`
    - `marketing_approved_at`
    - `purchasing_approved_by`
    - `purchasing_approved_at`
    - `accounting_approved_by`
    - `accounting_approved_at`

- ### 🔄️ RND Workflow

  - #### 🧑‍🍳 Chef

    - create rnd menu item
    - add / save ingredients
    - add / save packagings
    - new ingredients and packagings will be forwarded to purchasing for item creation
    - edit the rnd menu if they wish to
    - submit the rnd menu to next step (**publish**)

  - #### 💹 Marketing

    - edit and finalize the packagings that chef inputted
    - view the food cost value only
    - individual ingredients are hidden
    - create the menu
    - add the costing of the menu

  - #### 👍 Marketing Approver

    - can see details about the menu except the ingredients
    - can approve or reject the rnd menu item

  - #### 💵 Purchasing (FOR ITEM CREATION) -> different module

    - should see all new items to be created
    - should not be able to see which menu the items are used
    - should create items in IMFS for new ingredients / packagings

  - #### 🧾 Accounting
    - same view as marketing
    - approve or reject
    - should not be able to approve until all ingredients and packagings are in IMFS

---

## 🖋️ Author:

- Fillinor Gunio
