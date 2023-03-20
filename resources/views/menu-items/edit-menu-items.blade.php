<!-- First, extends to the CRUDBooster Layout -->
@push('head')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>

    .form-column{
    margin: 0 3vw;
    width: 33.3%;
        
    }

    .form-column fieldset{
        margin-bottom: 2vh;
        border: 1px solid #766b6b;
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
    #menu_type_select5, #status_select2{
        width: 100%;
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
    }

    .select2-container .select2-search--inline .select2-search__field{
        text-align: center !important;
    }

    .select2-container--default .select2-selection--single{
        border: none !important;
        text-align: center;
    }

</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Add Menu Item</a></p>
  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Menu Items</div>
    <div class='panel-body'>
        <form method="POST" action="{{CRUDBooster::mainpath('edit-save/'.$row->id)}}">
            @csrf
            <div class="add-content">
                {{-- First Column --}}
                <div class="form-column">
                    <label for="">TASTELESS MENU CODE</label>
                    <fieldset>
                        <input type="text" name="pos_item_code_1" readonly value="{{ $row->tasteless_menu_code }}">
                    </fieldset>
                    <label for="">POS OLD ITEM CODE 1</label>
                    <fieldset>
                        <input type="text" name="pos_item_code_1" value="{{ $row->old_code_1 }}">
                    </fieldset>
                    <label for="">POS OLD ITEM CODE 2</label>
                    <fieldset>
                        <input type="text" name="pos_item_code_2" value="{{ $row->old_code_2 }}">
                    </fieldset>
                    <label for="">POS OLD ITEM CODE 3</label>
                    <fieldset>
                        <input type="text" name="pos_item_code_3" value="{{ $row->old_code_3 }}">
                    </fieldset>
                    <label for="">POS OLD DESCRIPTION</label>
                    <fieldset>
                        <input type="text" name="pos_item_description" value="{{ $row->pos_old_item_description }}" >
                    </fieldset>
                    <label for=""><span id="required">*</span> MENU DESCRIPTION</label>
                    <fieldset>
                        <input type="text" name="menu_item_description" value="{{ $row->menu_item_description }}" required>
                    </fieldset>
                    <label for=""><span id="required">*</span> PRODUCT TYPE</label>
                    <fieldset>
                        <select class="js-example-basic-single" name="product_type" id="menu_type_select2" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_product_types as $product_type)
                                @if ($row->menu_product_types_id == $product_type->id)
                                    <option value="{{ $product_type->id }}" selected>{{ $product_type->menu_product_type_description }}</option>
                                    @else
                                    <option value="{{ $product_type->id }}">{{ $product_type->menu_product_type_description }}</option>
                                @endif
                            @endforeach
                        </select>
                    </fieldset>   
                </div>
                {{-- Second Column --}}
                <div class="form-column">
                    <label for="">CHOICES GROUP 1</label>
                    <fieldset>
                        <input type="text" name="choices_group_1"  value="{{ $row->choices_group_1 }}">
                    </fieldset>
                    <label for="">CHOICES GROUP 1 SKU</label>
                    <fieldset>
                        <input type="text" name="choices_skugroup_1" value="{{ $row->choices_skugroup_1 }}" >
                    </fieldset>
                    <label for="">CHOICES GROUP 2</label>
                    <fieldset>
                        <input type="text" name="choices_group_2" value="{{ $row->choices_group_2 }}">
                    </fieldset>
                    <label for="">CHOICES GROUP 2 SKU</label>
                    <fieldset>
                        <input type="text" name="choices_skugroup_2" value="{{ $row->choices_skugroup_2 }}" >
                    </fieldset>
                    <label for="">CHOICES GROUP 3</label>
                    <fieldset>
                        <input type="text" name="choices_group_3" value="{{ $row->choices_group_3 }}">
                    </fieldset>
                    <label for="">CHOICES GROUP 3 SKU</label>
                    <fieldset>
                        <input type="text" name="choices_skugroup_3" value="{{ $row->choices_skugroup_3 }}" >
                    </fieldset>  
                    <label for=""><span id="required">*</span> MENU TYPE</label>
                    <fieldset>
                        <select class="js-example-basic-single" name="menu_type" id="menu_type_select3" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_types as $menu)
                                @if ($row->menu_types_id == $menu->id)
                                    <option value="{{ $menu->id }}" selected>{{ $menu->menu_type_description }}</option>
                                @else
                                    <option value="{{ $menu->id }}">{{ $menu->menu_type_description }}</option>
                                @endif
                            @endforeach
                        </select>
                    </fieldset>
                    <label for=""><span id="required">*</span> MAIN CATEGORY</label>
                    <fieldset>
                        <select class="js-example-basic-single" name="menu_categories" id="menu_type_select4" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_categories as $category)
                                @if ($row->menu_categories_id == $category->id)
                                    <option value="{{ $category->id }}" selected>{{ $category->category_description }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->category_description }}</option>
                                @endif
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="form-column">
                    <label for=""><span id="required">*</span> SUB CATEGORY</label>
                    <fieldset>
                        <select class="js-example-basic-single" name="sub_category" id="menu_type_select5" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_subcategories as $category)
                                @if ($row->menu_subcategories_id == $category->id)
                                    <option value="{{ $category->id }}" selected>{{ $category->subcategory_description }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->subcategory_description }}</option>
                                @endif
                            @endforeach
                        </select> 
                    </fieldset>
                    <label for="">PRICE - DELIVERY</label>
                    <fieldset>
                        <input type="number" name="price_delivery" value="{{ $row->menu_price_dlv }}">
                    </fieldset>
                    <label for=""><span id="required">*</span> PRICE - DINE IN</label>
                    <fieldset>
                        <input type="number" name="price_dine_in" value="{{ $row->menu_price_dine }}" required>
                    </fieldset>
                    <label for="">PRICE - TAKE OUT</label>
                    <fieldset>
                        <input type="number" name="price_take_out" value="{{ $row->menu_price_take }}">
                    </fieldset>
                    <label for=""><span id="required">*</span> ORIGINAL CONCEPT</label>
                    <fieldset>
                        <input type="text" name="original_concept" value="{{ $row->original_concept }}" required>
                        </select>
                    </fieldset>
                    <label for=""><span id="required">*</span> MENU SEGMENTATION</label>
                    <fieldset>
                        <select class="js-example-basic-multiple" name="menu_segment_column_description[]" multiple="multiple" id="menu_type_select1" required>
                            @foreach ($menu_segmentations as $concept)
                                @if (in_array($concept->menu_segment_column_name, $user_menu_segment))
                                    <option value="{{ $concept->id }}" selected>{{ $concept->menu_segment_column_description }}</option>
                                @else
                                    <option value="{{ $concept->id }}" >{{ $concept->menu_segment_column_description }}</option>
                                @endif
                            @endforeach
                        </select>
                    </fieldset>
                    <label for="">STATUS</label>
                    <fieldset>
                        <select class="js-example-basic-single" name="status" id="status_select2" required>
                            <option value="ACTIVE" {{ $row->status == 'ACTIVE' ? 'selected':'' }}>ACTIVE</option>
                            <option value="INACTIVE" {{ $row->status == 'INACTIVE' ? 'selected':'' }}>INACTIVE</option>
                        </select>
                    </fieldset>
                </div>
            </div>
            <div class="panel-footer">
                <a href='http://127.0.0.1:8000/admin/add_menu_items' class='btn btn-default'>Cancel</a>
                <input type='submit' class='btn btn-primary pull-right' value='Save Changes'/>
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
    $('#status_select2').select2()

</script>
@endsection
