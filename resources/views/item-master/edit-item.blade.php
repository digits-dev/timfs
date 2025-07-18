@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>
    table, tbody, td, th {
        border: 1px solid black !important;
        padding-left: 50px;
    }

    th {
        width: 35%;
    }

    .photo-section {
        max-width: 400px;
        margin: 0 auto; 
    }

    .photo-section img {
        max-width: 100%;
        height: auto;
        display: block;
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
        <h3 class="text-center text-bold">Item Masterfile</h3>
    </div>
    <div class="panel-body">
        <form id="main-form" action="{{ $table == 'item_master_approvals' ? route('item_mater_approvals_submit_edit') : route('item_maters_submit_add_or_edit') }}" enctype="multipart/form-data" method="POST" class="form-main" autocomplete="off">
            <h3 class="text-center text-bold">ITEM DETAILS</h3>
            @csrf
            <input value="{{ $item->tasteless_code }}" name="tasteless_code" type="text" class="tasteless_code hide">
            @if ($item_masters_approvals_id)
            <input type="text" name="item_masters_approvals_id" value="{{ $item_masters_approvals_id }}" class="hide">
            @endif
            <div class="row">
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            @if ($item->tasteless_code)
                            <tr>
                                <th>Tasteless Code</th>
                                <td><input value="{{ $item->tasteless_code}}" type="text" name="tasteless_code" id="tasteless_code" class="form-control" readonly></td>
                            </tr>
                            @endif
                            <tr>
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input value="{{ $item->full_item_description ?: '' }}" type="text" name="full_item_description" id="full_item_description" class="form-control" required oninput="this.value = this.value.toUpperCase()"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">{{ $item->tasteless_code && !$item->image_filename ? '*' : '' }}</span> Display Photo</th>
                                <td><input type="file" name="item_photo" id="item_photo" accept="image/*" class="form-control" max="2000000" {{ $item->tasteless_code && !$item->image_filename ? 'required' : '' }} ></td>
                            </tr>
                            <tr>
                                <th>File Reference Link</th>
                                <td><input type="text" value="{{ $item->file_link ?: '' }}" name="file_link" id="file_link" class="form-control"></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Brand Description</th>
                                <td>
                                    <select value="{{ $item->brands_id }}" name="brands_id" id="brands_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Tax Code</th>
                                <td>
                                    <select name="tax_codes_id" id="tax_codes_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($tax_codes as $tax_code)
                                        <option value="{{ $tax_code->id }}" {{ $tax_code->id == $item->tax_codes_id ? 'selected' : '' }}>{{ $tax_code->tax_description }}</option>
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
                                        <option value="{{ $account->id }}" {{ $account->id == $item->accounts_id ? 'selected' : '' }}>{{ $account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  COGS Account</th>
                                <td>
                                    <select name="cogs_accounts_id" id="cogs_accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($cogs_accounts as $cogs_account)
                                        <option value="{{ $cogs_account->id }}" {{ $cogs_account->id == $item->cogs_accounts_id ? 'selected' : '' }}>{{ $cogs_account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Asset Account</th>
                                <td>
                                    <select name="asset_accounts_id" id="asset_accounts_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($asset_accounts as $asset_account)
                                        <option value="{{ $asset_account->id }}" {{ $asset_account->id == $item->asset_accounts_id ? 'selected' : '' }}>{{ $asset_account->group_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Purchase Description</th>
                                <td>
                                    <input type="text" value="{{ $item->purchase_description }}" class="form-control" name="purchase_description" id="purchase_description" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Fulfillment Type</th>
                                <td>
                                    <select name="fulfillment_type_id" id="fulfillment_type_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($fulfillment_types as $fulfillment_type)
                                        <option value="{{ $fulfillment_type->id }}" {{ $fulfillment_type->id == $item->fulfillment_type_id ? 'selected' : '' }}>{{ $fulfillment_type->fulfillment_method }}</option>
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
                                        <option value="{{ $uom->id }}" {{ $uom->id == $item->uoms_id ? 'selected' : '' }}>{{ $uom->uom_description }}</option>
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
                                        <option value="{{ $uom_set->id }}" {{ $uom_set->id == $item->uoms_set_id ? 'selected' : '' }}>{{ $uom_set->uom_description }}</option>
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
                                        <option value="{{ $currency->id }}" {{ $currency->id == $item->currencies_id ? 'selected' : '' }}>{{ $currency->currency_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Supplier Cost</th>
                                <td>
                                    <input value="{{ $item->purchase_price }}" type="number" step="any" class="form-control" name="purchase_price" id="purchase_price" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Sales Price</th>
                                <td>
                                    <input value="{{ $item->ttp }}"  type="number" step="any" class="form-control" name="ttp" id="ttp" {{$item->tasteless_code ? 'readonly' : ''}} required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            @if ($item->tasteless_code)
                            <tr>
                                <th>Sales Price Change</th>
                                <td>
                                    <input value="{{ $item->ttp_price_change }}"  type="number" step="any" class="form-control" name="ttp_price_change" id="ttp_price_change">
                                </td>
                            </tr>
                            <tr>
                                <th>Sales Price Effective Date</th>
                                <td>
                                    <input value="{{ $item->ttp_price_effective_date }}"  type="date" step="any" class="form-control" name="ttp_price_effective_date" id="ttp_price_effective_date">
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th><span class="required-star">*</span> Commi Margin</th>
                                <td>
                                    <input value="{{ $item->ttp_percentage }}" type="number" step="any" class="form-control" name="ttp_percentage" id="ttp_percentage" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Landed Cost</th>
                                <td>
                                    <input value="{{ $item->landed_cost }}" type="number" step="any" class="form-control" name="landed_cost" id="landed_cost" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Preferred Vendor</th>
                                <td>
                                    <select name="suppliers_id" id="suppliers_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Reorder Pt (Min)</th>
                                <td>
                                    <input value="{{ $item->reorder_pt }}" type="number" step="any" class="form-control" name="reorder_pt" id="reorder_pt" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Group</th>
                                <td>
                                    <select name="groups_id" id="groups_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($groups as $group)
                                        <option value="{{ $group->id }}" {{ $group->id == $item->groups_id ? 'selected' : '' }}>{{ $group->group_description }}</option>
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
                                        <option value="{{ $category->id }}" {{ $category->id == $item->categories_id ? 'selected' : '' }}>{{ $category->category_description }}</option>
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
                                        <option value="{{ $subcategory->id }}" {{ $subcategory->id == $item->subcategories_id ? 'selected' : '' }}>{{ $subcategory->subcategory_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Specifications</th>
                                <td>
                                    <input value="{{ $item->packaging_dimension }}" type="text" class="form-control" name="packaging_dimension" id="packaging_dimension">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Packaging Size</th>
                                <td>
                                    <input value="{{ $item->packaging_size }}" type="number" step="any" class="form-control" name="packaging_size" id="packaging_size" required>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Packaging UOM</th>
                                <td>
                                    <select name="packagings_id" id="packagings_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($packagings as $packaging)
                                        <option value="{{ $packaging->id }}" {{ $packaging->id == $item->packagings_id ? 'selected' : '' }}>{{ $packaging->packaging_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Supplier Item Code</th>
                                <td>
                                    <input value="{{ $item->supplier_item_code }}" type="text" class="form-control" name="supplier_item_code" id="supplier_item_code">
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> MOQ Store</th>
                                <td>
                                    <input value="{{ $item->moq_store }}" type="number" step="any" class="form-control" name="moq_store" id="moq_store" required>
                                </td>
                            </tr>
                            @if ($item->tasteless_code)
                            <tr>
                                <th><span class="required-star">*</span> SKU Status</th>
                                <td>
                                    <select name="sku_statuses_id" id="sku_statuses_id" class="form-control" required>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($sku_statuses as $sku_status)
                                        <option value="{{ $sku_status->id }}" {{ $sku_status->id == $item->sku_statuses_id ? 'selected' : '' }}>{{ $sku_status->sku_status_description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3 class="text-center text-bold">SEGMENTATIONS</h3>
                    <input type="text" class="hide" name="segmentations" id="segmentations">
                    <table class="table table-reponsive">
                    @php $selected = [] @endphp
                    @foreach ($sku_legends as $sku_legend)
                        @php
                            $value = $sku_legend->sku_legend;
                            $id_name = str_replace(' ', '_', $value);
                            $id_name = strtolower($id_name);
                            $selected[$id_name] = [];
                            if ($item) {
                                foreach ($segmentations as $segmentation) {
                                    $column = $segmentation->segment_column_name;
                                    $item_value = $item->{$column};
                                    if ($item_value == $value) {
                                        $selected[$id_name][] = $column;
                                    }
                                }
                            }

                        @endphp
                        <tr>
                            <th>Segmentation ({{ $value }})</th>
                            <td>
                                <select class="segmentation_select" id="segmentation_{{ $id_name }}" _value="{{ $value }}" class="form-control" multiple="multiple" >
                                    @foreach ($segmentations as $segmentation)
                                    <option {{ in_array($segmentation->segment_column_name, $selected[$id_name]) ? 'selected' : '' }} class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
                @if ($item->image_filename)
                <div class="col-md-6">
                    <div class="photo-section">
                        <h3 class="text-center text-bold">DISPLAY PHOTO</h3>
                        <img src="{{ asset('/img/item-master/' . $item->image_filename) }}" alt="Item Photo">
                    </div>
                </div>
                @endif
            </div>
            <button id="sumit-form-btn" class="btn btn-primary hide" type="submit">submit</button>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right _action="save" id="save-btn"><i class="fa fa-save"></i> Save</button>
    </div>
</div>

<script type="application/javascript">
    const allSubcategories = {!! json_encode($subcategories) !!};
    const today = new Date();

    // Format the date in YYYY-MM-DD for the input's value attribute
    const todayFormatted = today.toISOString().split('T')[0];

    // Set the minimum attribute for the input element
    $('#ttp_price_effective_date').attr('min', todayFormatted);
    getAllBrands();
    getAllSuppliers();
    function setBrand(brands) {
        brands.forEach(brand => {
            const option = $(document.createElement('option'))
                .val(brand.id)
                .text(brand.brand_description);

            if ("{{ $item->brands_id }}" == brand.id) {
                option.attr('selected', true);
            }
            
            $('#brands_id').append(option);
        });

        $('#brands_id').trigger('change');
    }

    function checkLandedCost(){
        const supplierCost = parseFloat($('#purchase_price').val());
        const landedCost = parseFloat($('#landed_cost').val());
        return landedCost >= math.floor(supplierCost, 2);
    }

    // new logic --------------------------
    function checkCommiMargin(){
        const fulfillmentType = $('#fulfillment_type_id :selected').text();
        const CommiMargin = parseFloat($('#ttp_percentage').val());

        if (isNaN(CommiMargin) || CommiMargin < 0) {
            return false;
        }

        if (fulfillmentType == 'DELIVERY-COMMI') {
            return CommiMargin >= 0;
        }
        return true;
    }

    //old logic -----------------------------
    // function checkCommiMargin(){
    //     const fulfillmentType = $('#fulfillment_type_id :selected').text();
    //     const CommiMargin = parseFloat($('#ttp_percentage').val());
    //     if (fulfillmentType == 'DELIVERY-COMMI'){
    //         return CommiMargin >= 0.05;
    //     }
    //     return true;
    // }

    function setSupplier(suppliers) {
        suppliers.forEach(supplier => {
            const option = $(document.createElement('option'))
                .val(supplier.id)
                .text(supplier.last_name);

            if ("{{ $item->suppliers_id }}" == supplier.id) {
                option.attr('selected', true);
            }
            
            $('#suppliers_id').append(option);
        });

        $('#suppliers_id').trigger('change');
    }
    function getAllBrands() {
        $.ajax({
            url: "{{ route('getAjaxSubmaster', ['table' => 'brands']) }}",
            _token: "{{ csrf_token() }}",
            type: 'GET',
            success: function(response) {
                localStorage.setItem('brands', response);
                brands = JSON.parse(localStorage.getItem('brands'));
                setBrand(brands)
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function getAllSuppliers() {
        $.ajax({
                url: "{{ route('getAjaxSubmaster', ['table' => 'suppliers']) }}",
                _token: "{{ csrf_token() }}",
                type: 'GET',
                success: function(response) {
                    localStorage.setItem('suppliers', response);
                    suppliers = JSON.parse(localStorage.getItem('suppliers'));
                    setSupplier(suppliers);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        
    }

    
    function restrictDecimals(jqueryElement, number) {
        const [int, dec] = jqueryElement.val().split('.');
        number = parseInt(number);
        if (dec && dec.length > number) {
            const value = `${int}.${dec.slice(0,number)}`
            jqueryElement.val(value);
        }
    }

    function updateCommiMargin() {
        const salesPrice = parseFloat($('#ttp_price_change').val() || $('#ttp').val() || 0);
        const landedCost = parseFloat($('#landed_cost').val() || 0);
        if (!landedCost || !salesPrice) return;
        const commiMargin = math.round((salesPrice - landedCost) / salesPrice, 2);

        $('#ttp_percentage').val(commiMargin);
    }

    function disableSelected() {
        $('.segmentation_select option').each(function() {
            const selected = $(this).attr('selected');
            if (selected) {
                const className = $(this).prop('class');
                const otherOptions = $(`.segmentation_select option.${className}`).attr('disabled', true);
            }
        });
    }

    function getSelectedSegmentations() {
        const segmentation = {};
        $('.segmentation_select').each(function() {
            const valueName = $(this).attr('_value');
            segmentation[valueName] = [];
            const options = $(this).find('option');
            options.each(function() {
                const isSelected = $(this).attr('selected');
                const columnName = $(this).val();
                if (isSelected) segmentation[valueName].push(columnName);
            });
        });
        return segmentation;
    }

    $(`
        #brands_id,
        #tax_codes_id,
        #accounts_id,
        #cogs_accounts_id,
        #asset_accounts_id,
        #fulfillment_type_id,
        #uoms_id,
        #uoms_set_id,
        #currencies_id,
        #suppliers_id,
        #groups_id,
        #categories_id,
        #subcategories_id,
        #packagings_id,
        #sku_statuses_id
    `).select2({
        width: '100%',
        height: '100%',
        placeholder: 'None selected...'
    });

    $('.segmentation_select').select2({
        width: '100%',
        // placeholder: 'None selected...'
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


    $('.segmentation_select').on("select2:select", function (event) {
        const element = event.params.data.element;
        const $element = $(element);
        $element.attr('selected', true);
        const className = $element.prop('class');
        const otherOptions = $(`.segmentation_select option.${className}`).not($element).attr('disabled', true);
    });

    $('.segmentation_select').on("select2:unselect", function(event) {
        const element = event.params.data.element;
        const $element = $(element);
        $element.attr('selected', false);
        const className = $element.prop('class');
        const otherOptions = $(`.segmentation_select option.${className}`).attr('disabled', false);
    });

    // $('#tax_codes_id').on('change', function() {        
    //     $('#purchase_price').val("");
    //     $('#ttp').val("");
    //     $('#ttp_percentage').val("");
    //     $('#landed_cost').val("");
    //     $('#price').val("");
    // });

    $('#full_item_description').on('input', function() {
        const text = $(this).val();
        $('#purchase_description').val(text);
    });

    $('#ttp, #landed_cost').on('input', function() {
        restrictDecimals($(this), 2);
        updateCommiMargin();
    });

    $('#purchase_price').on('input', function() {
        restrictDecimals($(this), 5);
    });

    $('#save-btn').on('click', function() {
        const segmentations = getSelectedSegmentations();
        console.log(checkLandedCost(), checkCommiMargin());
        const isValid = checkLandedCost() && checkCommiMargin();
        $('#segmentations').val(JSON.stringify(segmentations));
        console.log(segmentations);
        console.log(isValid);
        if (isValid) {
            Swal.fire({
                title: 'Do you want to save this item?',
                html:  `Doing so will push this item to <span class="label label-info">ITEM FOR APPROVAL</span>.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#sumit-form-btn').click();
                }
            });
        } else{
            Swal.fire({
                icon: "error",
                title: "Invalid Input!",
                text: "Check landed cost, supplier cost value, and commi margin.",
            });
        }
    });

    $('form input').on('keyup keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return;
        }
    });

    $('#main-form').on('submit', function() {
        Swal.fire({
            title: 'Loading...',
            html: 'Please wait...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });
    });

    disableSelected();
</script>
@endsection