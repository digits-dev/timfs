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

    .primary_ingredient_description {
        background-color: yellow;
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
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ingredient Description</th>
                        <th>Batching Ingredient Code</th>
                        <th>Prepared By</th>
                        <th>Quantity</th>
                        <th>UOM</th>
                        <th>Mark Up</th>
                        <th>TTP</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$item->ingredient_description}}</td>
                        <td>{{$item->bi_code}}</td>
                        <td>{{$item->prepared_by}}</td>
                        <td>{{(float) $item->quantity}}</td>
                        <td>{{$item->packaging_description}}</td>
                        <td>{{$item->mark_up_percent}}%</td>
                        <td>{{$item->ttp}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->created_at}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
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
                            <th scope="col">Item Code</th>
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
                                    <td>{{ $ingredient->item_code }}</td>
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
                                            <span class="timeago date-updated" datetime="{{ $ingredient->updated_at ?? $ingredient->created_at }}"></span>
                                        @endif
                                    </td>
                                    <td>{{ (float) $ingredient->ingredient_qty }}</td>
                                    <td>{{ (float) $ingredient->cost }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr class="text-bold">
                            <td colspan="12" class="total-cost-label">Total Ingredient Cost</td>
                            <td>₱ {{ (float) $item->ingredient_total_cost }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="note-ingredients">** Highlighted ingredient names are primary ingredients.</p>
        @endif
    </div>
    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button"> <i class="fa fa-arrow-left" ></i> Back </a>
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