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
        <h3 class="text-center text-bold">Assets Masterfile Detail</h3>
    </div>
    <div class="panel-body">
        <form id="main-form" action="{{ route('item_maters_fa_approve_or_reject') }}" enctype="multipart/form-data" method="POST" class="form-main" autocomplete="off">
            <input type="text" class="hide" id="action-selected" name="action">
            <input type="text" class="hide" value="{{ $item->id }}" id="item_master_approvals_id" name="item_master_approvals_id">
            @csrf
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
                                <th><span class="required-star">*</span> UPC Code</th>
                                <td><input value="{{ $item->upc_code ?: '' }}" type="text" name="upc_code" id="upc_code" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Item Description</th>
                                <td><input value="{{ $item->item_description ? : '' }}" type="text" name="item_description" id="item_description" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                            </tr>
                            <tr>
                                <th>
                                    Display Photo 
                                    @if ($item->image_filename)
                                    <a href="{{ asset('img/item-master/' . $item->image_filename) }}" download="{{ $item->image_filename }}" class="btn btn-primary pull-right">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    @endif
                                </th>
                                <td class="with-download"><input value="{{ $item->image_filename }}" type="text" name="image_filename" id="image_filename" accept="image/*" class="form-control" readonly></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Coa</th>
                                <td>
                                    <select name="categories_id" id="categories_id" class="form-control" disabled>
                                        <option value="" disabled selected>None selected...</option>
                                        @foreach ($coa as $account)
                                        <option value="{{ $account->id }}" {{ $account->id == $item->categories_id ? 'selected' : '' }}>{{ $account->description }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Sub category</th>
                                <td>
                                    <select selected data-placeholder="Select Sub Category" class="form-control sub_category_id" name="subcategories_id" id="sub_category_id" disabled style="width:100%"> 
                                        @foreach ($sub_categories as $sub)
                                        <option value="{{ $sub->id }}" {{ $sub->id == $item->subcategories_id ? 'selected' : '' }}>{{ $sub->description }}</option>
                                        @endforeach
                                    </select>
                                </td>  
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Cost</th>
                                <td>
                                    <input value="{{ $item->cost }}" type="number" step="any" class="form-control" name="cost" id="cost" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span>  Currency</th>
                                <td>
                                    <select selected data-placeholder="Select Sub Category" class="form-control sub_category_id" name="subcategories_id" id="sub_category_id" disabled style="width:100%"> 
                                        @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $currency->id == $item->currency_id ? 'selected' : '' }}>{{ $currency->currency_code }}</option>
                                        @endforeach
                                    </select>
                                </td>  
                            </tr>
                        </tbody>
                    </table>
                 
                </div>
                <div class="col-md-6">
                    <table class="table-responsive table">
                        <tbody>
                            @if($item->supplier_item_code)
                                <tr>
                                    <th> Supplier Item Code</th>
                                    <td><input value="{{ $item->supplier_item_code ?: '' }}" type="text" name="supplier_item_code" id="supplier_item_code" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                                </tr>
                            @endif
                            <tr>
                                <th><span class="required-star">*</span> Brand Name</th>
                                <td>
                                    <input value="{{ $item->brand_description ?: '' }}" type="text" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly>
                                    {{-- <select selected data-placeholder="Choose" class="form-control brand_id" name="brand_id" id="brand_id" disabled style="width:100%"> 
                                        @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $brand->id == $item->brand_id ? 'selected' : '' }}>{{ $brand->brand_description }}</option>
                                        @endforeach
                                    </select> --}}
                                </td>
                            </tr>
                            @if($item->vendor1_id)
                                <tr>
                                    <th><span class="required-star">*</span> Vendor 1 Name</th>
                                    <td><input value="{{ $item->vendor1_id ?: '' }}" type="text" name="vendor1_id" id="vendor_id" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                                </tr>
                            @endif
                            @if($item->vendor2_id)
                                <tr>
                                    <th><span class="required-star">*</span> Vendor 2 Name</th>
                                    <td><input value="{{ $item->vendor2_id ?: '' }}" type="text" name="vendor2_id" id="vendor_id" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                                </tr>
                            @endif
                            @if($item->vendor3_id)
                                <tr>
                                    <th><span class="required-star">*</span> Vendor 3 Name</th>
                                    <td><input value="{{ $item->vendor3_id ?: '' }}" type="text" name="vendor3_id" id="vendor_id" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                                </tr>
                            @endif
                            @if($item->vendor4_id)
                                <tr>
                                    <th><span class="required-star">*</span> Vendor 4 Name</th>
                                    <td><input value="{{ $item->vendor4_id ?: '' }}" type="text" name="vendor4_id" id="vendor_id" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                                </tr>
                            @endif
                            @if($item->vendor5_id)
                                <tr>
                                    <th><span class="required-star">*</span> Vendor 5 Name</th>
                                    <td><input value="{{ $item->vendor5_id ?: '' }}" type="text" name="vendor5_id" id="vendor_id" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                                </tr>
                            @endif
                            @if($item->model)
                                <tr>
                                    <th> Model</th>
                                    <td><input value="{{ $item->model ?: '' }}" type="text" name="model" id="model" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                                </tr>
                            @endif
                            <tr>
                                <th><span class="required-star">*</span> Size</th>
                                <td><input value="{{ $item->size ?: '' }}" type="text" name="size" id="size" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                            </tr>
                            <tr>
                                <th><span class="required-star">*</span> Color</th>
                                <td><input value="{{ $item->color ?: '' }}" type="text" name="color" id="color" class="form-control" required oninput="this.value = this.value.toUpperCase()" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if ($item->image_filename)
                <div class="col-md-12">
                    <div class="photo-section">
                        <h3 class="text-center text-bold">DISPLAY PHOTO</h3>
                        <img src="{{ asset('/img/item-master-fa/' . $item->image_filename) }}" alt="Item Photo">
                    </div>
                </div>
                @endif
                <button id="sumit-form-btn" class="btn btn-primary hide" type="submit">submit</button>
       
            </div>
        </form>
    </div>
</div>

<script type="application/javascript">
    
</script>
@endsection