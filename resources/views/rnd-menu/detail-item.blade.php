@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/aee358fec0.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<style>
    th, td {
        text-align: center;
    }

    .total-cost-label, .percentage-label {
        text-align: right;
        font-weight: bold;
    }

    .total-cost, .food-cost-percentage, .food-cost {
        font-weight: bold
    }

    .note-ingredients, .note-packagings {
        color: blue;
        font-weight: bold;
        margin-top: 10px;
    }

    .label-secondary {
        background: #7e57c2;
    }

    .date-updated {
        font-size: 11px;
        font-style: italic;
        color: slategrey;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data RND Menu Items
    </a>
</p>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> Detail RND Menu Item</strong>
    </div>
    <div class="panel-body">
        <h4 style="font-weight: 600; text-align: center;">RND Menu Information</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">RND Menu Item Code</th>
                    <th scope="col">RND Menu Item Description</th>
                    <th scope="col">RND Menu SRP</th>
                    <th scope="col">Portion Size</th>
                    <th scope="col">Food Cost</th>
                    <th scope="col">Food Cost Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$item->rnd_code}}</td>
                    <td>{{$item->rnd_menu_description}}</td>
                    <td class="peso">{{'₱ ' . (float) $item->rnd_menu_srp}}</td>
                    <td>{{(float) $item->portion_size}}</td>
                    <td class="food-cost">{{$item->computed_food_cost ? '₱ ' . (float) $item->computed_food_cost : '0'}}</td>
                    <td class="food-cost-percentage">{{$item->computed_food_cost_percentage ? (float) $item->computed_food_cost_percentage . '%' : '0%'}}</td>
                </tr>
            </tbody>
        </table>
        <h4 class="no-ingredient-warning" style="color: gray; text-align: center; font-style: italic; display: none"> <i class="fa fa-spoon"></i> No ingredients to show...</h4>
        <div class="with-ingredient" style="display: none;">
            <h4 style="font-weight: 600; text-align: center;">Ingredients List</h4>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"> </th>
                            <th scope="col">Status</th>
                            <th scope="col">From</th>
                            <th scope="col">Tasteless Code</th>
                            <th scope="col">Ingredient</th>
                            <th scope="col">Packaging Size</th>
                            <th scope="col">Preparation Qty</th>
                            <th scope="col">UOM</th>
                            <th scope="col">Preparation</th>
                            <th scope="col">Yield</th>
                            <th scope="col">TTP</th>
                            <th scope="col">Ingredient Qty</th>
                            <th scope="col">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="ingredient-tbody">
                    </tbody>
                </table>
            </div>
        </div>
        <p class="note-ingredients" style="display: none">** Highlighted ingredient names are primary ingredients.</p>
        <h4 class="no-packaging-warning" style="color: gray; text-align: center; font-style: italic; display: none"> <i class="fa fa-shopping-bag"></i> No packagings to show...</h4>
        <div class="with-packaging" style="display: none;">
            <h4 style="font-weight: 600; text-align: center;">Packagings List</h4>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"> </th>
                            <th scope="col">Status</th>
                            <th scope="col">From</th>
                            <th scope="col">Tasteless Code</th>
                            <th scope="col">Packaging</th>
                            <th scope="col">Packaging Size</th>
                            <th scope="col">Preparation Qty</th>
                            <th scope="col">UOM</th>
                            <th scope="col">Preparation</th>
                            <th scope="col">Yield</th>
                            <th scope="col">TTP</th>
                            <th scope="col">Packaging Qty</th>
                            <th scope="col">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="packaging-tbody">
                    </tbody>
                </table>
            </div>
        </div>
        <p class="note-packagings" style="display: none">** Highlighted packaging names are primary packagings.</p>
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button" id="export"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>
@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');
        
        const ingredients = {!! json_encode($ingredients) !!};
        const packagings = {!! json_encode($packagings) !!};
        const item = {!! json_encode($item) !!};
        for (const key in item) {
           if (!isNaN(item[key])) item[key] = parseFloat(item[key]);
        }

        function showIngredients() {
            const ingredientTbody = $('.ingredient-tbody');
            const groupCount = [...new Set([...ingredients.map(e => e.ingredient_group)])];
            
            for (const i of groupCount) {
                const groupedIngredients = ingredients.filter(e => e.ingredient_group == i);
                const isSelected = groupedIngredients.find(e => e.is_selected == 'TRUE');
                let primary;
                if (isSelected) isSelected.checked = true;
                else groupedIngredients.find(e => e.is_primary == 'TRUE').checked = true;
                groupedIngredients.forEach(groupedIngredient => {
                    for (const key in groupedIngredient) {
                        if (!isNaN(groupedIngredient[key])) groupedIngredient[key] = parseFloat(groupedIngredient[key]) || true;
                    }
                    const tr = $(document.createElement('tr'));
                    const check = $(document.createElement('td'))
                        .text(groupedIngredient.checked ? '✓' : '')
                        .css('font-weight', '700');
                    const status = $(document.createElement('td'));
                    const from = $(document.createElement('td'))
                    const tastelessCode = $(document.createElement('td'))
                        .text(
                            groupedIngredient.tasteless_code ||
                            groupedIngredient.tasteless_menu_code ||
                            'No Item Code'
                        ).css('font-style', !groupedIngredient.tastelessCode)
                    const ingredient = $(document.createElement('td'));
                    const ingredientSpan = $(document.createElement('span'))
                        .text(
                            groupedIngredient.full_item_description ||
                            groupedIngredient.menu_item_description ||
                            groupedIngredient.ingredient_description ||
                            groupedIngredient.item_description
                        ).css('background', groupedIngredient.checked ? 'yellow' : '');
                    ingredient.html(ingredientSpan);
                    const packagingSize = $(document.createElement('td')).text(groupedIngredient.packaging_size)
                    const preparationQty = $(document.createElement('td')).text(groupedIngredient.prep_qty);
                    const uom = $(document.createElement('td')).text(groupedIngredient.uom_description);
                    const preparation = $(document.createElement('td')).text(groupedIngredient.preparation_desc);
                    const yield = $(document.createElement('td')).text(groupedIngredient.yield + '%');
                    const ttpSpan = $(document.createElement('span'))
                        .addClass('date-updated')
                        .text(groupedIngredient.item_masters_id ? timeago.format(groupedIngredient.updated_at || groupedIngredient.created_at) : '')
                    const ttp = $(document.createElement('td')).html('₱ ' + (groupedIngredient.ttp || '0.00') + '<br/>').append(ttpSpan);
                    const ingredientQty = $(document.createElement('td')).text(groupedIngredient.ingredient_qty);
                    const cost = $(document.createElement('td')).text('₱ ' + (groupedIngredient.cost || '0.00'));
    
                    if (groupedIngredient.full_item_description || groupedIngredient.item_masters_id)
                        from.html('<span class="label label-info">IMFS</span>')
                    else if (groupedIngredient.menu_item_description)
                        from.html('<span class="label label-warning">MIMF</span>')
                    else if (groupedIngredient.ingredient_description)
                        from.html('<span class="label label-secondary">BATCH</span>')
                    else
                        from.html('<span class="label label-success">NEW</span>')
    
                    if (groupedIngredient.menu_item_status == 'INACTIVE' || groupedIngredient.item_status == 'INACTIVE' || groupedIngredient.new_ingredient_status == 'INACTIVE' || groupedIngredient.batching_ingredient_status == 'INACTIVE')
                        status.html('<span class="label label-danger">INACTIVE</span>')
                    else if (groupedIngredient.menu_item_status == 'ACTIVE' || groupedIngredient.item_status == 'ACTIVE' || groupedIngredient.new_ingredient_status == 'ACTIVE' || groupedIngredient.batching_ingredient_status == 'ACTIVE')
                        status.html('<span class="label label-success">ACTIVE</span>')
                    else if (groupedIngredient.menu_item_status == 'ALTERNATIVE' || groupedIngredient.item_status == 'ALTERNATIVE')
                        status.html('<span class="label label-primary">ALTERNATIVE</span>')
                    tr.append(
                        check,
                        status,
                        from,
                        tastelessCode,
                        ingredient,
                        packagingSize,
                        preparationQty,
                        uom,
                        preparation,
                        yield,
                        ttp,
                        ingredientQty,
                        cost
                    );
                    ingredientTbody.append(tr);
                });
            }
    
            const lastRow = $(document.createElement('tr')).css('font-weight', 'bold');
            const totalCostLabel = $(document.createElement('td'))
                .text('Total Ingredient Cost')
                .attr('colspan', 12)
                .addClass('total-cost-label');
            const totalCostValue = $(document.createElement('td')).text('₱ ' + item.computed_ingredient_total_cost);
            lastRow.append(totalCostLabel, totalCostValue);       
            ingredientTbody.append(lastRow);
    
            if (!ingredients.length) {
                $('.no-ingredient-warning').css('display', '');
            } else {
                $('.with-ingredient').css('display', '');
                $('.note-ingredients').css('display', '');
            }

            const lowCost = Number(localStorage.getItem('lowCost')) || 30;
    
            if (item.rnd_menu_srp > 0 && item.computed_food_cost_percentage > lowCost) {
                $('.food-cost-percentage').css('color', 'red');
            }
        }

        function showPackagings() {
            const packagingTBody = $('.packaging-tbody');
            const groupCount = [...new Set([...packagings.map(e => e.packaging_group)])];

            for (const i of groupCount) {
                const groupedPackagings = packagings.filter(e => e.packaging_group == i);
                const isSelected = groupedPackagings.find(e => e.is_selected == 'TRUE');
                let primary;
                if (isSelected) isSelected.checked = true;
                else groupedPackagings.find(e => e.is_primary == 'TRUE').checked = true;
                groupedPackagings.forEach(groupedPackaging => {
                    for (const key in groupedPackaging) {
                        if (!isNaN(groupedPackaging[key])) groupedPackaging[key] = parseFloat(groupedPackaging[key]) || true;
                    }
                    const tr = $(document.createElement('tr'));
                    const check = $(document.createElement('td'))
                        .text(groupedPackaging.checked ? '✓' : '')
                        .css('font-weight', '700');
                    const status = $(document.createElement('td'));
                    const from = $(document.createElement('td'))
                    const tastelessCode = $(document.createElement('td'))
                        .text(
                            groupedPackaging.tasteless_code ||
                            groupedPackaging.tasteless_menu_code ||
                            'No Item Code'
                        ).css('font-style', !groupedPackaging.tastelessCode)
                    const packaging = $(document.createElement('td'));
                    const packagingSpan = $(document.createElement('span'))
                        .text(
                            groupedPackaging.full_item_description ||
                            groupedPackaging.menu_item_description ||
                            groupedPackaging.item_description
                        ).css('background', groupedPackaging.checked ? 'yellow' : '');
                    packaging.html(packagingSpan);
                    const packagingSize = $(document.createElement('td')).text(groupedPackaging.packaging_size)
                    const preparationQty = $(document.createElement('td')).text(groupedPackaging.prep_qty);
                    const uom = $(document.createElement('td')).text(groupedPackaging.uom_description);
                    const preparation = $(document.createElement('td')).text(groupedPackaging.preparation_desc);
                    const yield = $(document.createElement('td')).text(groupedPackaging.yield + '%');
                    const ttpSpan = $(document.createElement('span'))
                        .addClass('date-updated')
                        .text(groupedPackaging.item_masters_id ? timeago.format(groupedPackaging.updated_at || groupedPackaging.created_at) : '')
                    const ttp = $(document.createElement('td')).html('₱ ' + (groupedPackaging.ttp || '0.00') + '<br/>').append(ttpSpan);
                    const packagingQty = $(document.createElement('td')).text(groupedPackaging.packaging_qty);
                    const cost = $(document.createElement('td')).text('₱ ' + (groupedPackaging.cost || '0.00'));
    
                    if (groupedPackaging.full_item_description || groupedPackaging.item_masters_id)
                        from.html('<span class="label label-info">IMFS</span>')
                    else if (groupedPackaging.menu_item_description)
                        from.html('<span class="label label-warning">MIMF</span>')
                    else
                        from.html('<span class="label label-success">NEW</span>')
    
                    if (groupedPackaging.menu_item_status == 'INACTIVE' || groupedPackaging.item_status == 'INACTIVE' || groupedPackaging.new_packaging_status == 'INACTIVE')
                        status.html('<span class="label label-danger">INACTIVE</span>')
                    else if (groupedPackaging.menu_item_status == 'ACTIVE' || groupedPackaging.item_status == 'ACTIVE' ||
                    groupedPackaging.new_packaging_status == 'ACTIVE')
                        status.html('<span class="label label-success">ACTIVE</span>')
                    else if (groupedPackaging.menu_item_status == 'ALTERNATIVE' || groupedPackaging.item_status == 'ALTERNATIVE')
                        status.html('<span class="label label-primary">ALTERNATIVE</span>')
                    tr.append(
                        check,
                        status,
                        from,
                        tastelessCode,
                        packaging,
                        packagingSize,
                        preparationQty,
                        uom,
                        preparation,
                        yield,
                        ttp,
                        packagingQty,
                        cost
                    );
                    packagingTBody.append(tr);
                });
            }

            const lastRow = $(document.createElement('tr')).css('font-weight', 'bold');
            const totalCostLabel = $(document.createElement('td'))
                .text('Total Packaging Cost')
                .attr('colspan', 12)
                .addClass('total-cost-label');
            const totalCostValue = $(document.createElement('td')).text('₱ ' + item.computed_packaging_total_cost);
            lastRow.append(totalCostLabel, totalCostValue);       
            packagingTBody.append(lastRow);

            if (!packagings.length) {
                $('.no-packaging-warning').css('display', '');
            } else {
                $('.with-packaging').css('display', '');
                $('.note-packagings').css('display', '');
            }
        }
        showIngredients();
        showPackagings();
        $('table th, table td').css('border', '1px solid #aaaaaa').css('vertical-align', 'middle');
        $('table thead').css('background', '#deeaee');
    });
    
</script>
@endpush