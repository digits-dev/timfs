<!-- First, extends to the CRUDBooster Layout -->
@push('head')

{{-- Jquery --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Swal Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

    .form-column{
    margin: 0 3vw;
    width: 33.3%;
        
    }

    .form-column fieldset{
        margin-bottom: 2vh;
        border: 1px solid #766b6b;
        box-shadow: 2px 3px #766b6b;
        border-radius: 5px;
        width: 100%;
        padding: 0
        
    }

    .form-column fieldset:hover{
        border: 1px solid #22577A;
        box-shadow: 2px 3px #22577A;
    } 

    .form-column legend{
        font-size: 15px;
        font-weight: bold;
        padding: 0px 20px;
        border-bottom: none;
        margin-bottom: auto;
        width: auto;
    }

    .form-column input{
        height: 100%;
        width: 100%;
        background: transparent;
        border: none;
        padding: 5px;
        font-size: 14px;
        border: 0;
        outline: 0;
        text-align: center;
    }

    .add-content{
        display: flex;
    }

    #required{
        color: red;
        font-weight: bold;
    }

    #success{
        color: green;
        font-weight: bold;
    }

    #menu_type_select1, #menu_type_select2, 
    #menu_type_select3, #menu_type_select4, 
    #menu_type_select5{
        width: 100%;
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
        border: none !important;
        width: 100% !important;
    }

    .select2-container .select2-search--inline .select2-search__field{
        text-align: center !important;
    }

    .select2-container--default .select2-selection--single{
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        text-align: center;
    }
    
