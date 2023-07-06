@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style>
    .flex {
        display: flex;
    }

    .parent {
        flex-wrap: wrap;
    }

    .child {
        min-width: 500px;
        margin-top: 10px;
    }

    .child > * {
        width: 50%;
    }
    thead {
        background: rgb(213, 211, 211);
    }

    .swal2-html-container {
        line-height: 3rem;
    }

    td {
        vertical-align: middle !important;
    }

    table.costing-table td + td {
        border-left: 2px solid #b9b8b8 !important; 
    }

    table.costing-table, table.costing-table th {
        border: 2px solid #b9b8b8 !important; 
    }

    .divider {
        border-top: 2px solid #b9b8b8;
    }

    .srp-td {
        width: 30%;
        margin: auto 3px;
    }

    .row-srp {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-direction: row;
        flex-wrap: wrap;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        appearance: textfield;
        -moz-appearance: textfield; /* Firefox */
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
        <form method="POST" action="{{route('add_pos_update')}}" id="form" class="form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="control-label">Menu Item Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->menu_item_description : ''}}" type="text" class="form-control rnd_menu_description" placeholder="RND Menu Item Description" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
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
                            <input value="{{$item->tasteless_menu_code ?? ''}}" type="text" class="form-control rnd_tasteless_code" placeholder="XXXXXX" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">* Release Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input type="date" value="{{ $rnd_menu_item->release_date ?? '' }}" class="form-control release_date" name="release_date" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">End Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input type="date" value="{{ $rnd_menu_item->end_date ?? '' }}" class="form-control end_date" name="end_date" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">* POS Update Date</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input type="date" value="{{ $rnd_menu_item->pos_update ?? '' }}" class="form-control pos_update" name="pos_update" required>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h3 class="text-center">RND MENU COSTING</h3>
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-striped table-bordered costing-table">
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
                                    <input type="number" value="{{(float) $item->portion_size}}" class="form-control portion-size" placeholder="Portion Size" step="any" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">Recipe Cost Without Buffer</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->recipe_cost_wo_buffer}}" class="form-control recipe-cost-wo-buffer" placeholder="Recipe Cost Without Buffer" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Buffer</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->buffer}}" class="form-control buffer" placeholder="Buffer" step="any" required readonly>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td class="text-center text-bold">Final Recipe Cost</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->final_recipe_cost}}" class="form-control final-recipe-cost" placeholder="Final Recipe Cost" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <table class="table table-striped table-bordered costing-table">
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
                                    <input type="number" value="{{(float) $item->packaging_cost}}" class="form-control packaging-cost" placeholder="Packaging Cost" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Ideal Food Cost</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->ideal_food_cost}}" class="form-control ideal-food-cost" placeholder="Ideal Food Cost" step="any" required readonly>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td class="text-center text-bold">Suggested Final SRP With VAT</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->suggested_final_srp_w_vat}}" class="form-control suggested-final-srp-w-vat" placeholder="Suggested Final SRP With VAT" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-5">
                    <table class="table table-striped table-bordered costing-table">
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
                                    <input type="number" value="{{(float) $item->final_srp_wo_vat}}" class="form-control final-srp-wo-vat" placeholder="Final SRP without VAT" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Cost Packaging From Final SRP</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->cost_packaging_from_final_srp}}" class="form-control cost-packaging-from-final-srp" placeholder="% Cost Packaging From Final SRP" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Food Cost from Final SRP</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->food_cost_from_final_srp}}" class="form-control food-cost-from-final-srp" placeholder="% Food Cost from Final SRP" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Total Cost</td>
                                <td class="text-center">
                                    <input type="number" value="{{(float) $item->total_cost}}" class="form-control total-cost" placeholder="% Total Cost" step="any" readonly>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td class="text-center text-bold">Final SRP with VAT</td>
                                <td>
                                    <div class="row-srp">
                                        <div class="srp-td">
                                            <p class="text-center text-bold">Dine In</p>
                                            <input type="number" value="{{(float) $item->final_srp_w_vat_dine_in}}" class="form-control final-srp-w-vat-dine-in" placeholder="0.00" readonly>
                                        </div>
                                        <div class="srp-td">
                                            <p class="text-center text-bold">Take Out</p>
                                            <input type="number" value="{{(float) $item->final_srp_w_vat_take_out}}" class="form-control final-srp-w-vat-take-out" placeholder="0.00" readonly>
                                        </div>
                                        <div class="srp-td">
                                            <p class="text-center text-bold">Delivery</p>
                                            <input type="number" value="{{(float) $item->final_srp_w_vat_delivery}}" class="form-control final-srp-w-vat-delivery" placeholder="0.00" readonly>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="hide">
                <input type="text" name="rnd_menu_items_id" value="{{$item->rnd_menu_items_id}}">
                <button type="submit" id="submit-release-date"> Submit</button>
                @csrf
            </div>
        </form>
        <section class="menu-and-workflow">
            <div class="row">
                <div class="col-md-6">
                    <hr>
                    <h3 class="text-center">MENU ITEM DETAILS</h3>
                    <table class="table table-striped">
                        <tbody>
                            @foreach ($menu_items_data->old_codes as $index => $old_code)
                            <tr>
                                <td class="text-bold" style="width: 200px;">POS Old Item Code {{$index + 1}}</td> 
                                <td>{{ $menu_items_data->{$old_code->menu_old_code_column_name} }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-bold">POS Old Description</td> 
                                <td>{{$menu_items_data->pos_old_item_description}}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">Product Type</td> 
                                <td>{{ $menu_items_data->menu_product_types_name }}</td>
                            </tr>
                            @foreach ($menu_items_data->menu_choice_groups as $index => $choice_group)
                            <tr>
                                <td class="text-bold">Choices Group {{$index + 1}}</td> 
                                <td>{{ $menu_items_data->{'choices_' . $choice_group->menu_choice_group_column_name} }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">Choices Group {{$index + 1}} SKU</td> 
                                <td>
                                @foreach ($menu_items_data->{'choices_sku' . $choice_group->menu_choice_group_column_name} as $sku)
                                <label class="label label-primary">{{$sku}}</label>
                                @endforeach
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-bold">Menu Type</td> 
                                <td>{{ $menu_items_data->menu_type_description }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">Main Category</td> 
                                <td>{{ $menu_items_data->category_description }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">Sub Category</td> 
                                <td>{{ $menu_items_data->subcategory_description }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">Original Concept</td> 
                                <td>{{ $menu_items_data->original_concept }}</td>
                            </tr>
                            <tr>
                                <td class="text-bold">Store Segmentation</td>
                                <td>
                                @foreach ($menu_items_data->menu_segmentations as $segmentation)
                                <label class="label label-primary">{{$segmentation}}</label>
                                @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <section class="workflow-details">
                        <hr>
                        <h3 class="text-center">WORKFLOW DETAILS</h3>
                        <div class="flex parent">
                            <div class="flex child">
                                <div class="text-bold">Published by:</div>
                                <div>{{$workflow->published_by_name}}</div>
                            </div>
                            <div class="flex child">
                                <div class="text-bold">Published at:</div>
                                <div>{{$workflow->published_at}}</div>
                            </div>
                        </div>
                        <br>
                        <div class="flex parent">
                            <div class="flex child">
                                <div class="text-bold">Packaging updated by:</div>
                                <div>{{$workflow->packaging_updated_by_name}}</div>
                            </div>
                            <div class="flex child">
                                <div class="text-bold">Packaging updated at:</div>
                                <div>{{$workflow->packaging_updated_at}}</div>
                            </div>
                        </div>
                        <br>
                        <div class="flex parent">
                            <div class="flex child">
                                <div class="text-bold">Menu created by:</div>
                                <div>{{$workflow->menu_created_by_name}}</div>
                            </div>
                            <div class="flex child">
                                <div class="text-bold">Menu created at:</div>
                                <div>{{$workflow->menu_created_at}}</div>
                            </div>
                        </div>
                        <br>
                        <div class="flex parent">
                            <div class="flex child">
                                <div class="text-bold">Costing updated by:</div>
                                <div>{{$workflow->costing_updated_by_name}}</div>
                            </div>
                            <div class="flex child">
                                <div class="text-bold">Costing updated at:</div>
                                <div>{{$workflow->costing_updated_at}}</div>
                            </div>
                        </div>
                        <br>
                        <div class="flex parent">
                            <div class="flex child">
                                <div class="text-bold">Approved by (Marketing):</div>
                                <div>{{$workflow->marketing_approved_by_name}}</div>
                            </div>
                            <div class="flex child">
                                <div class="text-bold">Approved at (Marketing):</div>
                                <div>{{$workflow->marketing_approved_at}}</div>
                            </div>
                        </div>
                        <br>
                        <div class="flex parent">
                            <div class="flex child">
                                <div class="text-bold">Approved by (Accounting):</div>
                                <div>{{$workflow->accounting_approved_by_name}}</div>
                            </div>
                            <div class="flex child">
                                <div class="text-bold">Approved at (Accounting):</div>
                                <div>{{$workflow->accounting_approved_at}}</div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
        <hr>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save"></i> Save</button>
    </div>
</div>




@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');

        $('#save-btn').on('click', function() {
            Swal.fire({
                title: `Do you want to save the changes?`,
                html: `📝 You won't be able to revert this.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submit-release-date').click();
                }
            });
        });
    });

</script>



@endpush