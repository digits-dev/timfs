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
<p class="noprint">
    <a title='Return' href="{{ CRUDBooster::mainPath() }}">
        <i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}
    </a>
</p>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> Detail {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>

    <div class="panel-body">
        <h4 class="text-center text-bold">Batching Ingredient Details</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ingredient Description</th>
                        <th>Batching Ingredient Code</th>
                        <th>Portion Size</th>
                        <th>Food Cost</th>
                        <th>Created Date</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$item->ingredient_description}}</td>
                        <td>{{$item->bi_code}}</td>
                        <td>{{(float) $item->portion_size}}</td>
                        <td>{{(float) $item->food_cost}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->name}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="ingredient-section">
            <h4 class="text-center text-bold">
                Ingredients List
            </h4>
            <div class="table-responsive">
                <table class="table table-striped">
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
            <p class="note-ingredients">** Highlighted ingredient names are primary ingredients.</p>
        </div>
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>

@endsection

@push('bottom')
<script>
    const item = {!! json_encode($item) !!};
    const ingredients = {!! json_encode($ingredients) !!};
    $(document).ready(function() {

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
                            groupedIngredient.item_description
                        ).css('background', groupedIngredient.checked ? 'yellow' : '');
                    ingredient.html(ingredientSpan);
                    const packagingSize = $(document.createElement('td')).text(groupedIngredient.packaging_size)
                    const preparationQty = $(document.createElement('td')).text(groupedIngredient.prep_qty);
                    const uom = $(document.createElement('td')).text(groupedIngredient.packaging_description || groupedIngredient.uom_description);
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
                    else
                        from.html('<span class="label label-success">NEW</span>')
    
                    if (groupedIngredient.menu_item_status == 'INACTIVE' || groupedIngredient.item_status == 'INACTIVE' || groupedIngredient.new_ingredient_status == 'INACTIVE')
                        status.html('<span class="label label-danger">INACTIVE</span>')
                    else if (groupedIngredient.menu_item_status == 'ACTIVE' || groupedIngredient.item_status == 'ACTIVE' || groupedIngredient.new_ingredient_status == 'ACTIVE')
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
            const totalCostValue = $(document.createElement('td')).text('₱ ' + item.ingredient_total_cost);
            lastRow.append(totalCostLabel, totalCostValue);       
            ingredientTbody.append(lastRow);
        }

        showIngredients();
        $('table th, table td').css('border', '1px solid #aaaaaa').css('vertical-align', 'middle');
        $('table thead').css('background', '#deeaee');
    });
</script>

@endpush