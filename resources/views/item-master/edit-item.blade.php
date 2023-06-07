@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    table, tbody, td, th {
        border: 1px solid black !important;
        padding-left: 50px;
    }

    th {
        width: 35%;
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
        <h3 class="text-center text-bold">Item Masterfile</h3>
    </div>
    <div class="panel-body">
        <form action="" class="form-main" autocomplete="off">
            <H3 class="text-center text-bold">ITEM DETAILS</H3>
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input type="text" name="full_item_description" id="full_item_description" class="form-control" required placeholder="Item Description" oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Brand Description</th>
                                <td>
                                    <select name="brands_id" id="brands_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Tax Code</th>
                                <td>
                                    <select name="tax_codes_id" id="tax_codes_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($tax_codes as $tax_code)
                                        <option value="{{ $tax_code->id }}">{{ $tax_code->tax_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Account</th>
                                <td>
                                    <select name="accounts_id" id="accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  COGS Account</th>
                                <td>
                                    <select name="cogs_accounts_id" id="cogs_accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($cogs_accounts as $cog_account)
                                        <option value="{{ $cog_account->id }}">{{ $cog_account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Asset Account</th>
                                <td>
                                    <select name="assets_accounts_id" id="assets_accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($asset_accounts as $asset_account)
                                        <option value="{{ $asset_account->id }}">{{ $asset_account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Purchase Description</th>
                                <td>
                                    <input type="text" class="form-control" name="purchase_description" id="purchase_description" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Fulfillment Type</th>
                                <td>
                                    <select name="fulfillment_type_id" id="fulfillment_type_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($fulfillment_types as $fulfillment_type)
                                        <option value="{{ $fulfillment_type->id }}">{{ $fulfillment_type->fulfillment_method }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> U/M</th>
                                <td>
                                    <select name="uoms_id" id="uoms_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($uoms as $uom)
                                        <option value="{{ $uom->id }}">{{ $uom->uom_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> U/M Set</th>
                                <td>
                                    <select name="uoms_set_id" id="uoms_set_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($uom_sets as $uom_set)
                                        <option value="{{ $uom_set->id }}">{{ $uom_set->uom_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Currency</th>
                                <td>
                                    <select name="currencies_id" id="currencies_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->currency_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Supplier Cost</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="purchase_price" id="purchase_price">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Sales Price</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="ttp" id="ttp">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Commi Margin</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="ttp_percentage" id="ttp_percentage">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Landed Cost</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="landed_cost" id="landed_cost">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Preferred Vendor</th>
                                <td>
                                    <select name="suppliers_id" id="suppliers_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->last_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Reorder Pt (Min)</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="reorder_pt" id="reorder_pt">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Group</th>
                                <td>
                                    <select name="groups_id" id="groups_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Category Description</th>
                                <td>
                                    <select name="categories_id" id="categories_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Subcategory Description</th>
                                <td>
                                    <select name="subcategories_id" id="subcategories_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->subcategory_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Dimension</th>
                                <td>
                                    <input type="text" class="form-control" name="packaging_dimension" id="packaging_dimension">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Packaging Size</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="packaging_size" id="packaging_size">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Supplier Item Code</th>
                                <td>
                                    <input type="text" class="form-control" name="supplier_item_code" id="supplier_item_code">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> MOQ Store</th>
                                <td>
                                    <input type="number" step="any" class="form-control" name="moq_store" id="moq_store">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h3 class="text-center text-bold">SEGMENTATIONS</h3>
                    <table class="table table-reponsive">
                        <tbody>
                            <tr>
                                <th><span class="required-star">*</span> Segmentation (Core)</th>
                                <td>
                                    <select class="segmentation_select" name="segmentation_core[]" id="segmentation_core" class="form-control" multiple="multiple" required>
                                        @foreach ($segmentations as $segmentation)
                                        <option class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Segmentation (Depletion)</th>
                                <td>
                                    <select class="segmentation_select" name="segmentation_depletion[]" id="segmentation_depletion" class="form-control" multiple="multiple" required>
                                        @foreach ($segmentations as $segmentation)
                                        <option class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Segmentation (Non Core)</th>
                                <td>
                                    <select class="segmentation_select" name="segmentation_non_core[]" id="segmentation_non_core" class="form-control" multiple="multiple" required>
                                        @foreach ($segmentations as $segmentation)
                                        <option class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Segmentation (Perishable)</th>
                                <td>
                                    <select class="segmentation_select" name="segmentation_perishable[]" id="segmentation_perishable" class="form-control" multiple="multiple" required>
                                        @foreach ($segmentations as $segmentation)
                                        <option class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Segmentation (Alternative)</th>
                                <td>
                                    <select class="segmentation_select" name="segmentation_alternative[]" id="segmentation_alternative" class="form-control" multiple="multiple" required>
                                        @foreach ($segmentations as $segmentation)
                                        <option class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right _action="save" id="save-btn"><i class="fa fa-save"></i> Save</button>
    </div>
</div>


<script type="application/javascript">
    const allSubcategories = {!! json_encode($subcategories) !!};
    $(`
        #brands_id,
        #tax_codes_id,
        #accounts_id,
        #cogs_accounts_id,
        #assets_accounts_id,
        #fulfillment_type_id,
        #uoms_id,
        #uoms_set_id,
        #currencies_id,
        #suppliers_id,
        #groups_id,
        #categories_id,
        #subcategories_id,
        #segmentation_core,
        #segmentation_depletion,
        #segmentation_non_core,
        #segmentation_perishable,
        #segmentation_alternative
    `).select2({
        width: '100%',
        height: '100%',
        placeholder: 'None selected...'
    });

    $('#categories_id').change(function() {
        const categoriesId = $('#categories_id').val();
        const subcategoriesElement = $('#subcategories_id');
        const subcategories = allSubcategories.filter(e => e.categories_id == categoriesId);
        $('#subcategories_id').html('');
        const firstOption = $(document.createElement('option'))
            .attr({
                selected: true,
                disabled: true
            })
            .text('None selected...');
        subcategoriesElement.append(firstOption);
        subcategories.forEach(e => {
            const option = $(document.createElement('option'))
            .attr({
                value: e.id
            })
            .text(e.subcategory_description);
            subcategoriesElement.append(option);
        });
    });

    $('.segmentation_select').on('change', function() {});

    $('.segmentation_select').on("select2:select", function (event) {
        const element = event.params.data.element;
        const $element = $(element);
        const className = $element.prop('class');
        const otherOptions = $(`.segmentation_select option.${className}`).not($element).attr('disabled', true);
    });

    $('.segmentation_select').on("select2:unselect", function(event) {
        const element = event.params.data.element;
        const $element = $(element);
        const className = $element.prop('class');
        const otherOptions = $(`.segmentation_select option.${className}`).attr('disabled', false);
    });

    // $(document).on('input', '.full_item_description', function() {
    //     console.log($(this).val());
    // })
</script>
@endsection