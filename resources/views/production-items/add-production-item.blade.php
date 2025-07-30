
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> 
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  


{{-- DOM STARTS HERE !!!! --}}
<style>
  
  .select2-container--default .select2-selection--single {
    height: 34px;
    border-radius: 0px !important; 
  }

  .add-sub-btn-pack {
    font-size: 14;
    height: 30px;
    width: 30px;
    border-radius: 50%;
    color: white;
    position: absolute;
    bottom: -15px;
    cursor: pointer;
    transition: 200ms;
    display: flex;
    justify-content: center;
    align-items: center;
}
 

.add-sub-btn-pack  {
    background-color: #367fa9;
    transform: translateY(-7px); 
    left: 10px;
}
.add-sub-btn-pack:hover  { 
      transform: translateY(-4px);
    /* rotate: 90deg; */
    transition: 200ms;
}
.add-sub-btn  {
    background-color: #367fa9;
    transform: translateY(-7px); 
    left: 10px;
}
.add-sub-btn:hover  { 
      transform: translateY(-4px);
    /* rotate: 90deg; */
    transition: 200ms;
}

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
 
    .card {
      border: 1px solid #ddd;
      border-radius: 4px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      padding: 15px;
      background: #fff;
    }
    .card img {
      max-width: 100%;
      border-radius: 4px 4px 0 0;
    }
    .card-body {
      padding: 10px 0;
    }
    .card-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .card-text {
      font-size: 14px;
      color: #555;
    }
 

  .select2-selection__choice {
        background-color: #3190c7 !important;
        border-color: #367fa9 !important;
        color: #fff !important;
    }
 
</style>
  </style>
