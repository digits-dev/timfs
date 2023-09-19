@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    table, tbody, td, th {
        border: 1px solid black !important;
        padding-left: 50px;
    }

    .swal2-html-container {
        line-height: 3rem !important;
    }


    .select2-container--default .select2-selection--single {
        border-radius: 0px !important
    }

    .select2-container .select2-selection--single {
        height: 35px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3190c7 !important;
        border-color: #367fa9 !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
    }

    .select2-container--default .select2-selection--multiple{
        border-radius: 0px !important;
        width: 100% !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered{
        padding: 0 !important;
        margin-top: -2px;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear{
        margin-right: 10px !important;
        padding: 0 !important;
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
        <i class="fa fa-pencil"></i><strong> Detail {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>
    <div class="panel-body">
        <h3 class="text-center">NON-TRADE ITEM</h3>
        <hr>
        <form action="{{ route('submit_non_trade_item') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th style="width: 35%"><span class="required-star">*</span> Menu Item Description</th>
                                <td><input type="text" class="form-control" name="menu_item_description" oninput="this.value = this.value.toUpperCase()" required></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Menu Product Type</th>
                                <td><input type="text" class="form-control" name="menu_product_type" oninput="this.value = this.value.toUpperCase()" required></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Menu Type</th>
                                <td>
                                    <select name="menu_types_id" class="form-control" name="menu_types_id" required>
                                        <option selected value="{{ $menu_type->id }}">{{ $menu_type->menu_type_description }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Main Category</th>
                                <td>
                                    <select name="menu_categories_id" class="form-control" name="menu_categories_id" required>
                                        <option selected value="{{ $menu_category->id }}">{{ $menu_category->category_description }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Sub Category</th>
                                <td>
                                    <select name="menu_subcategories_id" class="form-control" name="menu_subcategories_id" required>
                                        <option selected value="{{ $menu_subcategory->id }}">{{ $menu_subcategory->subcategory_description }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Original Concept</th>
                                <td>
                                    <select id="original_concept" name="original_concept[]" class="form-control" multiple required>
                                        @foreach ($concepts as $concept)
                                            <option value="{{ $concept->id }}">{{ $concept->segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th style="width: 35%"><span class="required-star">*</span> Price Dine In</th>
                                <td>
                                    <input type="number" class="form-control" name="menu_price_dine" placeholder="0.00" step="0.01" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Price Take Out</th>
                                <td>
                                    <input type="number" class="form-control" name="menu_price_take" placeholder="0.00" step="0.01" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Price Deliver</th>
                                <td>
                                    <input type="number" class="form-control" name="menu_price_dlv" placeholder="0.00" step="0.01" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Status</th>
                                <td>
                                    <input type="text" name="status" value="ACTIVE" class="form-control" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Store List</th>
                                <td>
                                    <select id="segmentations" name="segmentations[]" class="form-control" multiple required>
                                        @foreach ($menu_segmentations as $segmentation)
                                            <option value="{{ $segmentation->menu_segment_column_name }}">{{ $segmentation->menu_segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <button class="hide" type="submit" id="submit-btn">Submit</button>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save" ></i> Save</button>
    </div>
</div>

<script>
    $('#segmentations, #original_concept').select2({
        width: '100%',
    });

    $(document).on('click', '#save-btn', function() {
        Swal.fire({
            title: 'Do you want to save this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            returnFocus: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#submit-btn').click();
            }
        });
    });

    $('input').on('keypress', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            $('#save-btn').click();
        }
    });
</script>


@endsection

@push('bottom')
@endpush