</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Add Menu Item</a></p>
  <div class='panel panel-default'>
    <div class='panel-heading'>Add Menu Items</div>
    <div class='panel-body'>
        <form method="POST" action="{{CRUDBooster::mainpath('add-save')}}" id="form-add">
            @csrf
            <div class="add-content">
                <div class="form-column">
                    <fieldset>
                        <legend> POS OLD ITEM CODE 1</legend>
                        <input type="text" name="pos_item_code_1" placeholder="ENTER POS OLD ITEM CODE 1">
                    </fieldset>
                    <fieldset>
                        <legend> POS OLD ITEM CODE 2</legend>
                        <input type="text" name="pos_item_code_2" placeholder="ENTER POS OLD ITEM CODE 2">
                    </fieldset>
                    <fieldset>
                        <legend> POS OLD ITEM CODE 3</legend>
                        <input type="text" name="pos_item_code_3" placeholder="ENTER POS OLD ITEM CODE 3">
                    </fieldset>
                    <fieldset>
                        <legend> POS OLD DESCRIPTION</legend>
                        <input type="text" name="pos_item_description" placeholder="ENTER POS OLD DESCRIPTION">
                    </fieldset>
                    <fieldset>
                        <legend><span id="required">*</span> MENU DESCRIPTION</legend>
                        <input type="text" name="menu_item_description" placeholder="ENTER MENU DESCRIPTION" required>
                    </fieldset>
                    <fieldset>
                        <legend><span id="required">*</span> PRODUCT TYPE</legend>
                        <select class="js-example-basic-single" name="product_type" id="menu_type_select2" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_product_types as $product_type)
                                <option value="{{ $product_type->id }}">{{ $product_type->menu_product_type_description }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset>
                        <legend><span id="required">*</span> MENU TYPE</legend>
                        <select class="js-example-basic-single" name="menu_type" id="menu_type_select3" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_types as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->menu_type_description }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                </div>
                <div class="form-column">
                    <fieldset>
                        <legend> CHOICES GROUP 1</legend>
                        <input type="text" name="choices_group_1" placeholder="ENTER CHOICES GROUP 1">
                    </fieldset>
                    <fieldset>
                        <legend> CHOICES GROUP 1 SKU</legend>
                        <input type="text" name="choices_skugroup_1" placeholder="ENTER CHOICES GROUP 1 SKU">
                    </fieldset>
                    <fieldset>
                        <legend> CHOICES GROUP 2</legend>
                        <input type="text" name="choices_group_2" placeholder="ENTER CHOICES GROUP 2">
                    </fieldset>
                    <fieldset>
                        <legend> CHOICES GROUP 2 SKU</legend>
                        <input type="text" name="choices_skugroup_2" placeholder="ENTER CHOICES GROUP 2 SKU">
                    </fieldset>
                    <fieldset>
                        <legend> CHOICES GROUP 3</legend>
                        <input type="text" name="choices_group_3" placeholder="ENTER CHOICES GROUP 3">
                    </fieldset>
                    <fieldset>
                        <legend> CHOICES GROUP 3 SKU</legend>
                        <input type="text" name="choices_skugroup_3" placeholder="ENTER CHOICES GROUP 3 SKU">
                    </fieldset>
                    <fieldset>
                        <legend><span id="required">*</span> MAIN CATEGORY</legend>
                        <select class="js-example-basic-single" name="menu_categories" id="menu_type_select4" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_description }}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="form-column">
                    <fieldset>
                        <legend><span id="required">*</span> ORIGINAL CONCEPT</legend>
                        <input type="text" name="original_concept" placeholder="ENTER ORIGINAL CONCEPT" required>
                        </select>
                    </fieldset>
                    <fieldset>
                        <legend> PRICE - DELIVERY</legend>
                        <input type="number" name="price_delivery" placeholder="ENTER PRICE - DELIVERY">
                    </fieldset>
                    <fieldset>
                        <legend><span id="required">*</span> PRICE - DINE IN</legend>
                        <input type="number" name="price_dine_in" placeholder="ENTER PRICE - DINE IN" required>
                    </fieldset>
                    <fieldset>
                        <legend> PRICE - TAKE OUT</legend>
                        <input type="number" name="price_take_out" placeholder="ENTER PRICE - TAKE OUT">
                    </fieldset>   
                    <fieldset>
                        <legend><span id="required">*</span> MENU SEGMENTATION</legend>
                        <select class="js-example-basic-multiple" name="menu_segment_column_description[]" multiple="multiple" id="menu_type_select1" required>
                            @foreach ($menu_segmentations as $concept)
                                <option value="{{ $concept->id }}">{{ $concept->menu_segment_column_description }}</option>
                            @endforeach
                        </select>
                    </fieldset> 
                    <fieldset>
                        <legend><span id="required">*</span> SUB CATEGORY</legend>
                        <select class="js-example-basic-single" name="sub_category" id="menu_type_select5" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_subcategories as $category)
                                <option value="{{ $category->id }}">{{ $category->subcategory_description }}</option>
                            @endforeach
                        </select> 
                    </fieldset>   
                    <fieldset>
                        <legend>STATUS</legend>
                        <input type="text" name="status" value="ACTIVE" readonly id="success">
                    </fieldset> 
                </div>
            </div>
            <div class="panel-footer">
                <a href='http://127.0.0.1:8000/admin/add_menu_items' class='btn btn-default'>Cancel</a>
                <input type='submit' class='btn btn-primary pull-right' value='Add Menu' onclick=""/>
            </div>
        </form>
    </div>
</div>

<script>
    $('#menu_type_select1').select2({
        placeholder: "SELECT A MENU SEGMENTATION",
        allowClear: true
    });
    $('#menu_type_select2').select2({
        placeholder: "SELECT A CONCEPT",
        allowClear: true
    });
    $('#menu_type_select3').select2({
        placeholder: "SELECT A MENU TYPE",
        allowClear: true
    });
    $('#menu_type_select4').select2({
        placeholder: "SELECT A MAIN CATEGORY",
        allowClear: true
    });
    $('#menu_type_select5').select2({
        placeholder: "SELECT A SUB CATEGORY",
        allowClear: true
    });

    // function addBtn(event){
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //         }).then((result) => {
    //         if (result.isConfirmed) {
    //             $('#form-add').submit()
    //         }
    //     })
    // }
</script>
@endsection