</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-dollar"></i><strong> Production Item</strong>
        </div>
        @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let firstError =   @json($errors->first());
                if(firstError.indexOf("99999999.99")  >= 0)
                {
                    firstError = "Final Value(VATEX) and Final Value(VATINC) is too much, please check fields";
                }

                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: firstError,
                confirmButtonText: 'OK'
                });
            });
        </script>
        @endif 
            <form action="{{ $table == 'production_items' ? route('add-production-items-to-db') : route('approve_or_reject_production_items_push') }}" method="POST" id="ProductionItems"  enctype="multipart/form-data">
                @csrf
                 <input type="text" class="hide" id="action-selected" name="action">
                 <input type="text" class="hide" value="{{ $item->reference_number }}" id="production_item_reference_number" name="reference_number">
                <div class="panel-body">
                    <input name="id" value="{{$item->id}}" class="hide"/>
                    <div class="row">
                        <div class="col-md-12"> 
                            <h3 class="text-center text-bold">ITEM DETAILS</h3> 
                            <input value="{{ $item->tasteless_code }}" name="tasteless_code" type="text" class="tasteless_code hide"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table-responsive table">
                                        <tbody>
                                            @if ($item->reference_number)
                                            <tr>
                                                <th>Tasteless Code</th>
                                                <td>
                                                    <input value="{{ $item->reference_number}}" type="text" name="reference_number" id="reference_number" class="form-control" readonly>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th><span class="required-star">*</span> Item Description</th>
                                                <td>
                                                    <input value="{{ $item->full_item_description ?: '' }}" type="text" name="full_item_description" id="full_item_description" class="form-control" required oninput="this.value = this.value.toUpperCase()">
                                                </td>
                                            </tr>
                                            @if($table == 'production_items') 
                                             <tr>
                                                <th><span class="required-star">*</span> Display Photo</th>
                                                <td>
                                                    <input type="file" name="item_photo" id="item_photo" accept="image/*" class="form-control">
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>File Reference Link</th>
                                                <td>
                                                    <input type="text" value="{{ $item->file_link ?: '' }}" name="file_link" id="file_link" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class="required-star">*</span>  Brand Description</th>
                                                <td>
                                                    <select class="form-control" style="width: 100%;" id="brands_id" name="brands_id" required>
                                                         <option value="{{$item->brands_id}}" selected>{{ $item->brand_description }}</option>
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
                                                    <input value="{{ $item->purchase_price }}" type="number" step="any" class="form-control" name="purchase_price" id="purchase_price" required readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class="required-star">*</span> Sales Price</th>
                                                <td>
                                                    <input value="{{ $item->ttp }}"  type="number" step="any" class="form-control" name="ttp" id="ttp" {{$item->ttp ? 'readonly' : ''}} required readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                <div class="col-md-12">
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
                                                        <select class="segmentation_select" id="segmentation_{{ $id_name }}" _value="{{ $value }}" class="form-control" multiple="multiple">
                                                            @foreach ($segmentations as $segmentation)
                                                                <option {{ in_array($segmentation->segment_column_name, $selected[$id_name]) ? 'selected' : '' }} class="{{ $segmentation->segment_column_name }}" value="{{ $segmentation->segment_column_name }}">{{ $segmentation->segment_column_description }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                    </table>
                                </div> 
                                  
                            </div>
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
                                                    <input value="{{ $item->landed_cost }}" type="number" step="any" class="form-control" name="landed_cost" id="landed_cost" required readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class="required-star">*</span> Preferred Vendor</th>
                                                <td>
                                                    <select class="form-control" style="width: 100%;" id="suppliers_id" name="suppliers_id" required>
                                                         <option value="{{$item->suppliers_id}}" selected>{{ $item->last_name }}</option>
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
                                @if ($item->image_filename)
                                    <div class="col-md-6">
                                        <div class="photo-section">
                                            <h3 class="text-center text-bold">DISPLAY PHOTO</h3>
                                            <img src="{{ asset('/img/production-items/' . $item->image_filename) }}" alt="Item Photo"  style="width: 1000px; height: 500px;">
                                        </div>
                                    </div>
                                @endif
                            </div> 
                            <h3 class="text-center text-bold">Production Item Lines</h3>
                            <br>
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="card-title text-center" style="position: relative; top: -25px;"><b>Ingredients</b></h3>
                                    </div>
                                </div>
                                <br>
                                <div class="ingredient-box" style="margin-bottom: 5px; position: relative; top: -20px;">
                                    <div class="ingredient-table w-100" style="width: 100%;">
                                        <div id="ingredient-tbody" name="ingredient-added">
                                            <!-- Rows injected by JS -->
                                        </div>
                                    </div>
                                    <div class="no-data-available-ingredient text-center py-2" style="display: none;">
                                        <i style="font-style: italic; color: #6c757d;" class="fa fa-table"></i>
                                        <span style="font-style: italic; color: #6c757d;">No ingredients currently save</span>
                                    </div>
                                </div>

                                <a class="btn btn-success" id="add-Row-ingredient">
                                    <i class="fa fa-leaf" aria-hidden="true"></i> Add New Ingredient
                                </a>
                            </div>

                            <div class="card">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="card-title text-center" style="position: relative; top: -25px;"><b>Packaging</b></h3>
                                    </div>
                                </div>
                                <br>
                                <div class="package-box" style="margin-bottom: 5px; position: relative; top: -20px;">
                                    <div class="package-table w-100" style="width: 100%;">
                                        <div id="package-tbody" name="package-added">
                                            <!-- Rows injected by JS -->
                                        </div>
                                    </div>
                                    <div class="no-data-available text-center py-2" style="display: none;">
                                        <i style="font-style: italic; color: #6c757d;" class="fa fa-table"></i>
                                        <span style="font-style: italic; color: #6c757d;">No Packaging currently save</span>
                                    </div>
                                </div>
                                <a class="btn btn-primary" id="add-Row">
                                    <i class="fa fa-cube" aria-hidden="true"></i> Add New Packaging
                                </a>
                            </div>

                            <div class="row"></div>

                            <div class="ingredient-box" style="margin-bottom: 5px">
                                <div class="labor-table w-100" style="width: 100%;">
                                    <div id="labor-tbody" name="ingredient-added">
                                        <div class="card" id="labor-entry">
                                            <div class="col-md-12">
                                                <div class="box-header text-center">
                                                    <h3 class="box-title"><b>Labor</b></h3>
                                                </div>
                                            </div>

                                            <div class="labor-entry col-md-12" isExisting="true">
                                                <div class="labor-inputs">
                                                  <label class="labor-label">
                                                <span class="required-star">*</span> Labor Cost per Minute
                                                <span class="item-from label"></span> <span class="label label-danger"></span>
                                                <div>
                                                    <input  
                                                        type="number" 
                                                        step="0.01"
                                                        id="labor_cost_per_minute" 
                                                        name="labor_cost_per_minute"
                                                        class="form-control display-labor span-2" 
                                                        value="{{round($item->labor_cost_per_minute)}}"
                                                        placeholder="eg 120" 
                                                        required
                                                     />
                                                </div>
                                            </label>
                                            <label>
                                                <span class="required-star">*</span> Total Minutes per Pack
                                                <input  
                                                    id="total_minutes_per_pack" 
                                                    name="total_minutes_per_pack"
                                                    value="{{round($item->total_minutes_per_pack)}}"
                                                    class="form-control costparent cost" 
                                                    type="text" 
                                                    readonly 
                                                    required
                                                >
                                            </label>
                                            <label>
                                                <span class="required-star">*</span> Total Labor Cost
                                                <input  
                                                    id="labor_cost_val" 
                                                    name="labor_cost_val"
                                                    class="form-control costparent cost" 
                                                    value="{{round($item->labor_cost_val)}}"
                                                    type="text" 
                                                    readonly 
                                                    required
                                                >
                                            </label> 
                                                </div>
                                                <br>
                                            </div>
                                            <br>
                                            <div class="sub-labor sub-elements">
                                                <br>
                                                <div class="no-data-available-labor text-center py-2">
                                                    <i style="font-style: italic; color: #6c757d;" class="fa fa-table"></i>
                                                    <span style="font-style: italic; color: #6c757d;">No Labor currently save</span>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <a class="btn btn-primary add-sub-btn-labor">
                                                <i class="glyphicon glyphicon-briefcase"></i> Add New Labor
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>

                            <h3 class="text-center text-bold">Cost Summary</h3>

                            <div class="row"> 
                                <div class="col-lg-4 col-md-12 mb-3">   
                                     <h5 class="text-center font-weight-bold">Comments</h5>  
                                 <div class="panel panel-default" style="border:1px solid #ddd; border-radius:5px; box-shadow:0 2px 5px rgba(0,0,0,0.05); font-family:Arial, sans-serif;">
                                <div class="panel-heading" style="background-color:#f8f8f8; padding:10px 15px; border-bottom:1px solid #ddd;">
                                    <h3 class="panel-title" style="margin:0; font-size:16px; display:flex; align-items:center;">
                                        <span class="glyphicon glyphicon-comment" style="margin-right:8px;"></span>
                                        Recent Comments
                                    </h3>
                                </div>
                                <div class="panel-body" style="padding:15px;">
                                    <ul class="media-list" style="list-style:none; padding:0; margin:0;">
                                        <div class="coment-bottom bg-white p-3 px-4" style="margin-bottom:10px;">
                                            <div class="d-flex align-items-center add-comment-section mt-4 mb-4" style="display:flex; gap:12px; align-items:center;">
                                                <img src="http://timfs.test/img/production-items/2025-07-24-new_item-uWd1EAjSUs.png" alt="User Avatar" width="38" height="38" style="border-radius:50%; object-fit:cover;" />
                                                <input type="text" class="form-control mr-3 flex-grow-1" placeholder="Add comment" id="add_comment_field" 
                                                    style="flex-grow:1; padding:8px 12px; border:1px solid #ccc; border-radius:4px; font-size:14px;" />
                                                <button class="btn btn-primary d-flex align-items-center px-4 py-2 add-comment-btn" type="button"
                                                    style="background-color:#007bff; color:#fff; border:none; border-radius:4px; padding:8px 16px; cursor:pointer; display:flex; align-items:center; font-size:14px;">
                                                    <i class="glyphicon glyphicon-send mr-2" style="margin-right:6px;"></i> Comment
                                                </button>
                                            </div>
                                            <hr style="border:none; border-top:1px solid #eee; margin:10px 0;">
                                        </div>

                                        <div class="comment-section" style="height: 515px; max-height: 515px; overflow-y: auto; padding-right:10px;">
                                            <!-- Sample Comment -->
                                          
                                            <!-- Additional comment blocks go here -->
                                        </div>
                                    </ul>
                                </div>
                            </div>

                                </div>
                                <!-- ========== 1. COST COMPONENTS (Fixed Inputs) =============================== -->
                                <div class="col-lg-4 col-md-12 mb-3">
                                    <h5 class="text-center font-weight-bold">Cost Break Down</h5>
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Values</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <tr class="hide">
                                                <td>Packaging Cost</td>
                                                <td>
                                                    <input type="number" step="any" name="packaging_cost" id="packaging_cost" value="{{ $item->packaging_cost }}" class="form-control text-right">
                                                </td>
                                            </tr>
                                            <tr class="hide">
                                                <td>Ingredient Cost</td>
                                                <td>
                                                    <input type="number"  step="any" name="ingredient_cost" id="ingredient_cost" value="{{ $item->ingredient_cost }}" class="form-control text-right">
                                                </td>
                                            </tr>
                                             <tr>
                                                <td>Food Cost</td>
                                                <td>
                                                    <input type="number"  step="any" name="food_cost" id="food_cost" value="" class="form-control text-right">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Labor Cost</td>
                                                <td>
                                                    <input type="number" step="any" name="labor_cost" id="labor_cost" value="{{ $item->labor_cost }}" class="form-control text-right" readonly required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Gas Cost (%)</td>
                                                <td>
                                                    <input type="number"  step="0.01" name="gas_cost" id="gas_cost" value="{{ $item->gas_cost }}" class="form-control text-right">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Storage Cost (%)</td>
                                                <td>
                                                    <input type="number" name="storage_cost" id="storage_cost" value="{{ $item->storage_cost }}" class="form-control text-right">
                                                </td>
                                            </tr>
                                             <tr>
                                                <td> Utilities (%)</td>
                                                <td>
                                                    <input type="number" name="utilities" id="utilities" value="{{ $item->utilities }}" class="form-control text-right">
                                                </td>
                                            </tr>
                                              <tr>
                                                <td> Opex</td>
                                                <td>
                                                    <input type="number" name="opex" id="opex" value="{{ $item->opex }}" class="form-control text-right" readonly required>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- ========== 2. PERCENTAGES / MULTIPLIERS =================================== -->
                                <div class="col-lg-4 col-md-12 mb-3">
                                    <h5 class="text-center font-weight-bold">Transfer Price</h5>
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Values</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Transfer Price Category </td>
                                                <td>
                                                    <select name="transfer_price_category" id="transfer_price_category" class="form-control select2">
                                                         <option value="">Select Category</option>
                                                        @foreach ($transfer_price_category as $tcat)
                                                        <option value="{{ $tcat->id }}"  data-markup="{{ $tcat->transfer_price_category_markup }}" {{ $item->transfer_price_category == $tcat->id ? 'selected' : '' }}>
                                                            {{ $tcat->transfer_price_category_description }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Markup %</td>
                                                <td> 
                                                    <input type="number" name="markup_percentage" id="markup_percentage" value="{{ $item->markup_percentage }}" class="form-control text-right" readonly required>
                                                </td>
                                            </tr> 
                                           
                                        </tbody>
                                    </table>


                                    <h5 class="text-center font-weight-bold">Category & Location</h5>
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Values</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Production Category</td>
                                                <td>
                                                    <select name="production_category" id="production_category" class="form-control">
                                                        <option value="">Select Category</option>
                                                        @foreach ($production_category as $cat)
                                                            <option value="{{ $cat->id }}" {{ old('production_category', $item->production_category) == $cat->id ? 'selected' : '' }}>
                                                                {{ $cat->category_description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Production Location</td>
                                                <td>
                                                    <select name="production_location" id="production_location" class="form-control">
                                                        <option value="">Select Location</option>
                                                        @foreach ($production_location as $loc)
                                                            <option value="{{ $loc->id }}" {{ old('production_location', $item->production_location) == $loc->id ? 'selected' : '' }}>
                                                                {{ $loc->production_location_description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Storage Location</td>
                                                <td>
                                                    <select name="storage_location" id="storage_location" class="form-control">
                                                        <option value="">Select Location</option>
                                                        @foreach ($storage_location as $loc)
                                                            <option value="{{ $loc->id }}" {{ old('storage_location', $item->storage_location) == $loc->id ? 'selected' : '' }}>
                                                                {{ $loc->storage_location_description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- ========== 3. CATEGORIZATION / LOCATIONS ================================== -->
                                <div class="col-lg-4 col-md-12 mb-3">
                                    <h5 class="text-center font-weight-bold">Final Values</h5>
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Values</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>LC + SP</td>
                                                <td> 
                                                    <input type="text" value="" class="form-control rounded" name="lc_sp" id="lc_sp" placeholder="Landed Cost + Supplier" aria-describedby="basic-addon1" readonly /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>FC + PM + Opex</td>
                                                <td>  
                                                    <input type="text" value="" class="form-control rounded" name="fc_pm_opex" id="fc_pm_opex" placeholder="Food Cost + Packaging Materials + Opex" aria-describedby="basic-addon1" readonly />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Final Value(VATEX)</td>
                                                <td> 
                                                    <input type="text" value="{{$item->final_value_vatex}}" class="form-control rounded" name="final_value_vatex" id="final_value_vatex" placeholder="Final value vatex" aria-describedby="basic-addon1" readonly />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Final Value(VATINC)</td>
                                                <td>
                                                    <input type="text" value="{{$item->final_value_vatinc}}" class="form-control rounded" name="final_value_vatinc" id="final_value_vatinc" placeholder="Fina value vatinc" aria-describedby="basic-addon1" readonly />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 
                    </div>
                     <div >
        
            <!-- Fluid width widget -->    
                <button type="submit" id="sumit-form-button" class="btn btn-success  hide">+ Save data</button>
            </form>

         
             <div class="panel-footer">

                @if($table != 'production_items_approvals') 
                    @if($item->id != '')
                        <button type="button" id="save-datas" class="btn btn-success">+ Update data</button>
                    @else
                        <button type="button" id="save-datas" class="btn btn-success">+ Create data</button>
                @endif
                    <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-link'>← Back</a>
                @else
                    @if(!$view) 
                    <div class="panel-footer"> 
                        <button type="button" _action="approve" class="btn btn-success action-btn" id="approve-btn"><i class="fa fa-thumbs-up"></i> Approve</button>
                        <button type="button" _action="reject" class="btn btn-danger action-btn" id="reject-btn" style="margin-right: 10px;"><i class="fa fa-thumbs-down"></i> Reject</button>
                        <a href='{{ CRUDBooster::mainpath() }}' id="cancel-btn" class='btn btn-default'>Cancel</a>
                    </div>
                    @endif
                 @endif
            </div>
 
    </div>

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
 
    $(document).ready(function() {
        
       
        

        //for showing message no package or ingredients found 
        is_noingredient = false;
        is_SendingComment = false;
        //for adding table row assigning unique ids
        let tableRow = 0;
        let commentId = Number("{{$comment_id}}") || 0;
        let reference_number = "{{$item->reference_number}}"; 
        //check if need to disable fields
        let disableifapproval = "{{$table}}"; 
        let view_ = "{{$view}}"; 
        //loading ingredient and packaging data from contoller
        Load_Production_Item_Lines();
 

          $(`  
            #tax_codes_id,
            #accounts_id,
            #cogs_accounts_id,
            #asset_accounts_id,
            #fulfillment_type_id,
            #uoms_id,
            #uoms_set_id,
            #currencies_id, 
            #groups_id,
            #categories_id,
            #subcategories_id,
            #packagings_id,
            #sku_statuses_id, 
            #production_category,
            #transfer_price_category,
            #production_location,
            #storage_location
            `).select2({
                width: '100%',
                height: '100%',
                placeholder: 'None selected...'
            });
        
        $('#transfer_price_category').select2({
            width: '380px'  // makes Select2 respect the select's width/max-width
        });

        
        $('.segmentation_select').select2({
            width: '100%',
            // placeholder: 'None selected...'
        });

        $('body').addClass('sidebar-collapse');
        $(`.select`).select2({
            width: '100%',
            height: '100%' 
        }); 
            console.log(commentId);
        $('.add-comment-btn').click(function(){
            if(is_SendingComment == false)
            {
                console.log(commentId);
                console.log('add comemnt'); 
                let comment = $(`#add_comment_field`).val();
                if(comment)
                {   
                    commentId++; 
                    submitComment('comment', '.comment-section', reference_number, comment, commentId, '', "{{CRUDBooster::myName()}}");
                } 
            }
        })

        $(document).on('click', '[id*="send-comment-btn"]', function() { 
            if(is_SendingComment == false)
            {
                let id = $(this).attr('id').replace(/\D/g, '');  
                let reply = $(`#Textarea${id}`).val();
                console.log(id);
                if(reply)
                {  
                    commentId++; 
                    let parentId = $(this).closest('.collapse').closest('.post-footer-option').prev('.comment-sub').attr('id'); 
                    submitComment('comment_reply', `.comment-reply${id}`,  reference_number, reply, commentId, parentId, "{{CRUDBooster::myName()}}");  
                }
            }
           

        });
 
        $(window).on('load', function() {
           ScrollToBottom('.comment-section');
        });
        
        function ScrollToBottom(section)
        {
             var $commentSection = $(section);
            if ($commentSection.length) {
                $commentSection.scrollTop($commentSection.prop("scrollHeight"));
            }
        }

        $("#add-Row").click(function () { 
            tableRow++;
            console.log( tableRow + " dd-Row");
            const newRowHtml = generateRowHtml(tableRow, "", "", "", "","","", "", "", "");
             $(newRowHtml).appendTo('#package-tbody');
            PackagingSearchInit(`#itemDesc${tableRow}`, tableRow); 
            showNoData();
                showNoDataIngredient();
                 format_decimal()
        });

         $("#add-Row-ingredient").click(function () { 
            tableRow++;
            console.log( tableRow + " dd-Row");
            const newRowHtml = generateRowingredientHtml(tableRow, "", "", "", "", "", "", "", "", "", "");
            $(newRowHtml).appendTo('#ingredient-tbody'); 
            IngredientSearch(`#itemDesc${tableRow}`, tableRow); 
            showNoData();
                showNoDataIngredient();
                 format_decimal()
        });
        
 
        $(document).on('click', '.add-sub-btn', function(event) {
            tableRow++;
            const parentId = $(this).parent().attr('id').split("ingredient-entry")[1];   
            const newRowPackHtml = Sub_gen_ingredient_row(tableRow, "", "", "", "", "", "", "", "", "", "");   
            $(newRowPackHtml).appendTo(`.sub-ingredient${parentId}`);
            console.log(parentId);
            IngredientSearch(`#itemDesc${tableRow}`, tableRow); 
             format_decimal()
        });


        $(document).on('click', '.add-sub-btn-pack', function(event) {
            tableRow++;
            const parentId = $(this).parent().attr('id').split("packaging-entry")[1];  
            const newRowPackHtml = Sub_gen_pack_row(tableRow, "", "", "", "", "","", "", "", "");  
            $(newRowPackHtml).appendTo(`.sub-pack${parentId}`);
            console.log(parentId);
            PackagingSearchInit(`#itemDesc${tableRow}`, tableRow); 
             format_decimal()
        });

        $(document).on('click', '.add-sub-btn-labor', function(event) {
            tableRow++; 
            const newRowPackHtml = Sub_gen_Labor_row(tableRow,"","","","");  
            $(newRowPackHtml).appendTo(`.sub-labor`); 
            showNoDataLabor();
             format_decimal()
        });

        ajax_add(); 

        function ajax_add()
        {
            Brandsearch('#brands_id','brands', 'status','INACTIVE', 'brand_description');
            Brandsearch('#suppliers_id', 'suppliers', 'last_name', null, 'last_name');


            function Brandsearch(select, target, status, status_value, description) {  

                $(select).select2({
                    placeholder: 'None selected...', 
                ajax: {
                    url: `/admin/production_items/${target}/${status}/${status_value}/${description}`,
                    type: 'GET',
                    dataType: 'json',
                
                    delay: 250, 
                    data: params => ({
                        search: params.term                 
                    }),
                    processResults: function (data, params) {
                        const mapped = data.items.map(item => ({
                            id:   item.id,
                            text: item.description, 
                            ...item
                        }));
                        return {
                            results: mapped, 
                        };
                    },
                        cache: true
                    }   
                }); 
               
            }


        } 

         //for packaging search items
        function PackagingSearchInit(selector, rowId) {
           const token = $("#token").val();   
            console.log(rowId + 'pogi');
                $(`#itemDesc${rowId}`  ).on('input', function() {
                $(`#quantity${rowId}`).val('');
                $(`#cost${rowId}`).val('');
                $(`#default_cost${rowId}`).val(''); 
                $(`#tasteless_code${rowId}`).val('');
                $(`#tasteless_code_original${rowId}`).val(''); 
                 $(`#pack-size${rowId}`).val(''); 
            });
            
            $(selector).autocomplete({
                source: function (request, response) {
                $.ajax({
                    url: "{{ route('packag-search') }}",
                    type: "POST",
                    dataType: "json",
                    data: { 
                    "_token": token, 
                    "search": request.term, 
                    values: $('[id*="itemDesc"], [id*="tasteless_code"], [id*="tasteless_code_original"]').map(function() {
                                // if($(this).val() != "") {
                                //     return $(this).val();
                                // } else {
                                    return 'null';
                                //}
                            }).get()
                    },
                    success: function (data) {
                        console.log(data);
                        if (data.status_no == 1) {
                            $(`#ui-id-2${rowId}`).hide();
                            response($.map(data.items, item => ({
                               label: `<div>${
                                            item.from_db == 1 ? '<strong style="font-size: 12px; background-color: #28a745; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;">NEW</strong>' : '<strong style="font-size: 12px; background-color: #17a2b8; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;">IMFS</strong>'
                                        }${item.item_description}</div>`,
                                value: item.item_description,

                                ...item
                            })));
                        } else {
                            $('.ui-menu-item').remove();
                            $('.addedLi').remove();
                            const $ui = $(`#ui-id-2${rowId}`).html(`<i class="fa fa-exclamation fa-bounce "></i> ${data.message}`);
                            $ui.toggle($('#itemDesc' + rowId).val().length > 0);
                        }
                    }
                });
            },
                select: function (event, ui) {
                  const curid = $(this).attr("id"); 
                  var id = curid.split("itemDesc")[1];
                    console.log(id);
                    $(`#tasteless_code${id}`).val(ui.item.tasteless_code).trigger('change');
                    $(`#tasteless_code_original${id}`).val(ui.item.tasteless_code).trigger('change');
                    $(`#itemDesc${id}`).val(ui.item.item_description); 
                    $(`#cost${id}`).val(Number(ui.item.cost).toFixed(2)).attr('readonly', true); 
                    $(`#default_cost${id}`).val(Number(ui.item.cost).toFixed(2)); 
                    $(`#pack-size${rowId}`).val(Number(ui.item.packaging_size).toFixed(2));
                    $(`#quantity${rowId}`).val('1').trigger('change');
                   
                    //packaging_size
                    
                    calculateFinalValues();
                    return false;
                },
                minLength: 1,
                autoFocus: true
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append(item.label)
                    .appendTo(ul);
            }; 
        }
    

        //for packaging ingredients items
        function IngredientSearch(selector, rowId) {
            const token = $("#token").val();   
            console.log(rowId + 'pogi');
                $(`#itemDesc${rowId}`  ).on('input', function() {
                $(`#quantity${rowId}`).val('');
                $(`#cost${rowId}`).val('');
                $(`#tasteless_code${rowId}`).val('');
                $(`#tasteless_code_original${rowId}`).val('');
                $(`#ttp${rowId}`).val('');
                $(`#pack-size${rowId}`).val('');  
                $(`#ingredient-qty${rowId}`).val('');  
            });
 
            $(selector).autocomplete({
                source: function (request, response) {
                $.ajax({
                    url: "{{ route('item-search') }}",
                    type: "POST",
                    dataType: "json",
                    data: { 
                    "_token": token, 
                    "search": request.term, 
                    values: $('[id*="itemDesc"], [id*="tasteless_code"], [id*="tasteless_code_original"]').map(function() {
                                // if($(this).val() != "") {
                                //     return $(this).val();
                                // } else {
                                    return 'null';
                                //}
                            }).get()
                    },
                    success: function (data) {
                            console.log(data);
                            if (data.status_no == 1) {
                                $(`#ui-id-2${rowId}`).hide();
                                response($.map(data.items, item => ({
                                label: `<div><strong style="font-size: 12px; background-color: #17a2b8; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;">IMFS</strong>${item.item_description}</div>`,
                                    value: item.item_description,
                                    ...item
                                })));
                            } else {
                                $('.ui-menu-item').remove();
                                $('.addedLi').remove();
                                const $ui = $(`#ui-id-2${rowId}`).html(`<i class="fa fa-exclamation fa-bounce "></i> ${data.message}`);
                                $ui.toggle($('#itemDesc' + rowId).val().length > 0);
                            }
                        }
                    });
                },
                select: function (event, ui) {
                  const curid = $(this).attr("id"); 
                  var id = curid.split("itemDesc")[1];
                        
                    $(`#tasteless_code${id}`).val(ui.item.tasteless_code).trigger('change');
                    $(`#tasteless_code_original${id}`).val(ui.item.tasteless_code).trigger('change');
                    $(`#itemDesc${id}`).val(ui.item.item_description); 
                    $(`#ttp${id}`).val(Number(ui.item.cost).toFixed(2)).attr('readonly', true);
                    $(`#pack-size${id}`).val(ui.item.packaging_size); 
                    $(`#quantity${rowId}`).val('1').trigger('change'); 
                    //packaging_size
                  

                    calculateFinalValues();
                    return false;
                },
                minLength: 1,
                autoFocus: true
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append(item.label)
                    .appendTo(ul);
            }; 
        }
  
        function validateFields() {
            let isValid = true;

            $(".itemDesc, .digits_code").each(function () {
                const val = $(this).val();
                if (!val) {
                    showError("Please fill all Fields!");
                    isValid = false;
                    return false; // break out of loop
                }
            });

            return isValid;
        }

        function showError(message) {
            swal({
                type: "error",
                title: message,
                icon: "error",
                confirmButtonColor: "#367fa9",
            });
        }

       
        //to save data and list to Production Items List module
        $('.action-btn').on('click', function() {
            const action = $(this).attr('_action'); 
            const segmentations =  getSelectedSegmentations();  
            $('#segmentations').val(JSON.stringify(segmentations));
            $('#action-selected').val(action);
            Swal.fire({
                title: `Do you want to ${action} this item?`,
                html:  action == 'approve' ? `🟢 Doing so will update the Production Item Masterfile.`
                    : `🔴 Doing so will turn the status of this item to <span class="label label-danger">REJECTED</span>.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#ProductionItems').find('input, select, textarea').prop('disabled', false);
                    $('#sumit-form-button').click();
                }
            });
        });
 
            

        $('#save-datas').on('click', function() {
            
                validateFields();
                const segmentations =  getSelectedSegmentations();
                const packagingRows = $('[id*="packaging-entry"]').length; 
                const ingredientRows = $('[id*="ingredient-entry"]').length;  
                $('#segmentations').val(JSON.stringify(segmentations));
                console.log(segmentations);
             if(packagingRows == 0 || ingredientRows == 0)
                {
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please ensure that both Ingredients and Packaging Production lines are not left empty.',
                    confirmButtonText: 'OK'
                    });
                } else
                {
                    //check if user if on update or add module
                    let itemId = "{{ $item->id }}";
                    if(itemId == "")
                    {
                        Swal.fire({
                        title: 'Do you want to save this production item?',
                        html:  `Doing this will create new <span class="label label-info">Production Item</span>.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Save',
                        returnFocus: false,
                        }).then((result) => {
                            
                                if (result.isConfirmed) {
                                    $('#sumit-form-button').click();
                                }
                            
                        });
                    }else
                    {
                        Swal.fire({
                        title: 'Do you want to update this production item?',
                        html:  `Doing this will update Production item reference number <span class="label label-info"> ${reference_number}</span>.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Save',
                        returnFocus: false,
                        }).then((result) => {
                        
                                if (result.isConfirmed) {
                                        $('#sumit-form-button').click(); 
                                }
                            
                            
                        });
                    }
                }
            
           
        });
 

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

         
        $('.segmentation_select').on("select2:unselect", function(event) {
            const element = event.params.data.element;
            const $element = $(element);
            $element.attr('selected', false);
            const className = $element.prop('class');
            const otherOptions = $(`.segmentation_select option.${className}`).attr('disabled', false);
        }); 

        $('.segmentation_select').on("select2:select", function (event) {
            const element = event.params.data.element;
            const $element = $(element);
            $element.attr('selected', true);
            const className = $element.prop('class');
            const otherOptions = $(`.segmentation_select option.${className}`).not($element).attr('disabled', true);
        });
 
          
         // Recalculate on any input change
        $(document).on('input change', '[id*="quantity"], [id*="yield"]', function() {
                  
                var id = $(this).attr('id');
                var lastChar = id.split("quantity")[1] || id.split("yield")[1];
                console.log(lastChar);
                const yieldInput = $(`#yield${lastChar}`).val() || 0;
                console.log(yieldInput);
                const container = $(this).closest('.packaging-wrapper').attr('id');
                
                if(container.includes('ingredient-entry'))
                {
                    if($(`#tasteless_code${lastChar}`).val() != '' && yieldInput  != '0')
                    {
                        
                        const yieldPercent = math.round(yieldInput / 100, 4); 
                        console.log($(`#yield${lastChar}`).val());

                        const uomQty = 1; 

                        let preperationQuantity = $(`#quantity${lastChar}`).val() || 0; 

                        let packagingSize = $(`#pack-size${lastChar}`).val() || 0; 

                        let cost = $(`#ttp${lastChar}`).val() || 0; 
                        
                        

                        let actual_pack_uom = yieldPercent * packagingSize;

                        let actual_ingredient_cost = (cost / packagingSize * actual_pack_uom) * preperationQuantity;

                       // const ingredientModifier = math.round(uomQty / packagingSize * preperationQuantity  / yieldPercent, 4);
                            
                        const ingredientCost = math.round(actual_ingredient_cost, 4); 
                        const ingredientQty = math.round(actual_pack_uom, 4);  


                        $(`#cost${lastChar}`).val(Number(ingredientCost).toFixed(2)).attr('readonly', true);
                        $(`#ingredient-qty${lastChar}`).val(Number(ingredientQty).toFixed(2)).attr('readonly', true); 
                    }  
                }
                else
                {
                    const packaging_cost =  $(`#default_cost${lastChar}`).val() || 0; 
                    let  quantity = $(`#quantity${lastChar}`).val() || 1;   
                    let packsize = $(`#pack-size${lastChar}`).val() || 1; 

                    let piece_cost = packaging_cost / packsize


                    total = piece_cost * quantity;

                    $(`#cost${lastChar}`).val(Number(total).toFixed(2)).attr('readonly', true);
                    
                } 
                calculateFinalValues();
            });

            //Calcualte labor lines fields on change/input new data
            $(document).on('input change', '[id*="yiel"], [id*="time-labor"], [id*="labor_cost_per_minute"]', function() {
                  
                var id = $(this).attr('id');
                var lastChar = id.split("time-labor")[1] || id.split("yiel")[1] || id.split("labor_cost_per_minute")[1];
                console.log(lastChar);
                let yieldInput = $(`#yiel${lastChar}`).val() / 100 || 0;
                console.log(yieldInput);

                let time = $(`#time-labor${lastChar}`).val() / 100 || 0; 
                let total = math.round(time / yieldInput, 2); 
                 $(`#pack-minute${lastChar}`).val(total);
                CalculateLabor();
            }); 
 
            function CalculateLabor()
            {
                let cost = 0; 
                
                $('[id*="pack-minute"]').each(function() { 
                    cost += parseFloat($(this).val()) || 0;  
                });  

                
               
                let labor_cost_per_minute = $('#labor_cost_per_minute').val() / 100;
                let total = math.round(cost * labor_cost_per_minute, 2); 
                $(`#total_minutes_per_pack`).val(math.round(cost, 2));
                $(`#labor_cost_val`).val(total);
                $(`#labor_cost`).val(total).trigger('input');
                
            }






        //set sub packaging/ingredients as primary  
       $(document).on('click', '[id*="set-primary"]', function() { 
            let total_sub = 0; 
            let id = $(this).attr('id');
            
            // counting each production item lines before this line for animation exact position 
            // "from line user desire to be set primary to parent production item line position"
            $(this).closest('.substitute-packaging').closest('.sub-elements').find('.substitute-packaging').each(function(){
                 total_sub++; 
                if($(this).find('.actions').find('.set-primary').attr('id') == id)
                { 
                    return false;  
                } 
            });  

            let  wrapperId = $(this).closest('.packaging-wrapper').find('.packaging-entry');
            let entry = $(this).closest('.substitute-packaging').closest('.sub-elements').find('.substitute-packaging').first(); 
 
            const parentId = wrapperId.closest('.packaging-wrapper').attr('id').replace(/\D/g, '');
            
            let parent_contents = {
                'tasteless_code': $(`#tasteless_code${parentId}`).val(),
                'itemDesc': $(`#itemDesc${parentId}`).val(),
                'quantity': $(`#quantity${parentId}`).val(),
                'preparations': $(`#preparations${parentId}`).val(),
                'yield': $(`#yield${parentId}`).val(),
                'ttp': $(`#ttp${parentId}`).val(),
                'ingredient-qty': $(`#ingredient-qty${parentId}`).val(),
                'qty-contribution': $(`#qty-contribution${parentId}`).val(),
                'pack-size': $(`#pack-size${parentId}`).val(),
                'cost': $(`#cost${parentId}`).val(),
                'costparent-contribution': $(`#costparent-contribution${parentId}`).val()
            };

            let sibling = $(this).closest('.substitute-packaging'); 
            sibling.css('position', 'relative');

            const sub_id = sibling.attr('id').replace(/\D/g, '');
            
             let sub_contents = {
                'tasteless_code': $(`#tasteless_code${sub_id}`).val(),
                'itemDesc': $(`#itemDesc${sub_id}`).val(),
                'quantity': $(`#quantity${sub_id}`).val(),
                'preparations': $(`#preparations${sub_id}`).val(),
                'yield': $(`#yield${sub_id}`).val(),
                'ttp': $(`#ttp${sub_id}`).val(),
                'ingredient-qty': $(`#ingredient-qty${sub_id}`).val(),
                'qty-contribution': $(`#qty-contribution${sub_id}`).val(),
                'pack-size': $(`#pack-size${sub_id}`).val(),
                'cost': $(`#cost${sub_id}`).val(),
                'costparent-contribution': $(`#costparent-contribution${sub_id}`).val()
            };

            entry.removeClass('animate__animated animate__bounceIn'); 
            
            const prevBr = entry;   

            sibling.animate(
                {
                    top: `-=${entry.outerHeight() * total_sub}`,
                },
                {
                    duration: 500,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');

                        Object.entries(sub_contents).forEach(function ([key, value]){
                            $(`#${key+parentId}`).val(value); 
                        }); 
                    }
                }
            );

            wrapperId.animate(
                {
                    top: `+=${sibling.outerHeight() * total_sub}`,
                },
                {
                    duration: 500,
                    queue: false,
                    done: function() {
                        $(wrapperId).css('top', '0');

                        Object.entries(parent_contents).forEach(function([key, value])
                        {
                            $(`#${key+sub_id}`).val(value); 
                        }); 
                        $(`#quantity${parentId}`).trigger('change');

                    }
                }
            );

             
        });
 
 
       

        // This logic updates the parent IDs of related sub-elements based on the closest 'tasteless_code' input within the same packaging-wrapper.
        // The main idea is: when the parent 'tasteless_code' input value changes, 
        // it dynamically updates the 'name' attributes of all associated sub-elements to keep their data grouped correctly under the new parent ID.
        $(document).on('input change', 'input[id^="tasteless_code"]', function() {
            const $wrapper = $(this).closest('.packaging-wrapper');

            const parentid = $wrapper.find('input[id^="tasteless_code"]').val();

            const parentid_todb = $wrapper.find('input[id^="tasteless_code"]').attr('id').replace(/\D/g, '');

            const oldParentId = $(this).data('oldParentId') || null;
 
            $(this).data('oldParentId', parentid);
 
            var idsub = $(this).attr('id');
            var lastCharsub = idsub.split("tasteless_code")[1]; 
            $(`#tasteless_code_original${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][tasteless_code]`); 
            
            $(`#itemDesc${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][description]`); 
            $(`#quantity${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][quantity]`);
            $(`#yield${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][yield]`); 
            $(`#ttp${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][ttp]`);
            $(`#cost${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][cost]`);
            $(`#DB_id${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][DB_id]`); 
            $(`#production_type${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][production_item_line_type]`);  
            $(`#preparations${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][preparations]`);
            if (parentid_todb !== oldParentId) {
                $wrapper.find('[class^="sub-ingredient"], [class^="sub-pack"]').find('input').each(function() {
                    var subId = $(this).attr('id') || '';
                    var subLastChar = subId.split("tasteless_code")[1] || '';
                    if (subLastChar) {
                        const ids =  subId.split("tasteless_code")[1] 
                        $(this).attr('name', `produtionlines[${parentid_todb}][${subLastChar}][tasteless_code]`); 
                         
                        $(`#itemDesc${ids}`).attr('name', `produtionlines[${parentid_todb}][${subLastChar}][description]`); 
                        $(`#quantity${ids}`).attr('name', `produtionlines[${parentid_todb}][${subLastChar}][quantity]`);
                        $(`#yield${ids}`).attr('name', `produtionlines[${parentid_todb}][${subLastChar}][yield]`);
                         $(`#ttp${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][ttp]`); 
                        $(`#cost${ids}`).attr('name', `produtionlines[${parentid_todb}][${subLastChar}][cost]`); 
                        $(`#DB_id${ids}`).attr('name', `produtionlines[${parentid_todb}][${subLastChar}][DB_id]`);
                        $(`#production_type${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][production_item_line_type]`);  
                         $(`#preparations${lastCharsub}`).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][preparations]`);
                    }
                });
            }
        });


        $('#transfer_price_category').on('change', function(){ 
        var markup = $(this).find('option:selected').data('markup');  // get data-markup
        $('#markup_percentage').val(Number(markup / 100).toFixed(2)).trigger('input');
            
        });
 
        $('#gas_cost, #ingredient_cost').on('change', function() {
                let gas =  $(this).val();
                let ingredient_cost =  $('#ingredient_cost').val(); 
                let total = gas * ingredient_cost;  
                $(this).val(Number(total).toFixed(2)).trigger('input');
        });
 
 
        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper');
            const chld = entry.find('.animate__bounceIn, .animate__rubberBand'); 


            entry.removeClass('animate__animated animate__bounceIn');
            chld.removeClass('animate__animated animate__bounceIn animate__rubberBand'); 

            const prevBr = entry.prevAll('br').first(); 
            let sibling = entry.prev();
            while (sibling.length && sibling.is('br')) {
                sibling = sibling.prev();
            } 
            $(sibling).animate(
                {
                    top: `+=${entry.outerHeight()}`,
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `-=${sibling.outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                      
                        if (prevBr.length) {
                            entry.insertAfter(prevBr);
                        } else {
                            entry.insertBefore(sibling);
                        }
                    }
                }
            );
        });

        $(document).on('click', '.move-down', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper');
            const chld = entry.find('.animate__bounceIn, .animate__rubberBand'); 


            entry.removeClass('animate__animated animate__bounceIn');
            chld.removeClass('animate__animated animate__bounceIn animate__rubberBand'); 

            const nextBr = entry.nextAll('br').first();

             
            let sibling = entry.next();
            while (sibling.length && sibling.is('br')) {
                sibling = sibling.next();
            }

            if (!sibling.length) return;

            $(sibling).animate(
                {
                    top: `-=${entry.outerHeight()}`,
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');
                    }
                }
            );

            entry.animate(
                {
                    top: `+=${sibling.outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                    
                        if (nextBr.length) {
                            entry.insertBefore(nextBr);
                        } else {
                            entry.insertAfter(sibling);
                        }
                    }
                }
            );
        });
        
         $(document).on('click', '.delete', function(event) {
             
            const entry = $(this).parents(
                '.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper'
            );
            entry.addClass('animate__animated animate__bounceOut').one('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function() {
                entry.prevAll('br').first().remove(); 
                $(this).remove();
                showNoData();
                showNoDataIngredient();
                calculateFinalValues();
            });
          
        }); 


        $(document).on('click', '.delete-sub', function(event) {
               
            const subEntry = $(this).parents(`
                .substitute-ingredient, 
                .new-substitute-ingredient, 
                .substitute-packaging, 
                .new-substitute-packaging
            `);
           subEntry.addClass('animate__animated animate__bounceOut').one('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function() {
                $(this).remove();
                showNoDataLabor();
                calculateFinalValues();
                CalculateLabor();
            });

           
        });
  

        $('#storage_cost, #storage_multiplier').on('input', calculateTotalStorage);

         // Calculate total storage cost
        function calculateTotalStorage() { 
            const storageCost = parseFloat($('#storage_cost').val()) || 0;
            const storageMultiplier = parseFloat($('#storage_multiplier').val()) || 0;
            const totalStorage = storageCost * storageMultiplier;
            //$('#total_storage_cost').val(totalStorage.toFixed(2));
        }


        



        // Calculate final values
        function calculateFinalValues() {
            console.log($('#tasteless_code_original1').val());
            let ingredientsCost = 0;
            let packagingsCost = 0;
            let total_prepquantity_ing = 0;
            let total_prepquantity_pac = 0;
            const rawMastProvision = 0; 

                $('[class*="packaging-wrapper"]').each(function() {
                 
                var id = $(this).attr('id');
                var lastChar = id.split("ingredient-entry")[1] || id.split("packaging-entry")[1];

                let cost;  
                ids = [];
                const sub =  $(this).find('.sub-elements').children('.substitute-packaging').each(function(){
                    ids.push($(this).attr('id'));

                });

                  
                       
                    
                    const quantity = parseFloat(cost) || 0;  
                    const container = $(this).attr('id');
              
                    console.log(ids.length);    
                    if(ids.length == 0)
                    {
                        cost = parseFloat($(`.costparent${lastChar}`).val()) || 0;
                    }
                    else
                    {
                        cost = parseFloat($(`.costparent${lastChar}`).val()) || 0;
                        ids.forEach(function(id) {
                            const $el = $(`#${id}`); // select element by id
                            
                            if ($el.css('background-color') === 'rgb(255, 230, 98)') {
                                var lastCharEl = id.split("ingredient-entry")[1] || id.split("packaging-entry")[1];
                                console.log($('#cost'+ lastCharEl).val());
                                cost = parseFloat($('#cost'+ lastCharEl).val()) || 0;  
                            } 
                        });
            
                    }
                    
                    if(container.includes('ingredient-entry'))
                    {
                       
                        ingredientsCost += cost;  
                        total_prepquantity_ing += parseFloat($('#quantity'+$(this).attr('id').replace(/\D/g, '')).val()) || 0; 
                    }
                    else
                    {
                        packagingsCost += cost; 
                        total_prepquantity_pac += parseFloat($('#quantity'+$(this).attr('id').replace(/\D/g, '')).val()) || 0; 
                    }
            }); 
            $('#packaging_cost').val(packagingsCost.toFixed(2));
            $('#ingredient_cost').val(ingredientsCost.toFixed(2));  


            const packagingCost = parseFloat($('#packaging_cost').val()) || 0;
            const ingredientCost = parseFloat($('#ingredient_cost').val()) || 0;
            const labor = parseFloat($('#labor_cost').val()) || 0;
            const gas = parseFloat($('#gas_cost').val()) / 100 || 0;
            const StorageCost = parseFloat($('#storage_cost').val()) / 100 || 0; 
            const utilities = parseFloat($('#utilities').val()) / 100 || 0; 
            const markupPercent = parseFloat($('#markup_percentage').val()) || 0; 
            const landed_cost = parseFloat($('#landed_cost').val()) || 0; 
            const purchase_price = parseFloat($('#purchase_price').val()) || 0; 


            const opex = gas + StorageCost + utilities;
            const lc_sp = landed_cost + purchase_price;

            $('#opex').val(opex.toFixed(2)); 
            
            
            const food_cost = packagingCost + ingredientCost;
            const fc_pm_opex =  food_cost + packagingCost + opex;

            $('#food_cost').val(food_cost.toFixed(2));
            $('#fc_pm_opex').val(fc_pm_opex.toFixed(2));
            $('#lc_sp').val(lc_sp.toFixed(2));
            $('#landed_cost').val(food_cost.toFixed(2));
            $('#purchase_price').val(food_cost.toFixed(2));
            const total = labor + gas + StorageCost + utilities + markupPercent + food_cost + opex;

        
            const finalValueVATex = total;
         
            $('#ttp').val(finalValueVATex.toFixed(2));
            $('#final_value_vatex').val(finalValueVATex.toFixed(2));
            $('#final_value_vatinc').val((finalValueVATex * 1.12).toFixed(2)); 

            $('[id*="qty-contribution"]').each(function() {

                let id = $(this).attr('id');
                let lastChar = id.split("qty-contribution")[1]; 
                let cost = Number($(`#cost${lastChar}`).val()); 
                $(this).val(Number($(`#quantity${lastChar}`).val()).getContribution(total_prepquantity_ing).toString() + '%');
                if(!isNaN(cost.getContribution($(`#ingredient_cost`).val())))
                {
                    $(`#costparent-contribution${lastChar}`).val(cost.getContribution($(`#ingredient_cost`).val()).toString() + '%');      
                }
                else
                {
                     $(`#costparent-contribution${lastChar}`).val('0.00' + '%');
                }
               
            }); 

             $('[id*="qty-contribution-pack"]').each(function() {
                    
                let id = $(this).attr('id');
                let lastChar = id.split("qty-contribution-pack")[1]; 
                let cost = Number($(`#cost${lastChar}`).val()); 
                $(this).val(Number($(`#quantity${lastChar}`).val()).getContribution(total_prepquantity_pac).toString() + '%');

                if(!isNaN(cost.getContribution($(`#packaging_cost`).val())))
                {
                    $(`#costparent-contribution-pack${lastChar}`).val(cost.getContribution($(`#packaging_cost`).val()).toString() + '%');
                } 
                else
                {
                    $(`#costparent-contribution${lastChar}`).val('0.00' + '%');
                }
            }); 
        }



        Number.prototype.getContribution = function(total_cost) {
           // total_ingredient_cost = $(total_cost).val() || 0;
            contribution = this.toFixed(2) || 0;

            return Number(contribution / total_cost * 100, 1).toFixed(2) || 0;
        } 


         function showNoData() {
            const hasRows = $('[id*="packaging-entry"]').length; 
             console.log(hasRows + ' hasRows packaging');
            if (hasRows === 0) {
                $('.no-data-available').show();
                is_noingredient = true;
            } else {
                $('.no-data-available').hide();
                is_noingredient = false;
            }
        }

        function showNoDataIngredient() {
            const hasRows = $('[id*="ingredient-entry"]').length;  
            console.log(hasRows + ' hasRows ingredients');
              console.log(hasRows + 'show');  
            if (hasRows === 0) {
                $('.no-data-available-ingredient').show(); 
            } else {
                $('.no-data-available-ingredient').hide(); 
            }
        }

          function showNoDataLabor() {
            const hasRows = $('[id*="labor-entry-sub"]').length;  
            console.log(hasRows + ' hasRows ingredients');
              console.log(hasRows + 'show');  
             if (hasRows === 0) {
                $('.no-data-available-labor').show(); 
            } else {
                $('.no-data-available-labor').hide(); 
            }
        }
         
        
        showNoData(); 
        showNoDataIngredient();
        showNoDataLabor();
        format_decimal(); 
        function format_decimal(){
           $('#ProductionItems').find('input').on('blur', function() {
                if ($(this).attr('type') === 'number') {
                    let val = parseFloat($(this).val());
                    if (!isNaN(val)) {
                        $(this).val(val.toFixed(2)).trigger('input');
                    } else {
                        $(this).val('').trigger('input');
                    }
                    calculateTotalStorage();
                    calculateFinalValues();
                }
            });
        }
  
        if(disableifapproval == 'production_items_approvals' || view_ == 'true')
        {
            $('#ProductionItems').find('input, select, textarea').prop('disabled', true);
            $('[class*="btn"]').hide();
            $('#approve-btn').show(); 
            $('#reject-btn').show();
            $('#cancel-btn').show();
        }


           //triggering tasteless_code change and input to calculate packeaging/ingredients cost
        $(document).ready(function() {
        $('[id*="quantity"], [id*="tasteless_code"]').each(function() {
            const eventInput = new Event('input', { bubbles: true });
            const eventChange = new Event('change', { bubbles: true }); 
            this.dispatchEvent(eventInput);
            this.dispatchEvent(eventChange);
        });
        });



        
        function Load_Production_Item_Lines()
        {
            const production_item_lines = @json($production_item_lines);
            const production_items_comments = @json($production_items_comments);  

            //looping for parent packeaging/ingredients
            production_item_lines.forEach(item => {
          
                if(item.production_item_line_type == 'packaging' && item.production_item_line_id == item.packaging_id && item.production_item_line_type != 'labor') 
                { 

                     //function Sub_gen_pack_row(rowId, tasteless_code, quantity, cost, description)
                    const newRowHtml = generateRowHtml(
                        item.production_item_line_id,
                        item.production_item_line_id,
                        item.item_code,
                        item.quantity,
                        item.landed_cost,  
                        item.description,
                        item.default_cost,
                        item.packaging_size,
                        "",
                        ""
                    );
                    $(newRowHtml).appendTo('#package-tbody');
                     showNoDataIngredient();
                     if ($(`#itemDesc${item.production_item_line_id}`).length > 0) {
                        PackagingSearchInit(`#itemDesc${item.production_item_line_id}`, item.production_item_line_id);
                     }
                }
                
                else if(item.production_item_line_id == item.packaging_id && item.production_item_line_type != 'labor')
                { 
                    const newRowHtml = generateRowingredientHtml(
                        item.production_item_line_id,
                        item.production_item_line_id,
                        item.item_code,
                        item.description,
                        item.landed_cost,
                        item.quantity,
                        item.yield,
                        item.packaging_size,
                        item.landed_cost,
                        "",
                        "",
                        item.preparations,
                        item.preparation_desc,
                        "",
                        ""
                        //rowId, DB_id, tasteless_code, itemDesc, ttp, quantity, yiel, packsize, cost , costparent_contribution, qty_contribution, preparations ,description
                    );
                    $(newRowHtml).appendTo('#ingredient-tbody');
                    showNoData();
                    if ($(`#itemDesc${item.production_item_line_id}`).length > 0) {
                     IngredientSearch(`#itemDesc${item.production_item_line_id}`, item.production_item_line_id);
                    }
                    

                }else if(item.production_item_line_type == 'packaging' && item.production_item_line_id != item.packaging_id  && item.production_item_line_type != 'labor') 
                { 
                 
                    const matchingInput =  item.packaging_id;
                    
                    const newRowHtml = Sub_gen_pack_row(
                        item.production_item_line_id,
                        item.production_item_line_id,
                        item.item_code,
                        item.quantity,
                        item.landed_cost,  
                        item.description,
                        item.default_cost,
                        item.packaging_size,
                        "",
                        ""
                    );
                      
                    // rowId, DB_id, tasteless_code, quantity, cost, description, default_cost, packsize, costparent_contribution, qty_contribution
                    $(newRowHtml).appendTo(`.sub-pack${matchingInput}`); 
                     showNoDataIngredient();
                     if ($(`#itemDesc${item.production_item_line_id}`).length > 0) {
                        PackagingSearchInit(`#itemDesc${item.production_item_line_id}`, item.production_item_line_id);
                     }
                }

                else if (item.production_item_line_id != item.packaging_id && item.production_item_line_type != 'labor')
                { 
                   
                    const matchingInput = item.packaging_id;
                    
                    const newRowHtml = Sub_gen_ingredient_row(
                        item.production_item_line_id,
                        item.production_item_line_id,
                        item.item_code,
                        item.description,
                        item.landed_cost,
                        item.quantity,
                        item.yield,
                        item.packaging_size,
                        item.landed_cost,
                        "",
                        "",
                        item.preparations,
                        item.preparation_desc,
                        "",
                        ""
                    );
                    // console.log(`.sub-ingredient${oldindex}`);
                    $(newRowHtml).appendTo(`.sub-ingredient${matchingInput}`); 
                    showNoData();
                    if ($(`#itemDesc${item.production_item_line_id}`).length > 0) {
                     IngredientSearch(`#itemDesc${item.production_item_line_id}`, item.production_item_line_id);
                    }
                } 
                tableRow = item.production_item_line_id;
            });


            production_item_lines.forEach(item => {
                
                if (item.production_item_line_type == 'labor') { 
                        tableRow++; 
                        const newRowPackHtml = Sub_gen_Labor_row(tableRow, item.time_labor, item.yield, item.preparations, item.preparation_desc);  
                        $(newRowPackHtml).appendTo(`.sub-labor`); 
                        showNoDataLabor(); 
                        $(`#time-labor${tableRow}`).trigger('change');
                }    
            });
 
            if(production_items_comments)
            {
                production_items_comments.forEach(item => {
                
                    if (!item.parent_id) { 
                        const newRowHtml = Add_Comment(item.comment_id, item.comment_content, item.created_by,"{{ request()->getSchemeAndHttpHost() }}"+"/"+item.profile_pic,item.created_at);
                        $(newRowHtml).appendTo('.comment-section'); 
                        ScrollToBottom('.comment-section');
                        $(`#add_comment_field`).val("");
                    }else
                    {
                        const newRowHtm2l = Add_Comment_Reply(item.comment_id, item.comment_content, item.created_by, "{{ request()->getSchemeAndHttpHost() }}"+"/"+item.profile_pic,item.created_at);
                        $(newRowHtm2l).appendTo('.comment-reply'+ item.parent_id); 
                        ScrollToBottom('.comment-section');
                        $(`#Textarea${item.parent_id}`).val("");
                    }  
                });
            }
        } 

        function submitComment(comment_type, append_to,production_items_id, comment_content, comment_id, parent_id, created_by)
        {
            is_SendingComment = true;
            $.ajax({
            url: `{{ route('send-comment') }}`,
            method: 'POST', 
            dataType: 'json', 
            data:   { 
                        production_items_id : production_items_id,
                        comment_content : comment_content,
                        comment_id : comment_id,
                        parent_id : parent_id 
                    },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'  // If using Laravel for CSRF protection
                },
                    success: function(response) {
                         console.log(response);
                        is_SendingComment = false;
                        if(comment_type == 'comment_reply')
                        {
                            const newRowHtm2l = Add_Comment_Reply(comment_id, comment_content, created_by, "http://timfs.test/uploads/1/2019-10/avatar.jpg","{{now()}}");
                            $(newRowHtm2l).appendTo(append_to); 
                            $(`#Textarea${parent_id}`).val("");
                        }
                        else
                        {
                            const newRowHtml = Add_Comment(comment_id, comment_content, created_by, "http://timfs.test/uploads/1/2019-10/avatar.jpg","{{now()}}");
                            $(newRowHtml).appendTo('.comment-section'); 
                            $(`#add_comment_field`).val("");
                        }
                    } 
                }); 
        }


        //To Append HTML section
         function Add_Comment(rowId, message, name, image_link, time) {
            return `
                    <li class=" media">
                        <div class="media-left">
                                <img src="${image_link}" alt="User Avatar" width="30" height="30" class="rounded-circle mr-3" >
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    ${name}
                                    <br>
                                    <small>
                                        commented on <a href="#">${time}</a>
                                    </small>
                                </h4>
                                <p id="message${rowId}">
                                    ${message}
                                </p>
                                
                                <div class="comment-sub comment-reply${rowId}" id="${rowId}">
                                    <!-- reply inject here --> 
                                </div>
                                  

                                <div class="media-left post-footer-option container">
                                <ul class="list-unstyled" style="display:flex; gap:15px;  ">
                                    
                                    <li style="color:#999; margin-left: -15px; font-size:12px; display:flex; align-items:center; cursor:pointer;"data-toggle="collapse" data-target="#collapseExample${rowId}" aria-expanded="false" aria-controls="collapseExample${rowId}">
                                        <i class="glyphicon glyphicon-comment" style="margin-right:5px;"></i> Reply
                                    </li>
                                </ul>
                                <div class="collapse" id="collapseExample${rowId}">
                                    <textarea class="form-control" id="Textarea${rowId}" rows="3"></textarea>
                                
                                    <ul class="list-unstyled" style="display:flex; gap:15px;">
                                        <li style="color:#999; margin-left: 0px; font-size:12px; display:flex;" id="send-comment-btn${rowId}">
                                            <i class="glyphicon glyphicon-send " style="margin-right:5px;"></i> Send
                                        </li>
                                        <li style="color:#999;  font-size:12px; display:flex; align-items:center; cursor:pointer;"data-toggle="collapse" data-target="#collapseExample${rowId}" aria-expanded="false" aria-controls="collapseExample${rowId}">
                                        <i class="glyphicon glyphicon-remove" style="margin-right:5px;"></i> Cancel
                                        </li>
                                    </ul>
                                </div>
                            
                            </div>
                        </div>
                    </li> 
                    <hr>
                 `;
            }

            function Add_Comment_Reply(rowId, message, name, image_link, time) {
            return `
                     
                     <div style=" 
                                margin-left: 50px;
                                margin-bottom: 10px;">
                                <div class="media-left">
                                <img src="${image_link}" alt="User Avatar" width="30" height="30" class="rounded-circle mr-3" >
                                </div>
                                <div class="media-body">
                                <h4 class="media-heading">
                                 ${name}
                                <br>
                                <small>
                                commented on <a>${time}</a>
                                </small>
                                </h4>
                                <p >
                                    ${message}
                                </p>
                                </div>
                                </div>
                                
                 `;
        }

          function Sub_gen_Labor_row(rowId, time_labor, yields, preparations, description) {
            return `  
                <div class="substitute-packaging animate__animated animate__bounceIn"  id="labor-entry-sub${rowId}">
                <div class="packaging-inputs">
                    <input
                        value="labor"
                        class="form-control yield hide"
                        name="LaborLines[${rowId}][production_item_line_type]" 
                        type="text"  
                        required
                    >
                    <label>
                    <span class="required-star">*</span> Preparations
                    <select class="form-control select labor-cost-dropdown" id="preparations${rowId}" name="LaborLines[${rowId}][preparations]" required>
                        <option value=""  ${description == null ? 'selected' : ''}>Select Process </option> 
                        @foreach($menu_ingredients_preparations as $preparations)
                            <option value="{{ $preparations->id }}" ${@json($preparations->id) == preparations ? 'selected' : '' }>
                                {{ $preparations->preparation_desc }}
                            </option>
                        @endforeach 
                    </select>
                    </label> 

                    <label class="label-wide">
                    <span class="required-star">*</span> Time (minutes)
                    <input
                        value="${time_labor}"
                        class="form-control yield"
                        name="LaborLines[${rowId}][time-labor]"
                        id="time-labor${rowId}" 
                        type="number"
                        min="0"
                        step="1"
                        placeholder="Enter minutes"
                        required
                    >
                    </label>

                    <label class="label-wide">
                    <label> 
                    <label class="label-wide">
                    <span class="required-star">*</span> Yield %
                    <input value="${yields}"  class="form-control yield"  name="LaborLines[${rowId}][yiel]" id="yiel${rowId}" type="number" required>
                    </label>
                    <label class="label-wide">
                    <span class="required-star">*</span> Minutes per pack <span class="date-updated"></span>
                    <input value="" class="form-control ttp"  LaborLines[${rowId}][pack-minute] id="pack-minute${rowId}" type="number" readonly required>
                    </label> 
                     
                </div>
                <div class="actions"> 
                    <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button">
                    <i class="fa fa-minus"></i>
                    </button>
                </div>
                </div>`;
            }

              
        function Sub_gen_ingredient_row(rowId, DB_id, tasteless_code, itemDesc, ttp, quantity, yiel, packsize, cost, costparent_contribution, qty_contribution, preparations, description)
          {
            return `  
                <div class="substitute-packaging sub-ing animate__animated animate__bounceIn" id="ingredient-entry${rowId}" >
                <div class="packaging-inputs">
                       <label class="packaging-label">
                            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
                            <div>
                                <input
                                        value="ingredient"
                                        class="form-control yield hide"
                                        name="" 
                                        id="production_type${rowId}"
                                        type="text"  
                                        required
                                    >
                                <input value="${tasteless_code}" type="text" id="tasteless_code${rowId}" name="produtionlines[${rowId}][][tasteless_code]"  class="packaging form-control hidden" required/>
                                <input value="${DB_id}" type="text" id="DB_id${rowId}" class="packaging form-control hidden"/>
                                <input value="${itemDesc}" type="text" id="itemDesc${rowId}" name="produtionlines[${rowId}][][description]" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                <div class="item-list">
                                      <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"  id="ui-id-2${rowId}" style="display: none;  width: 120px; color:red; padding:5px;">
                                <li class="text-center">Loading...</li>
                                </ul>
                                </div>
                             
                            </div>
                            
                        </label>
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="${quantity}" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any" required/>
                        </label> 
                          <label>
                        <span class="required-star">*</span> Preparations
                        <select class="form-control select labor-cost-dropdown" id="preparations${rowId}" name="preparations" required>
                            <option value=""  ${description == null ? 'selected' : ''}>Select Process </option> 
                            @foreach($menu_ingredients_preparations as $preparations)
                                <option value="{{ $preparations->id }}" ${@json($preparations->id) == preparations ? 'selected' : '' }>
                                    {{ $preparations->preparation_desc }}
                                </option>
                            @endforeach
                        </select>
                        </label> 
                        <label class="label-wide">
                            <span class="required-star">*</span> Yield %
                            <input value="${yiel}" class="form-control yield" id="yield${rowId}" type="number" required>
                        </label>
                        <label class="label-wide">
                            <span class="required-star">*</span> Landed Cost <span class="date-updated"></span>
                            <input value="${ttp}" class="form-control ttp" id="ttp${rowId}" type="number" readonly required>
                        </label>
                        
                        <label>
                            <span class="required-star">*</span> Actual Pack UOM
                            <input value="" class="form-control pack-quantity" id="ingredient-qty${rowId}" type="number" readonly required>
                        </label> 
                        <label>
                            <span class="required-star">*</span> Packaging Size
                           <input value="${packsize}" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label> 
                        <label>
                            <span class="required-star">*</span> Ingredient Cost
                            <input value="${cost}" id="cost${rowId}" class="form-control cost" type="text" readonly required>
                        </label> 
                       
                </div>
                <div class="actions">
                    <button class="btn btn-info set-primary" id="set-primary${rowId}" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
                    <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
                </div>
            </div> 
            `;
          }
                                                
          function generateRowingredientHtml(rowId, DB_id, tasteless_code, itemDesc, ttp, quantity, yiel, packsize, cost , costparent_contribution, qty_contribution, preparations ,description) {
            return ` 
            
            <div class="packaging-wrapper animate__animated animate__bounceIn" id="ingredient-entry${rowId}">
                <div class="packaging-entry" isExisting="true">
                    <div class="packaging-inputs">
                        <label class="packaging-label">
                            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
                            <div>
                               <input
                                        value="ingredient"
                                        class="form-control yield hide"
                                        name="" 
                                        id="production_type${rowId}"
                                        type="text"  
                                        required
                                    >
                                <input value="${tasteless_code}" type="text" id="tasteless_code${rowId}" class="packaging form-control hidden " required/>
                                 <input value="${DB_id}" type="text" id="DB_id${rowId}" class="packaging form-control hidden"/>
                                <input value="${tasteless_code}" type="text" id="tasteless_code_original${rowId}"   class="packaging form-control  hidden" required/>
                                <input value="${itemDesc}" type="text" id="itemDesc${rowId}" name="produtionlines[${rowId}][][description]" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                <div class="item-list">
                                      <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"  id="ui-id-2${rowId}" style="display: none;  width: 120px; color:red; padding:5px;">
                                <li class="text-center">Loading...</li>
                                </ul>
                                </div>
                             
                            </div>
                            
                        </label>
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="${quantity}" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any" required/>
                        </label> 
                        <label>
                        <span class="required-star">*</span> Preparations
                        <select class="form-control select labor-cost-dropdown" id="preparations${rowId}" name="preparations" required>
                            <option value=""  ${description == null ? 'selected' : ''}>Select Process </option>
                             @foreach($menu_ingredients_preparations as $preparations)
                                <option value="{{ $preparations->id }}" ${@json($preparations->id) == preparations ? 'selected' : '' }>
                                    {{ $preparations->preparation_desc }}
                                </option>
                            @endforeach
                        </select>
                        </label> 
                        <label class="label-wide">
                            <span class="required-star">*</span> Yield %
                            <input value="${yiel}" class="form-control yield" id="yield${rowId}" type="number" required>
                        </label>
                        <label class="label-wide">
                            <span class="required-star">*</span> Landed Cost <span class="date-updated"></span>
                            <input value="${ttp}" class="form-control ttp" id="ttp${rowId}" type="number" readonly required>
                        </label>
                        
                        <label>
                            <span class="required-star">*</span> Actual Pack UOM
                            <input value="" class="form-control pack-quantity" id="ingredient-qty${rowId}" type="number" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Qty Contribution
                            <input value="${qty_contribution}" class="form-control pack-quantity" id="qty-contribution${rowId}" type="text" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Packaging Size
                           <input value="${packsize}" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label> 
                        <label>
                            <span class="required-star">*</span> Ingredient Cost
                            <input value="${cost}" id="cost${rowId}" class="form-control costparent${rowId} cost" type="text" readonly required>
                        </label>
                        <label>
                            <span class="required-star">*</span> Cost Contribution
                            <input value="${costparent_contribution}" class="form-control pack-quantity" id="costparent-contribution${rowId}" type="text" readonly required>
                        </label>
                    </div>
                    <div class="actions">
                        <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
                        <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
                        <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
                    </div>
                </div>
                <div class="sub-ingredient${rowId} sub-elements">
                    

                </div>
                <div  class="add-sub-btn" style="background-color: green;" title="Add Substitute Ingredient">
                    <i class="fa fa-plus"></i>
                </div> 
            </div>
            `;
        }

  
         //generate sub for packaging
          function Sub_gen_pack_row(rowId, DB_id, tasteless_code, quantity, cost, description, default_cost, packsize, costparent_contribution, qty_contribution )
          {
            return `  
            <div class="substitute-packaging  sub-pack  animate__animated animate__bounceIn" id="packaging-entry${rowId}">
                <div class="packaging-inputs">
                      <label class="packaging-label">
                            <span class="required-star">*</span> Packaging <span class="item-from label"></span> <span class="label label-danger"></span>
                            <div>
                                 <input
                                        value="packaging"
                                        class="form-control yield hide"
                                        name="" 
                                        id="production_type${rowId}"
                                        type="text"  
                                        required
                                    >
                                <input value="${tasteless_code}" type="text" id="tasteless_code${rowId}" class="packaging form-control hidden" required/>
                                <input value="${DB_id}" type="text" id="DB_id${rowId}" class="packaging form-control hidden"/>
                                <input value="${description}" type="text" id="itemDesc${rowId}" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                <div class="item-list">
                                      <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"  id="ui-id-2${rowId}" style="display: none;  width: 120px; color:red; padding:5px;">
                                <li class="text-center">Loading...</li>
                                </ul>
                                </div>
                             
                            </div>
                            
                        </label> 
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="${quantity}" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any" required/>
                        </label>  
                          <label>
                            <span class="required-star">*</span> Packaging Size
                           <input value="${packsize}" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label> 
                        <label>
                            <span class="required-star">*</span> Packaging Cost
                            <input value="${default_cost}" id="default_cost${rowId}" class="form-control cost hide" type="text" readonly required>
                            <input value="${cost}" id="cost${rowId}" class="form-control cost" type="text" readonly required>
                        </label> 
                        </div>
                        <div class="actions">
                            <button class="btn btn-info set-primary" id="set-primary${rowId}" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
                            <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
                        </div>
            </div> 
            `;
          }

          function generateRowHtml(rowId, DB_id, tasteless_code, quantity, cost, description, default_cost, packsize, costparent_contribution, qty_contribution) {
            return `  
                 
                    <div class="packaging-wrapper animate__animated animate__bounceIn" id="packaging-entry${rowId}">
                    <div class="packaging-entry" isExisting="true">
                        <div class="packaging-inputs">
                            <label class="packaging-label">
                                <span class="required-star">*</span> Packaging <span class="item-from label"></span> <span class="label label-danger"></span>
                                <div>
                                    <input
                                        value="packaging"
                                        class="form-control yield hide"
                                        name="" 
                                        id="production_type${rowId}"
                                        type="text"  
                                        required
                                    >
                                    <input value="${tasteless_code}" type="text" id="tasteless_code${rowId}" class="packaging form-control  hidden " required/>
                                    <input value="${DB_id}" type="text" id="DB_id${rowId}" class="packaging form-control hidden"/>
                                    <input value="${tasteless_code}" type="text" id="tasteless_code_original${rowId}" class="packaging form-control hidden " required/>
                                    <input value="${description}" type="text" id="itemDesc${rowId}"      class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                                    <div class="item-list">
                                        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content"  id="ui-id-2${rowId}" style="display: none;  width: 120px; color:red; padding:5px;">
                                    <li class="text-center">Loading...</li>
                                    </ul>
                                    </div>
                                
                                </div>
                                
                            </label> 
                            <label>
                                <span class="required-star">*</span> Preparation Qty 
                                <input value="${quantity}" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any" required/>
                            </label>  
                              <label>
                            <span class="required-star">*</span> Packaging Size
                           <input value="${packsize}" class="form-control pack-quantity" id="pack-size${rowId}" type="number" readonly required>
                        </label> 
                         <label>
                        <span class="required-star">*</span> Qty Contribution
                        <input value="${qty_contribution}" class="form-control pack-quantity" id="qty-contribution-pack${rowId}" type="text" readonly required>
                        </label> 
                        <label>
                            <span class="required-star">*</span> Packaging Cost
                            <input value="${default_cost}" id="default_cost${rowId}" class="form-control cost hide" type="text" readonly required>
                            <input value="${cost}" id="cost${rowId}" class="form-control costparent${rowId} cost" type="text" readonly required>
                        </label>
                        <label>
                        <span class="required-star">*</span> Cost Contribution
                        <input value="${costparent_contribution}" class="form-control pack-quantity" id="costparent-contribution-pack${rowId}" type="text" readonly required>
                        </label>
                        </div>
                        
                        <div class="actions">
                            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
                            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
                            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
                        </div>
                    </div>
                    <div class="sub-pack${rowId} sub-elements">
                        

                    </div>
                    <div  class="add-sub-btn-pack" title="Add Substitute Packaging">
                        <i class="fa fa-plus"></i>
                    </div> 
                </div> 
            `;
        }
    });

 
      
</script>
@endpush
@endsection