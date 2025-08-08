
@push('head')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> 
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
 


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
                                                    <input value="{{ $item->ttp }}"  type="number" step="any" class="form-control sales_price" name="ttp" id="ttp" {{$item->ttp ? 'readonly' : ''}} required readonly>
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
                                                        id="labor_cost_per_minute" 
                                                        name="labor_cost_per_minute"
                                                        step="0.01"
                                                        class="form-control display-labor span-2" 
                                                        value="{{round($item->labor_cost_per_minute, 2)}}"
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
                                                    value="{{round($item->total_minutes_per_pack , 2)}}"
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
                                                    value="{{round($item->labor_cost_val , 2)}}"
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
                                @if($isAddPage != "add" && $isAddPage != "detail")
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
                                                            <img src="{{CRUDBooster::myPhoto()}}" alt="User Avatar" width="38" height="38" style="border-radius:50%; object-fit:cover;" />
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
                                @endif
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
                                            <tr>
                                                <td>Packaging Cost</td>
                                                <td>
                                                    <input type="number" step="any" name="packaging_cost" id="packaging_cost" value="{{ $item->packaging_cost }}" class="form-control text-right" readonly required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td> Food Cost </td><!--Ingredient Cost</td>-->
                                                <td>
                                                    <input type="number"  step="any" name="ingredient_cost" id="ingredient_cost" value="{{ $item->ingredient_cost }}" class="form-control text-right" readonly required>
                                                </td>
                                            </tr>
                                             <!--
                                             <tr>
                                                <td>Food Cost</td>
                                                <td>
                                                    <input type="number"  step="any" name="food_cost" id="food_cost" value="" class="form-control text-right">
                                                </td>
                                            </tr>
                                            -->
                                            <tr>
                                                <td>Labor Cost</td>
                                                <td>
                                                    <input type="number" step="any" name="labor_cost" id="labor_cost" value="{{ $item->labor_cost }}" class="form-control text-right" readonly required>
                                                </td>
                                            </tr> 
                                        </tbody>
                                    </table>

                                    <h5 class="text-center font-weight-bold">Opex Break Down</h5>
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Particulars</th>
                                                <th>Values</th>
                                                <th>Particulars * Ingredient Cost</th>
                                            </tr>
                                        </thead>
                                            <tbody> 
                                                <tr>
                                                    <td> Choose Opex </td>
                                                    <td colspan="2"> 
                                                        <select name="opex_category" id="production_items_opex" class="form-control">
                                                            <option value="" disabled selected> Manual </option>
                                                            @foreach ($production_items_opexs as $production_items_opex)
                                                                <option value="{{ $production_items_opex->id }}" {{ $production_items_opex->id == $item->opex_category ? 'selected' : '' }}>{{ $production_items_opex->opex_description }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td> 
                                                </tr>
                                                <tr> 
                                                    <td>Gas Cost (%)</td>
                                                    <td>
                                                        <input type="number"  step="0.01" name="gas_cost" id="gas_cost" value="{{ $item->gas_cost }}" class="form-control text-right">
                                                    </td>
                                                      <td>
                                                        <input type="number"  step="0.01" name="gas_costxfc" id="gas_costxfc" value="{{ $item->gas_costxfc }}" class="form-control text-right" readonly required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Storage Cost (%)</td>
                                                    <td>
                                                        <input type="number"  step="0.01" name="storage_cost" id="storage_cost" value="{{ $item->storage_cost }}" class="form-control text-right">
                                                    </td>
                                                      <td>
                                                        <input type="number"  step="0.01" name="storage_costxfc" id="storage_costxfc" value="{{ $item->storage_costxfc }}" class="form-control text-right" readonly required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> Meralco (%)</td>
                                                    <td>
                                                        <input type="number"  step="0.01" name="meralco" id="meralco" value="{{ $item->meralco }}" class="form-control text-right">
                                                    </td>
                                                      <td>
                                                        <input type="number"  step="0.01" name="meralcoxfc" id="meralcoxfc" value="{{ $item->meralcoxfc }}" class="form-control text-right" readonly required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> Water (%)</td>
                                                    <td>
                                                        <input type="number"  step="0.01" name="water" id="water" value="{{ $item->water }}" class="form-control text-right">
                                                    </td>
                                                    <td>
                                                        <input type="number"  step="0.01" name="waterxfc" id="waterxfc" value="{{ $item->waterxfc }}" class="form-control text-right" readonly required>
                                                    </td>
                                                </tr>
                                                    <tr>
                                                    <td> Opex</td>
                                                    <td colspan="2">
                                                        <input type="number"  step="0.01" name="opex" id="opex" value="{{ $item->opex }}" class="form-control text-right" readonly required>
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
                                                    <select name="transfer_price_category" id="transfer_price_category" class="form-control select2" readonly required>
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
                                                    <input type="text" name="markup_percentage" id="markup_percentage" value="{{ $item->markup_percentage }}" class="form-control text-right" readonly required>
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
                                            <td>Food Cost + Packaging</td>
                                                <td> 
                                                    <input type="text" value="" class="form-control rounded" name="fc_pm" id="fc_pm" placeholder="Food Cost + Packaging" aria-describedby="basic-addon1" readonly /> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Food Cost + Packaging + Opex</td>
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
                   <a href='{{ CRUDBooster::mainpath() }}' id="cancel-btn" class='btn btn-default'>Cancel</a>
                @else
                    @if(!$view) 
                    <div class="panel-footer"> 
                        <button type="button" _action="approve" class="btn btn-success action-btn" id="approve-btn"><i class="fa fa-thumbs-up"></i> Approve</button>
                        <button type="button" _action="reject" class="btn btn-danger action-btn" id="reject-btn" style="margin-right: 10px;"><i class="fa fa-thumbs-down"></i> Reject</button>
                        
                    @endif 
                    <a href='{{ CRUDBooster::mainpath() }}' id="cancel-btn" class='btn btn-default'>Cancel</a>
                    </div> 
                 @endif 
            </div>
 
    </div>

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    Swal.fire({
        title: 'Loading...',
        html: 'Please wait...',
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        },
    });
    
    $(document).ready(function() {
        Swal.close();
        const allSubcategories = {!! json_encode($subcategories) !!}; 
        
          

        
        
        console.log("{{$isAddPage}}");

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
         console.log(view_);
        // This Section is for all calculations
        state = {
            yieldPercent : 0,
            preperationQuantity : 0,
            packagingSize : 0,
            landed_cost : 0, 
            packaging_cost: 0,
            time_labor: 0,
            gas_cost: 0, 
            storage_cost: 0,
            meralco: 0,
            water: 0,
            ingredient_costs: 0
        } 

        //calculation cestion
        function calculateOpexLines(id)
        {
          return  state[id] * state.ingredient_costs;
        }

        function calculateLabor()
        {
            let calPackMinutes = state.time_labor / state.yieldPercent 
            
            return  calPackMinutes;
        }

        function CalculateLabor()
        {
            let cost = 0; 
            
            $('[id*="pack-minute"]').each(function() { 
                cost += parseFloat($(this).val()) || 0;  
            });  

            let labor_cost_per_minute = $('#labor_cost_per_minute').val();
            let total = math.round(cost * labor_cost_per_minute, 2); 
            $(`#total_minutes_per_pack`).val(math.round(cost, 2));
            $(`#labor_cost_val`).val(total);
            $(`#labor_cost`).val(total).trigger('input');
            
        }

        function calculateIngreditents()
        { 
            function get_actual_pack_uom()
            {
                return state.yieldPercent / 100 * state.packagingSize; 
            }

            function get_actual_ingredient_cost(actual_pack_uom)
            {
                return (state.landed_cost / actual_pack_uom) * state.preperationQuantity;
            } 

            let actual_pack_uom = get_actual_pack_uom();
            let actual_ingredient_cost = get_actual_ingredient_cost(actual_pack_uom);

            return { actual_pack_uom, actual_ingredient_cost };
        }

        function calculatePackagings()
        {
            let piece_cost = state.packaging_cost / state.packagingSize
            return piece_cost * state.preperationQuantity;
        }
        
        function calculateFinalValues() {
           
            let ingredientsCost = 0;
            let packagingsCost = 0;
            let total_prepquantity_ing = 0;
            let total_prepquantity_pac = 0;
            const rawMastProvision = 0; 
           
            $('[class*="packaging-wrapper"]').each(function() {
                 
                var id = $(this).attr('id');
                var lastChar = getIdNumber(id);
                let cost;    
                const quantity = parseFloat(cost) || 0;  
                const container = $(this).attr('id'); 
                cost = parseFloat($(`.costparent${lastChar}`).val()) || 0;
                 
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
            $('#gas_cost, #storage_cost, #meralco, #water').trigger('change');


            const packagingCost = parseFloat($('#packaging_cost').val()) || 0;
            const ingredientCost = parseFloat($('#ingredient_cost').val()) || 0;
            const labor = parseFloat($('#labor_cost').val()) || 0;
            const gas = parseFloat($('#gas_cost').val()) / 100 || 0;
            const StorageCost = parseFloat($('#storage_cost').val()) / 100 || 0; 
            const utilities = parseFloat($('#utilities').val()) / 100 || 0;  
            let markup_percentage = $('#markup_percentage').val().replace('%', ''); 
            const markupPercent = parseFloat(markup_percentage / 100) || 0; 
            
            const landed_cost = parseFloat($('#landed_cost').val()) || 0; 
            const purchase_price = parseFloat($('#purchase_price').val()) || 0; 
           // const opex = gas + StorageCost + utilities;
            //const fc_pm = landed_cost + purchase_price;

            //added code
            let gas_costxfc = parseFloat($('#gas_costxfc').val()) / 100 || 0;
            let storage_costxfc = parseFloat($('#storage_costxfc').val()) / 100 || 0;
            let meralcoxfc = parseFloat($('#meralcoxfc').val()) / 100 || 0;
            let waterxfc = parseFloat($('#waterxfc').val()) / 100 || 0;
            let opex = gas_costxfc + storage_costxfc + meralcoxfc + waterxfc; 
            
            $('#opex').val(opex.toFixed(2)); 
            
            
            const food_cost = packagingCost + ingredientCost;
            const fc_pm_opex =  food_cost + opex;
 
            $('#fc_pm_opex').val(fc_pm_opex.toFixed(2));
            $('#fc_pm').val(food_cost.toFixed(2));
            $('#landed_cost').val(food_cost.toFixed(2)).trigger('input');
            $('#purchase_price').val(food_cost.toFixed(2));
            const total = labor + markupPercent + ingredientCost + opex;

        
            const finalValueVATex = total;
         
            $('#ttp').val(finalValueVATex.toFixed(2)).trigger('input'); // sales_price
            $('#final_value_vatex').val(finalValueVATex.toFixed(2));
            $('#final_value_vatinc').val((finalValueVATex * 1.12).toFixed(2)); 
 
            // setting cost contribution
            $('[id*="qty-contribution"]').each(function() {

                let id = $(this).attr('id');
                let lastChar = getIdNumber(id);
                let cost = Number($(`#cost${lastChar}`).val());  

                if(!isNaN(Number($(`#quantity${lastChar}`).val()).getContribution(total_prepquantity_ing)))
                {
                    $(this).val(Number($(`#quantity${lastChar}`).val()).getContribution(total_prepquantity_ing).toString() + '%');
                }else
                {
                    $(this).val('');
                }

                if(!isNaN(cost.getContribution($(`#ingredient_cost`).val())))
                {
                    $(`#costparent-contribution${lastChar}`).val(cost.getContribution($(`#ingredient_cost`).val() || 0).toString() + '%');      
                }
                else
                {
                     $(`#costparent-contribution${lastChar}`).val('');
                }
               
            }); 

             $('[id*="qty-contribution-pack"]').each(function() {
                    
                let id = $(this).attr('id');
                let lastChar = getIdNumber(id);
                let cost = Number($(`#cost${lastChar}`).val()); 
                if(!isNaN(Number($(`#quantity${lastChar}`).val()).getContribution(total_prepquantity_pac)))
                {
                   $(this).val(Number($(`#quantity${lastChar}`).val()).getContribution(total_prepquantity_pac).toString() + '%');
                }else
                {
                    $(this).val('');
                }
              
                

                if(!isNaN(cost.getContribution($(`#packaging_cost`).val())))
                {
                    $(`#costparent-contribution-pack${lastChar}`).val(cost.getContribution($(`#packaging_cost`).val()).toString() + '%');
                } 
                else
                {
                    $(`#costparent-contribution${lastChar}`).val('');
                }
            }); 
        }



        Number.prototype.getContribution = function(total_cost) {
           // total_ingredient_cost = $(total_cost).val() || 0;
            contribution = this.toFixed(2) || 0;

            return Number(contribution / total_cost * 100, 1).toFixed(2) || 0;
        } 



        //loading ingredient and packaging data from contoller
        Load_Production_Item_Lines();
  
        //triggering tasteless_code change and input to calculate packeaging/ingredients cost
        $(document).ready(function() {
            $('[id*="quantity"], [id*="tasteless_code"]').each(function() {
                const eventInput = new Event('input', { bubbles: true });
                const eventChange = new Event('change', { bubbles: true }); 
                this.dispatchEvent(eventInput);
                this.dispatchEvent(eventChange);
            });
        });


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
            #storage_location,
            #production_items_opex
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
            
        $('.add-comment-btn').click(function(){
            if(is_SendingComment == false)
            {
                
                let comment = $(`#add_comment_field`).val();
                if(comment)
                {   
                    commentId++; 
                    submitComment('comment', '.comment-section', reference_number, comment, commentId, '', "{{CRUDBooster::myName()}}", false);
                } 
            }
        })

        $(document).on('click', '.send-comment-reply', function() { 
            if(is_SendingComment == false)
            {
                let id = $(this).attr('id').replace(/\D/g, '');  
                let reply = $(`#Textarea${id}`).val();
                
                if(reply)
                {  
                    commentId++; 
                    let parentId = $(this).closest('.collapse').closest('.post-footer-option').prev('.comment-sub').attr('id'); 
                    submitComment('comment_reply', `.comment-reply${id}`,  reference_number, reply, commentId, parentId, "{{CRUDBooster::myName()}}", true);  
                }
            }
           

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
            
            const newRowHtml = generateRowHtml(tableRow, "", "", "", "","","", "", "", "");
             $(newRowHtml).appendTo('#package-tbody');
            PackagingSearchInit(`#itemDesc${tableRow}`, tableRow); 
            showNoData();
                showNoDataIngredient(); 
        });

         $("#add-Row-ingredient").click(function () { 
            tableRow++;
            
            const newRowHtml = generateRowingredientHtml(tableRow, "", "", "", "", "", "", "", "", "", "", "");
            $(newRowHtml).appendTo('#ingredient-tbody'); 
            IngredientSearch(`#itemDesc${tableRow}`, tableRow); 
            showNoData();
                showNoDataIngredient();
                
        });
        
 
        $(document).on('click', '.add-sub-btn', function(event) {
            tableRow++;
            const parentId = $(this).parent().attr('id').split("ingredient-entry")[1];   
            const newRowPackHtml = Sub_gen_ingredient_row(tableRow, "", "", "", "", "", "", "", "", "", "", "");   
            $(newRowPackHtml).appendTo(`.sub-ingredient${parentId}`);
            
            IngredientSearch(`#itemDesc${tableRow}`, tableRow); 
       
        });


        $(document).on('click', '.add-sub-btn-pack', function(event) {
            tableRow++;
            const parentId = $(this).parent().attr('id').split("packaging-entry")[1];  
            const newRowPackHtml = Sub_gen_pack_row(tableRow, "", "", "", "", "","", "", "", "");  
            $(newRowPackHtml).appendTo(`.sub-pack${parentId}`);
             
            PackagingSearchInit(`#itemDesc${tableRow}`, tableRow);  
        });

        $(document).on('click', '.add-sub-btn-labor', function(event) {
            tableRow++; 
            const newRowPackHtml = Sub_gen_Labor_row(tableRow,"","","","","");  
             // function Sub_gen_Labor_row(rowId, time_labor, yields, preparations, description, labor_yield_uom) {
            $(newRowPackHtml).appendTo(`.sub-labor`); 
            showNoDataLabor(); 
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
                    
                    $(`#tasteless_code${id}`).val(ui.item.tasteless_code).trigger('change');
                    $(`#tasteless_code_original${id}`).val(ui.item.tasteless_code).trigger('change');
                    $(`#itemDesc${id}`).val(ui.item.item_description);  
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
           
                $(`#itemDesc${rowId}`  ).on('input', function() {
                $(`#quantity${rowId}`).val('');
                $(`#cost${rowId}`).val('');
                $(`#tasteless_code${rowId}`).val('');
                $(`#tasteless_code_original${rowId}`).val('');
                $(`#ttp${rowId}`).val('');
                $(`#pack-size${rowId}`).val('');  
                $(`#actual_pack_uom${rowId}`).val('');  
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

        function checkLandedCost(){
            const supplierCost = parseFloat($('#purchase_price').val());
            const landedCost = parseFloat($('#landed_cost').val());
            return landedCost >= math.floor(supplierCost, 2);
        }
        
        function updateCommiMargin() {
            const salesPrice = parseFloat($('#ttp_price_change').val() || $('#ttp').val() || 0);
            const landedCost = parseFloat($('#landed_cost').val() || 0);
            if (!landedCost || !salesPrice) return;
            const commiMargin = math.round((salesPrice - landedCost) / salesPrice, 2);

            $('#ttp_percentage').val(commiMargin);
        }


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
                    let markup_percentage = $('#markup_percentage').val().replace('%', ''); 
                    const markupPercent = parseFloat(markup_percentage / 100) || 0; 
                    $('#markup_percentage').val(markupPercent); 
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
                const isValid = checkLandedCost() && checkCommiMargin();
               
             if (isValid) {
             
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
                                    let markup_percentage = $('#markup_percentage').val().replace('%', ''); 
                                    const markupPercent = parseFloat(markup_percentage / 100) || 0; 
                                    $('#markup_percentage').val(markupPercent);  
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
                                    let markup_percentage = $('#markup_percentage').val().replace('%', ''); 
                                    const markupPercent = parseFloat(markup_percentage / 100) || 0; 
                                    $('#markup_percentage').val(markupPercent);  
                                    $('#sumit-form-button').click(); 
                                } 
                        });
                    } 
            }else{
                Swal.fire({
                    icon: "error",
                    title: "Invalid Input!",
                    text: "Check landed cost, supplier cost value, and commi margin.",
                });
            }
            
           
        });
 

        $('#ProductionItems').on('submit', function() {
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
                var lastChar = getIdNumber(id); 
                state.yieldPercent = $(`#yield${lastChar}`).val() || 0; 
                
                const container = $(this).closest('.packaging-wrapper').attr('id');
                
                if(container.includes('ingredient-entry'))
                {
                    if($(`#tasteless_code${lastChar}`).val() != '' && state.yieldPercent  != '0')
                    {
                        state.preperationQuantity = $(`#quantity${lastChar}`).val() || 0; 

                        state.packagingSize = $(`#pack-size${lastChar}`).val() || 0; 

                        state.landed_cost = $(`#ttp${lastChar}`).val() || 0;  
                        

                        const ingredientCost = math.round(calculateIngreditents().actual_ingredient_cost, 4); 
                        const ingredientQty = math.round(calculateIngreditents().actual_pack_uom, 4);  


                        $(`#cost${lastChar}`).val(Number(ingredientCost).toFixed(2)).attr('readonly', true);
                        $(`#actual_pack_uom${lastChar}`).val(Number(ingredientQty).toFixed(2)).attr('readonly', true); 
                    }  
                }
                else
                {
                    state.packaging_cost =  $(`#default_cost${lastChar}`).val() || 0; 
                    state.preperationQuantity = $(`#quantity${lastChar}`).val() || 1;   
                    state.packagingSize = $(`#pack-size${lastChar}`).val() || 1; 
                    

                    $(`#cost${lastChar}`).val(Number(calculatePackagings()).toFixed(2)).attr('readonly', true);
                    
                } 
                calculateFinalValues();
            });

            //Calcualte labor lines fields on change/input new data 
            $(document).on('change input', '[id*="yiel"], [id*="time-labor"], [id*="labor_cost_per_minute"]', function() {
              
                var id = $(this).attr('id');
                var lastChar = getIdNumber(id); 
                state.yieldPercent = $(`#yiel${lastChar}`).val() || 0;
                 

                state.time_labor = $(`#time-labor${lastChar}`).val() || 0; 
                let total = math.round(calculateLabor(), 2); 
                 $(`#pack-minute${lastChar}`).val(total); 
                 CalculateLabor();
                 calculateFinalValues();
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

        $('#full_item_description').on('input', function() {
            const text = $(this).val();
            $('#purchase_description').val(text);
        });
         

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
                'actual_pack_uom': $(`#actual_pack_uom${parentId}`).val(),
                'qty-contribution': $(`#qty-contribution${parentId}`).val(), 
                'pack-size': $(`#pack-size${parentId}`).val(),
                'cost': $(`#cost${parentId}`).val(),
                'costparent-contribution': $(`#costparent-contribution${parentId}`).val(),
                'default_cost': $(`#default_cost${parentId}`).val()
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
                'actual_pack_uom': $(`#actual_pack_uom${sub_id}`).val(),
                'qty-contribution': $(`#qty-contribution${sub_id}`).val(),
                'pack-size': $(`#pack-size${sub_id}`).val(),
                'cost': $(`#cost${sub_id}`).val(),
                'costparent-contribution': $(`#costparent-contribution${sub_id}`).val(),
                'default_cost': $(`#default_cost${sub_id}`).val(),
            };
            
 
            
            const prevBr = entry;   

            sibling.animate(
                {
                    top: `-=${entry.outerHeight() * total_sub}`,
                },
                {
                    duration: 100,
                    queue: false,
                    done: function() {
                        $(sibling).css('top', '0');

                        Object.entries(sub_contents).forEach(function ([key, value]){
                            $(`#${key+parentId}`).val(value); 
                        }); 
                       //  $(`#quantity${parentId}`).trigger('change');
                    }
                }
            );

            wrapperId.animate(
                {
                    top: `+=${sibling.outerHeight() * total_sub}`,
                },
                {
                    duration: 100,
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
  
        // setting ingredients and packaging name for form submission
        $(document).on('input change', 'input[id^="tasteless_code"]', function() {
            const $wrapper = $(this).closest('.packaging-wrapper');   
            const parentid_todb = $wrapper.find('input[id^="tasteless_code"]').attr('id').replace(/\D/g, ''); 
              

        $wrapper.find('input, select').each(function (){ 
                
                let idsub = $(this).attr('id');
                let lastCharsub = idsub.replace(/[^0-9]/g, '');
                let name  = idsub.replace(/\d+/g, ''); 
                $(this).attr('name', `produtionlines[${parentid_todb}][${lastCharsub}][${name}]`);
            });
        });

        

        $('#transfer_price_category').on('change', function(){ 
            if($(this).val() != 8)
            {
                var markup = $(this).find('option:selected').data('markup');  // get data-markup
                if(markup != null)
                {
                    $('#markup_percentage').val(markup + ' %').trigger('input'); 
                }
                
            } 
             
        });


        $('#transfer_price_category').on('change', function(){
            if($(this).val() == 8)
            {
                $('#markup_percentage').removeAttr('readonly required');
            }else
            {
                $('#markup_percentage').attr('readonly', true).attr('required', true);

            }
        })

        $('#markup_percentage').on('input', function(){  
            $('#markup_percentage').val(getIdNumber($(this).val().replace(' %', '')) + ' %');  
        });
        
        $('#markup_percentage').val($('#markup_percentage').val() * 100).trigger('input');

        $('#transfer_price_category').trigger('change');
        
        $('#gas_cost, #ingredient_cost, #storage_cost, #meralco, #water').on('change', function() { 
                let id = $(this).attr('id'); 
                state[id] = $(this).val(); 
                state.ingredient_costs = $('#ingredient_cost').val();
                $(`#${id}xfc`).val(calculateOpexLines(id).toFixed(2));  
        });
 
        $('#ttp, #landed_cost').on('input', function() { 
            updateCommiMargin();
        });


        const opex_ids = ['gas_cost','storage_cost','meralco','water']; 

        $('#production_items_opex').on('change', function(){
           
            const production_items_opexs = @json($production_items_opexs);
            const opex_value = production_items_opexs[$(this).val() - 1]; 
            opex_ids.forEach(function(e)
            {  
                $(`#${e}`).val(parseFloat(opex_value[e]).toFixed(2)).trigger('change'); 
            }); 
            calculateFinalValues(); 
        }) 

        opex_ids.forEach(function(e)
        {  
            $(`#${e}`).on('keyup', function(){ 
                $('#production_items_opex').val(null).trigger('change.select2');
            });
        }); 


        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper'); 

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
            entry.hide(300, function() {
                entry.prevAll('br').first().remove(); 
                $(this).remove();
                showNoData();
                showNoDataIngredient();
                calculateFinalValues();
            });
            
        }); 


        $(document).on('click', '.delete-sub', function(event) {
           let parent  =  $(this).closest('.sub-elements').attr('class')
            const subEntry = $(this).parents(`
                .substitute-ingredient, 
                .new-substitute-ingredient, 
                .substitute-packaging, 
                .new-substitute-packaging
            `);
            subEntry.hide('fast', function() {
                $(this).remove();
                calculateFinalValues();
                if(parent == 'sub-labor sub-elements')
                { 
                    $('#labor_cost_per_minute').trigger('change');
                } 
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


        
        function getIdNumber(id)
        {
            if(!id.replace(/[^0-9]/g, ''))
            {
                return 0;      
            }
            return id.replace(/[^0-9]/g, '');
        }
        function getIdName(id)
        {
            return id.replace(/\d+/g, '');
        }
 
 
         function showNoData() {
            const hasRows = $('[id*="packaging-entry"]').length; 
           
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
        
            if (hasRows === 0) {
                $('.no-data-available-ingredient').show(); 
            } else {
                $('.no-data-available-ingredient').hide(); 
            }
        }

          function showNoDataLabor() {
            const hasRows = $('[id*="labor-entry-sub"]').length;  
           
             if (hasRows === 0) {
                $('.no-data-available-labor').show(); 
            } else {
                $('.no-data-available-labor').hide(); 
            }
        }
         
        
        showNoData(); 
        showNoDataIngredient();
        showNoDataLabor(); 
        
        $(document).on('blur', 'input', function(){
             
                if ($(this).attr('type') === 'number') {
                    let val = parseFloat($(this).val());
                    if (!isNaN(val)) {
                        $(this).val(val.toFixed(2)).trigger('input');
                    } else {
                        $(this).val(parseFloat(0).toFixed(2)).trigger('input');
                    }
                    calculateTotalStorage();
                    calculateFinalValues();
                }
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
                        item.cost_contribution, //costparent_contribution
                        item.qty_contribution, //qty_contribution
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
                        item.production_item_line_id, //rowId
                        item.production_item_line_id, //DB_id
                        item.item_code, //tasteless_code
                        item.description, //itemDesc
                        item.landed_cost, //ttp
                        item.quantity, //quantity
                        item.yield, //yiel
                        item.packaging_size, //packsize
                        item.landed_cost, //cost
                        item.cost_contribution, //costparent_contribution
                        item.qty_contribution, //qty_contribution
                        item.preparations, //preparations
                        item.preparation_desc, // description  
                        item.actual_pack_uom
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
                  
                    $(newRowHtml).appendTo(`.sub-ingredient${matchingInput}`); 
                    showNoData();
                    if ($(`#itemDesc${item.production_item_line_id}`).length > 0) {
                     IngredientSearch(`#itemDesc${item.production_item_line_id}`, item.production_item_line_id);
                    }
                } 
                tableRow = item.production_item_line_id;
                 
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
      
        function submitComment(comment_type, append_to,production_items_id, comment_content, comment_id, parent_id, created_by, is_sending_reply_comment)
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
                      
                        is_SendingComment = false;
                        if(comment_type == 'comment_reply')
                        {
                            const newRowHtm2l = Add_Comment_Reply(comment_id, comment_content, created_by, "{{CRUDBooster::myPhoto()}}" ,"{{now()}}");
                            $(newRowHtm2l).appendTo(append_to); 
                            $(`#Textarea${parent_id}`).val("");
                        }
                        else
                        {
                            const newRowHtml = Add_Comment(comment_id, comment_content, created_by, "{{CRUDBooster::myPhoto()}}","{{now()}}");
                            $(newRowHtml).appendTo('.comment-section'); 
                            $(`#add_comment_field`).val("");
                        }
                        if(!is_sending_reply_comment)
                        {
                            ScrollToBottom('.comment-section');
                        }
                        
                    } 
                }); 
        }
        const production_item_lines = @json($production_item_lines);
        production_item_lines.forEach(item => {
            
            if (item.production_item_line_type == 'labor') { 
                    tableRow++; 
                    const newRowPackHtml = Sub_gen_Labor_row(tableRow, item.time_labor, item.yield, item.preparations, item.preparation_desc, item.labor_yield_uom);  
                                // function Sub_gen_Labor_row(rowId, time_labor, yields, preparations, description, labor_yield_uom) {
                    $(newRowPackHtml).appendTo(`.sub-labor`); 
                    showNoDataLabor(); 
                    $(`#time-labor${tableRow}`).trigger('change');
            }  
         
        });

       


        //To Append HTML section
         function Add_Comment(rowId, message, name, image_link, time) {
            return `
                    <li class=" media">
                        <div class="media-left">
                                <img src="${image_link}" alt="User Avatar" width="38" height="38" style="border-radius:50%; object-fit:cover;" />
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
                                        <li style="color:#999; margin-left: 0px; font-size:12px; display:flex;" class="send-comment-reply" id="send-comment-reply${rowId}">
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
                                <img src="${image_link}" alt="User Avatar" width="38" height="38" style="border-radius:50%; object-fit:cover;" />
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

          function Sub_gen_Labor_row(rowId, time_labor, yields, preparations, description, labor_yield_uom) {
            return `  
                <div class="substitute-packaging"  id="labor-entry-sub${rowId}">
                <div class="packaging-inputs">
                    <input
                        value="labor"
                        step="0.01"
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
                        step="0.01"
                        class="form-control yield"
                        name="LaborLines[${rowId}][time-labor]"
                        id="time-labor${rowId}" 
                        type="number"
                        min="0" 
                        placeholder="Enter minutes"
                        required
                    >
                    </label>

                    <label class="label-wide">
                    <label> 
                    <label class="label-wide">
                    <span class="required-star">*</span> Yield
                    <input value="${yields}"  step="0.01" class="form-control yield"  name="LaborLines[${rowId}][yiel]" id="yiel${rowId}" type="number" required>
                    </label>
                    
                     <label class="label-wide">
                    <span class="required-star">*</span> Yield UOM <span class="date-updated"></span>
                    <input value="${labor_yield_uom}" class="form-control" id="labor_yield_uom${rowId}" name="LaborLines[${rowId}][labor_yield_uom]" id="yiel${rowId}"  type="text"/>
                    </label> 
                    <label class="label-wide">
                    <span class="required-star">*</span> Duration <span class="date-updated"></span>
                    <input value="" class="form-control ttp" step="0.01" name="LaborLines[${rowId}][duration]" id="pack-minute${rowId}" type="number" readonly required>
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
                <div class="substitute-packaging sub-ing" id="ingredient-entry${rowId}" >
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
                        <span class="required-star">*</span> Process
                        <select class="form-control select labor-cost-dropdown" id="preparations${rowId}" name="preparations" required>
                            <option value=""  ${description == null ? 'selected' : ''}>Select Process </option> 
                            @foreach($menu_ingredients_preparations as $preparations)
                                <option value="{{ $preparations->id }}" ${@json($preparations->id) == preparations ? 'selected' : '' }>
                                    {{ $preparations->preparation_desc }}
                                </option>
                            @endforeach
                        </select>
                        </label> 
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="${quantity}" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any" required/>
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
                            <input value="" class="form-control pack-quantity" id="actual_pack_uom${rowId}" type="number" readonly required>
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
                                                
          function generateRowingredientHtml(rowId, DB_id, tasteless_code, itemDesc, ttp, quantity, yiel, packsize, cost , costparent_contribution, qty_contribution, preparations ,description , actual_pack_uom) {
            return ` 
            
            <div class="packaging-wrapper" id="ingredient-entry${rowId}">
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
                        <span class="required-star">*</span> Process
                        <select class="form-control select labor-cost-dropdown" id="preparations${rowId}" name="preparations" required>
                            <option value=""  ${description == null ? 'selected' : ''}>Select Process </option>
                             @foreach($menu_ingredients_preparations as $preparations)
                                <option value="{{ $preparations->id }}" ${@json($preparations->id) == preparations ? 'selected' : '' }>
                                    {{ $preparations->preparation_desc }}
                                </option>
                            @endforeach
                        </select>
                        </label>
                        <label>
                            <span class="required-star">*</span> Preparation Qty
                            <input value="${quantity}" id="quantity${rowId}" class="form-control prep-quantity" type="number" min="0" step="any" required/>
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
                            <input value="${actual_pack_uom}" class="form-control pack-quantity" id="actual_pack_uom${rowId}" type="number" readonly required>
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
            <div class="substitute-packaging  sub-pack" id="packaging-entry${rowId}">
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
                 
                    <div class="packaging-wrapper" id="packaging-entry${rowId}">
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
         $('form input').on('keyup keypress', function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return;
            }
        });

        if(disableifapproval == 'production_items_approvals' || view_ == 'true')
        { 
            $('#ProductionItems').find('input, select, textarea').prop('disabled', true); 
            $('[class*="btn"]').hide();
            $('#approve-btn').show(); 
            $('#reject-btn').show(); 
            $('#add_comment_field').prop('disabled', false);
            $('.add-comment-btn').show();
            $('[id*="Textarea"]').prop('disabled', false);
            $('#cancel-btn').show(); 
        } 
        $('input').trigger('blur');
    });

 
      
</script>
@endpush
@endsection