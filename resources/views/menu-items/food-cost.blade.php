@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script>
    const lowCost = {!! json_encode($low_cost_value) !!};
    const lowCostFromLocalStorage = localStorage.getItem('lowCost');
    if (lowCostFromLocalStorage && lowCostFromLocalStorage != lowCost) {
        location.assign("{{CRUDBooster::mainpath()}}/" + localStorage.getItem('lowCost'));
    }
</script>
<style>
    table, th, td {
        border: 1px solid rgb(215, 214, 214) !important;
        text-align: center;
    }

    .clickable {
        color: blue;
        cursor: pointer;
    }

    .clickable:hover{
        outline: 2px solid blue;
        background: rgb(220, 220, 220);
    }

    .loading-label {
        text-align: center;
        font-style: italic;
        color: grey;
    }

    .percentage-input-label {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .percentage-input-label > * {
        width: revert;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-dollar"></i><strong> Food Cost</strong>
    </div>

    <div class="panel-body">
        <label class="percentage-input-label">
            Low Cost Percentage
           <input class="percentage-input form-control percentage-text" type="number" step="any"/>
           <button class="btn btn-primary set-percentage-btn">Set</button>
       </label>
       <div class="loading-label text-center">Loading...</div>
        <table id="main-table" class="table table-striped table-bordered" style="display: none;">
            <thead>
                <tr class="active">
                    <th scope="col">Store</th>
                    <th scope="col">Low Cost</th>
                    <th scope="col">High Cost</th>
                    <th scope="col">No Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($concepts as $concept)
                    @php
                        // per concept
                        $concept_column_name = $concept->menu_segment_column_name;
                        $items = array_filter($menu_items, fn($obj) => $obj->$concept_column_name != null);
                        $high_cost = array_filter($items, fn($obj) => (float) $obj->food_cost_percentage > (float) $low_cost_value && $obj->food_cost);
                        $low_cost = array_filter($items, fn($obj) => ((float) $obj->food_cost_percentage <= (float) $low_cost_value || $obj->menu_price_dine == null || $obj->menu_price_dine == 0) && $obj->food_cost);
                        $no_cost = array_filter($items, fn($obj) => (float) $obj->food_cost == null || (float) $obj->food_cost == 0);
                        $high_cost_id = array_map(fn($obj) => $obj->id, $high_cost);
                        $low_cost_id = array_map(fn($obj) => $obj->id, $low_cost);
                        $no_cost_id = array_map(fn($obj) => $obj->id, $no_cost);
                    @endphp
                <tr>
                    <td>{{$concept->menu_segment_column_description}}</td>
                    <td class="clickable" filter="low" _id="{{$concept->id}}" items="{{implode(',', $low_cost_id)}}">{{count($low_cost)}}</td>
                    <td class="clickable" filter="high" _id="{{$concept->id}}" items="{{implode(',', $high_cost_id)}}">{{count($high_cost)}}</td>
                    <td class="clickable" filter="no" _id="{{$concept->id}}" items="{{implode(',', $no_cost_id)}}">{{count($no_cost)}}</td>
                </tr>
                @endforeach
                @php
                    // all concepts
                    $concept_column_name = $concept->menu_segment_column_name;
                    $high_cost = array_filter($menu_items, fn($obj) => (float) $obj->food_cost_percentage > (float) $low_cost_value && $obj->food_cost);
                    $low_cost = array_filter($menu_items, fn($obj) => ((float) $obj->food_cost_percentage <= (float) $low_cost_value || $obj->menu_price_dine == null) && $obj->food_cost);
                    $no_cost = array_filter($menu_items, fn($obj) => (float) $obj->food_cost == null || (float) $obj->food_cost == 0);
                    $high_cost_id = array_map(fn($obj) => $obj->id, $high_cost);
                    $low_cost_id = array_map(fn($obj) => $obj->id, $low_cost);
                    $no_cost_id = array_map(fn($obj) => $obj->id, $no_cost);
                @endphp
                @if ($menu_items)
                <tr>
                    <td>ALL</td>
                    <td class="clickable" filter="low" _id="all" items="{{implode(',', $low_cost_id)}}">{{count($low_cost)}}</td>
                    <td class="clickable" filter="high" _id="all" items="{{implode(',', $high_cost_id)}}">{{count($high_cost)}}</td>
                    <td class="clickable" filter="no" _id="all" items="{{implode(',', $no_cost_id)}}">{{count($no_cost)}}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('bottom')
<script> 
    document.title = 'Food Cost';
    $(document).ready(function() {


        $(document).on('click', '.clickable', function() {
            const td = $(this);
            const concept = td.attr('_id');
            const filter = td.attr('filter');
            const low_cost = Number(localStorage.getItem('lowCost')) || 30;
            location.assign("{{CRUDBooster::mainpath()}}/" + `${concept}/${filter}/${low_cost}`);
        });

        $('.percentage-text').val(lowCostFromLocalStorage || lowCost);

        $(document).on('click', '.set-percentage-btn', function() {
            const percentage = $('.percentage-text').val();
            localStorage.setItem('lowCost', percentage);
            location.assign("{{CRUDBooster::mainpath()}}/" + percentage);
        });

        $('table').DataTable({
            pagingType: 'full_numbers',
            pageLength: 100,
        });
        $('.loading-label').hide();
        $('#main-table').show();

        $('.percentage-input').on('keypress', function(event) {
            if (event.keyCode == 13) {
                $('.set-percentage-btn').click();
            }
        })

    });

</script>
@endpush