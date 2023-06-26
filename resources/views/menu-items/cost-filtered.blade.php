@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

<style>
    table, th, td {
        border: 1px solid rgb(215, 214, 214) !important;
        text-align: center;
    }

    .action {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .concept-name {
        text-align: center;
        letter-spacing: 3px;
        font-weight: 600;
    }

    .filter-name {
        text-align: center;
        font-size: 16px;
        text-transform: uppercase;
        font-style: italic;
        color: grey;
    }

    .loading-label {
        text-align: center;
        font-style: italic;
        color: grey;
    }
</style>
@endpush


@extends('crudbooster::admin_template')
@section('content')
<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Food Cost
    </a>
</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-dollar"></i><strong> Food Cost</strong>
    </div>

    <div class="panel-body">
        <h3 class="concept-name">{{$concept->menu_segment_column_description ? $concept->menu_segment_column_description : 'ALL'}}</h3>
        <p class="filter-name">{{$filter}} Cost</p>
        <table id="tableData" class="table table-striped table-bordered">
            <thead>
                <tr class="active">
                    <th scope="col">Menu Item Code</th>
                    <th scope="col">Menu Item Description</th>
                    <th scope="col">SRP</th>
                    <th scope="col">Food Cost</th>
                    <th scope="col">Percentage</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menu_items as $menu_item)
                <tr>
                    <td>{{$menu_item->tasteless_menu_code}}</td>
                    <td>{{$menu_item->menu_item_description}}</td>
                    <td>{{(float) $menu_item->menu_price_dine}}</td>
                    <td>{{$menu_item->food_cost ? (float) $menu_item->food_cost : '0'}}</td>
                    <td>{{$menu_item->food_cost_percentage ? (float) $menu_item->food_cost_percentage : '0.00'}}%</td>
                    <td class="action">
                        <a class="action-button" href="#" _menu-item-id="{{ $menu_item->id }}" _action="detail">
                            <i class="fa fa-eye button"></i>
                        </a>
                        <a class="action-button" href="#" _menu-item-id="{{ $menu_item->id }}" _action="edit">
                            <i class="fa fa-pencil button"></i>
                        </a>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        <p class="loading-label">Loading...</p>
    </div>

    <div class="panel-footer">
        <a class="btn btn-primary" href="{{ CRUDBooster::mainpath() }}" type="button" id="export"> <i class="fa fa-arrow-left" ></i> Back </a>
    </div>
</div>

@endsection

@push('bottom')
<script type="text/javascript">
    const concept = {!! json_encode($concept) !!};
    const filter = {!! json_encode($filter) !!};
    document.title = `${concept ? concept.menu_segment_column_description.toUpperCase() : 'ALL'}: ${filter.toUpperCase()} COST`
    $(document).ready(function() {
        $('.loading-label').remove();
        const tbody = $('tbody');
        $('table').DataTable({
            pagingType: 'full_numbers',
            pageLength: 50,
        });

        $(document).on('click', '.action-button', function() {
            const clickedAction = $(this).attr('_action');
            const id = $(this).attr('_menu-item-id');
            const path = "{{ CRUDBooster::adminPath('menu_items') }}";
            if (clickedAction == 'edit') {
                Swal.fire({
                    title: 'Which details do you want to see?',
                    showDenyButton: true,
                    focusConfirm: false,
                    showCancelButton: true,
                    showCloseButton: true,
                    focusConfirm: true,
                    confirmButtonText: `🍕 Ingredients`,
                    denyButtonText: `🛍️ Packagings`,
                    cancelButtonText: `💲 Costing`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open(`${path}/edit/${id}/ingredients`);
                    } else if (result.isDenied) {
                        window.open(`${path}/edit/${id}/packagings`);
                    } else if (result.dismiss == 'cancel') {
                        window.open(`${path}/edit/${id}/costing`);
                    }
                });
            } else if (clickedAction == 'detail') {
                Swal.fire({
                    title: 'Which one do you want to edit?',
                    showDenyButton: true,
                    focusConfirm: false,
                    showCancelButton: true,
                    showCloseButton: true,
                    focusConfirm: true,
                    confirmButtonText: `🍕 Ingredients`,
                    denyButtonText: `🛍️ Packagings`,
                    cancelButtonText: `💲 Costing`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open(`${path}/detail/${id}`);
                    } else if (result.isDenied) {
                        window.open(`${path}/packaging-detail/${id}`);
                    } else if (result.dismiss == 'cancel') {
                        window.open(`${path}/costing-detail/${id}`);
                    }
                })
            }
        });
    });
</script>
@endpush