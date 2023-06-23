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

    .primary_ingredient_description,
    .primary_packaging_description {
        background-color: yellow;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data Menu Items
    </a>
</p>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> Detail Menu Item</strong>
    </div>
    <div class="panel-body">
        <h4 style="font-weight: 600; text-align: center;">Menu Information</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Menu Item Code</th>
                    <th scope="col">Menu Item Description</th>
                    <th scope="col">Menu SRP</th>
                    <th scope="col">Portion Size</th>
                    <th scope="col">Food Cost</th>
                    <th scope="col">Food Cost Percentage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$item->tasteless_menu_code}}</td>
                    <td>{{$item->menu_item_description}}</td>
                    <td class="peso">{{'₱ ' . (float) $item->menu_price_dine}}</td>
                    <td>{{(float) $item->portion_size}}</td>
                    <td class="food-cost">{{$item->computed_food_cost ? '₱ ' . (float) $item->computed_food_cost : '0'}}</td>
                    <td class="food-cost-percentage">{{$item->computed_food_cost_percentage ? (float) $item->computed_food_cost_percentage . '%' : '0%'}}</td>
                </tr>
            </tbody>
        </table>
        @if (!$ingredients)
        <h4 class="no-ingredient-warning" style="color: gray; text-align: center; font-style: italic;"> <i class="fa fa-spoon"></i> No ingredients to show...</h4>
        @else
        <div class="with-ingredient">
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
                        @php
                            $grouped_ingredients = [];
                            foreach ($ingredients as $ingredient) {
                                $key = $ingredient->ingredient_group;
                                $grouped_ingredients[$key][] = $ingredient;
                            }

                        @endphp
                        @foreach ($grouped_ingredients as $group)
                            @php
                                $primary = array_filter($group, fn($obj) => $obj->is_selected == 'TRUE');
                                $column_name = !!$primary ? 'is_selected' : 'is_primary';
                                $group = array_map(function($obj) use ($column_name) {
                                    if ($obj->{$column_name} == 'TRUE') {
                                        $obj->is_checked = 'TRUE';
                                    }
                                    return $obj;
                                }, $group);

                            @endphp
                            @foreach ($group as $ingredient)
                                @php
                                    $status = $ingredient->menu_item_status ??
                                            $ingredient->item_status ??
                                            $ingredient->new_ingredient_status ??
                                            $ingredient->batching_ingredient_status;

                                    $description = $ingredient->full_item_description ??
                                            $ingredient->menu_item_description ??
                                            $ingredient->ingredient_description ??
                                            $ingredient->item_description;

                                @endphp
                                <tr>
                                    <td>{{ $ingredient->is_checked ? '✓' : '' }}</td>
                                    <td>
                                        @if ($status == 'INACTIVE')
                                        <span class="label label-danger">INACTIVE</span>
                                        @elseif ($status == 'ACTIVE')
                                        <span class="label label-success">ACTIVE</span>
                                        @else
                                        <span class="label label-primary">{{ $status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ingredient->item_masters_id)
                                        <span class="label label-info">IMFS</span>
                                        @elseif ($ingredient->menu_item_description)
                                        <span class="label label-warning">MIMF</span>
                                        @elseif ($ingredient->ingredient_description)
                                        <span class="label label-secondary">BATCH</span>
                                        @else
                                        <span class="label label-success">NEW</span>
                                        @endif
                                    </td>
                                    <td>{{ $ingredient->tasteless_code ?? $ingredient->tasteless_menu_code ?? '' }}</td>
                                    <td>
                                        <span class="{{ $ingredient->is_checked ? 'primary_ingredient_description' : '' }}">
                                            {{ $description }}
                                        </span>
                                    </td>
                                    <td>{{ (float) $ingredient->packaging_size }}</td>
                                    <td>{{ (float) $ingredient->prep_qty }}</td>
                                    <td>{{ $ingredient->packaging_description ?? $ingredient->uom_description }}</td>
                                    <td>{{ $ingredient->preparation_desc }}</td>
                                    <td>{{ (float) $ingredient->yield }}%</td>
                                    <td>
                                        ₱ {{ (float) $ingredient->ttp }}
                                        @if ($ingredient->item_masters_id)
                                            <br>
                                            <span class="timeago date-updated" datetime={{ $ingredient->updated_at ?? $ingredient->created_at }}></span>
                                        @endif
                                    </td>
                                    <td>{{ (float) $ingredient->ingredient_qty }}</td>
                                    <td>{{ (float) $ingredient->cost }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr class="text-bold">
                            <td colspan="12" class="total-cost-label">Total Ingredient Cost</td>
                            <td>₱ {{ (float) $item->computed_ingredient_total_cost }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="note-ingredients">** Highlighted ingredient names are primary ingredients.</p>
        @endif
        <hr>
        @if (!$packagings)
        <h4 class="no-packaging-warning" style="color: gray; text-align: center; font-style: italic;"> <i class="fa fa-shopping-bag"></i> No packagings to show...</h4>
        @else
        <div class="with-packaging">
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
                    <tbody class="ingredient-tbody">
                        @php
                            $grouped_packagings = [];
                            foreach ($packagings as $packaging) {
                                $key = $packaging->packaging_group;
                                $grouped_packagings[$key][] = $packaging;
                            }

                        @endphp
                        @foreach ($grouped_packagings as $group)
                            @php
                                $primary = array_filter($group, fn($obj) => $obj->is_selected == 'TRUE');
                                $column_name = !!$primary ? 'is_selected' : 'is_primary';
                                $group = array_map(function($obj) use ($column_name) {
                                    if ($obj->{$column_name} == 'TRUE') {
                                        $obj->is_checked = 'TRUE';
                                    }
                                    return $obj;
                                }, $group);

                            @endphp
                            @foreach ($group as $packaging)
                                @php
                                    $status = $packaging->menu_item_status ??
                                            $packaging->item_status ??
                                            $packaging->new_packaging_status ??
                                            $packaging->batching_packaging_status;

                                    $description = $packaging->full_item_description ??
                                            $packaging->menu_item_description ??
                                            $packaging->packaging_description ??
                                            $packaging->item_description;

                                @endphp
                                <tr>
                                    <td>{{ $packaging->is_checked ? '✓' : '' }}</td>
                                    <td>
                                        @if ($status == 'INACTIVE')
                                        <span class="label label-danger">INACTIVE</span>
                                        @elseif ($status == 'ACTIVE')
                                        <span class="label label-success">ACTIVE</span>
                                        @else
                                        <span class="label label-primary">{{ $status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($packaging->item_masters_id)
                                        <span class="label label-info">IMFS</span>
                                        @elseif ($packaging->menu_item_description)
                                        <span class="label label-warning">MIMF</span>
                                        @elseif ($packaging->packaging_description)
                                        <span class="label label-secondary">BATCH</span>
                                        @else
                                        <span class="label label-success">NEW</span>
                                        @endif
                                    </td>
                                    <td>{{ $packaging->tasteless_code ?? $packaging->tasteless_menu_code ?? '' }}</td>
                                    <td>
                                        <span class="{{ $packaging->is_checked ? 'primary_packaging_description' : '' }}">
                                            {{ $description }}
                                        </span>
                                    </td>
                                    <td>{{ (float) $packaging->packaging_size }}</td>
                                    <td>{{ (float) $packaging->prep_qty }}</td>
                                    <td>{{ $packaging->packaging_description ?? $packaging->uom_description }}</td>
                                    <td>{{ $packaging->preparation_desc }}</td>
                                    <td>{{ (float) $packaging->yield }}%</td>
                                    <td>
                                        ₱ {{ (float) $packaging->ttp }}
                                        @if ($packaging->item_masters_id)
                                            <br>
                                            <span class="timeago date-updated" datetime={{ $packaging->updated_at ?? $packaging->created_at }}></span>
                                        @endif
                                    </td>
                                    <td>{{ (float) $packaging->packaging_qty }}</td>
                                    <td>{{ (float) $packaging->cost }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr class="text-bold">
                            <td colspan="12" class="total-cost-label">Total Packaging Cost</td>
                            <td>₱ {{ (float) $item->computed_packaging_total_cost }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="note-packagings">** Highlighted packaging names are primary packagings.</p>
        @endif
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

        const timeAgoNodes = $('.timeago').get();
        if (timeAgoNodes.length) timeago.render(timeAgoNodes);
        $('table th, table td').css('border', '1px solid #aaaaaa').css('vertical-align', 'middle');
        $('table thead').css('background', '#deeaee');
    });
    
</script>
@endpush