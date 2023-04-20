@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style>
    thead {
        background: rgb(213, 211, 211);
    }

    .swal2-html-container {
        line-height: 3rem;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')
<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data RND Menu Items (For Approval)
    </a>
</p>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil"></i><strong> Edit RND Menu Item</strong>
    </div>
    <div class="panel-body">
        <form action="" id="form" class="form">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu Item Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_menu_description : ''}}" type="text" class="form-control rnd_menu_description" placeholder="RND Menu Item Description" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu SRP</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="custom-icon"><strong>₱</strong></span>
                            </div>
                            <input value="{{$item ? (float) $item->rnd_menu_srp : ''}}" type="number" class="form-control rnd_menu_srp" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu Item Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_code : ''}}" type="text" class="form-control rnd_code" placeholder="RND-XXXXX" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Tasteless Menu Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->tasteless_menu_code : ''}}" type="text" class="form-control rnd_tasteless_code" placeholder="XXXXXX" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h3 class="text-center">RND MENU COSTING</h3>
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">PARTICULARS</th>
                                <th class="text-center">VALUES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-bold">Portion Size</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->portion_size : ''}}" type="number" class="form-control portion-size" placeholder="Portion Size" step="any" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">Recipe Cost Without Buffer</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->recipe_cost_wo_buffer : ''}}" type="number" class="form-control recipe-cost-wo-buffer" placeholder="Recipe Cost Without Buffer" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Buffer</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->buffer : ''}}" type="number" class="form-control buffer" placeholder="Buffer" step="any" required readonly>
                                </td>
                            </tr>
                            <tr style="border-top: 2px solid #ddd;">
                                <td class="text-center text-bold">Final Recipe Cost</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->final_recipe_cost : ''}}" type="number" class="form-control final-recipe-cost" placeholder="Final Recipe Cost" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">PARTICULARS</th>
                                <th class="text-center">VALUES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-bold">Packaging Cost</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->packaging_cost : ''}}" type="number" class="form-control packaging-cost" placeholder="Packaging Cost" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Ideal Food Cost</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->ideal_food_cost : ''}}" type="number" class="form-control ideal-food-cost" placeholder="Ideal Food Cost" step="any" required readonly>
                                </td>
                            </tr>
                            <tr style="border-top: 2px solid #ddd;">
                                <td class="text-center text-bold">Suggested Final SRP With VAT</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->suggested_final_srp_w_vat : ''}}" type="number" class="form-control suggested-final-srp-w-vat" placeholder="Suggested Final SRP With VAT" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">PARTICULARS</th>
                                <th class="text-center">VALUES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-bold">Final SRP without VAT</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->final_srp_wo_vat : ''}}" type="number" class="form-control final-srp-wo-vat" placeholder="Final SRP without VAT" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">Final SRP with VAT</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->final_srp_w_vat : ''}}" type="number" class="form-control final-srp-w-vat" placeholder="Final SRP with VAT" step="any" required readonly>
                                </td>
                            </tr>
                            <tr style="border-top: 2px solid #ddd;">
                                <td class="text-center text-bold">% Cost Packaging From Final SRP</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->cost_packaging_from_final_srp : ''}}" type="number" class="form-control cost-packaging-from-final-srp" placeholder="% Cost Packaging From Final SRP" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Food Cost from Final SRP</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->food_cost_from_final_srp : ''}}" type="number" class="form-control food-cost-from-final-srp" placeholder="% Food Cost from Final SRP" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Total Cost</td>
                                <td class="text-center">
                                    <input value="{{$item ? (float) $item->total_cost : ''}}" type="number" class="form-control total-cost" placeholder="% Total Cost" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-2 h-5"><strong>Published by:</strong></div>
                <div class="col-md-3">{{$item->published_by}}</div>
                <div class="col-md-2"><strong>Published at:</strong></div>
                <div class="col-md-3">{{$item->published_at}}</div>
            </div>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-success pull-right" id="approve-btn"><i class="fa fa-thumbs-up" ></i> Approve</button>
		<button class="btn btn-danger pull-right" style="margin-right:10px" id="reject-btn"><i class="fa fa-thumbs-down" ></i> Reject</button>
    </div>
</div>




@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');

        function submitForm(action) {
            const form = $(document.createElement('form'))
                .attr('method', 'POST')
                .attr('action', "{{ route('approve_by_marketing') }}")
                .hide();

            const csrf = $(document.createElement('input'))
                .attr('name', '_token')
                .val("{{ csrf_token() }}")

            const rndMenuId = $(document.createElement('input'))
                .attr('name', 'rnd_menu_items_id')
                .val("{{ $item->rnd_menu_items_id }}");

            const actionData = $(document.createElement('input'))
                .attr('name', 'action')
                .val(action);
            
            form.append(csrf, rndMenuId, actionData);
            $('.panel-body').append(form);
            form.submit();
        }

        $('#approve-btn').on('click', function() {
            Swal.fire({
                title: 'Do you want to approve this item?',
                html: '🔵 Doing so will forward this item to <label class="label label-info">PURCHASING</label>.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Approve'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm('approve');
                }
            });
        });

        $('#reject-btn').on('click', function() {
            Swal.fire({
                title: 'Do you want to reject this item?',
                html: '🔴 Doing so will turn the status of this item to <label class="label label-danger">REJECTED</label>.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reject'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm('reject');
                }
            });
        });
    });
</script>
@endpush