<!-- First, extends to the CRUDBooster Layout -->
@push('head')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
    .swal2-html-container {
        line-height: 3rem;
    }

    .swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
        font-size: 1.6rem !important;
    }

    .form-column{
    margin: 0 3vw;
    width: 100%;
        
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

    .form-column label{
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
  <!-- Your html goes here -->
  <p class="noprint">
    <a title='Return' href="{{ CRUDBooster::mainPath() }}">
        <i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}
    </a>
  </p>
  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Menu Items</div>
    <div class='panel-body'>
        <form method="POST" action="{{$table == 'menu_items' ? route('menu_item_submit_menu_data') : route('edit_new_menu', ['id' => $row->id])}}" id="form-edit" autocomplete="off">
            @csrf
            @if ($rnd_menu_items_id)
            <input type="text" class="hidden" name="rnd_menu_items_id" value="{{$rnd_menu_items_id}}">
            @endif
            <div class="add-content">
                <div class="form-column">
                    @if (!$rnd_menu_items_id)
                    <label> Tasteless Menu Code</label>
                    <fieldset>
                        <input type="text" name="tasteless_menu_code" value="{{ $row->tasteless_menu_code }}" readonly>
                    </fieldset>
                    @foreach ($old_codes as $old_code)
                        <label>{{ $old_code->menu_old_code_column_description }}</label>
                        <fieldset>
                            <input type="text" name="{{ $old_code->menu_old_code_column_name }}" placeholder="Enter {{ $old_code->menu_old_code_column_description }}" value="{{ $row->{$old_code->menu_old_code_column_name} }}" oninput="this.value = this.value.toUpperCase()">
                        </fieldset>
                    @endforeach
                    <label> POS Old Description</label>
                    <fieldset>
                        <input type="text" name="pos_item_description" placeholder="Enter pos old item description" value="{{ $row->pos_old_item_description }}" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    @endif
                    <label><span id="required">*</span> Menu Description</label>
                    <fieldset>
                        <input type="text" name="menu_item_description" placeholder="Enter menu description" required value="{{ $row->menu_item_description }}" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <label><span id="required">*</span> Product Type</label>
                    <fieldset>
                        <input type="text" name="product_type" placeholder="Enter a product type" required value="{{ $row->menu_product_types_name }}" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <label><span id="required">*</span> Menu Type</label>
                    <fieldset>
                        <select class="js-example-basic-single" name="menu_type" id="menu_type_select3" required>
                            @foreach ($menu_types as $menu)
                                @if ($row->menu_types_id == $menu->id)
                                    <option value="{{ $menu->id }}" selected>{{ $menu->menu_type_description }}</option>
                                @else
                                    <option value="{{ $menu->id }}">{{ $menu->menu_type_description }}</option>
                                @endif
                            @endforeach
                        </select>
                    </fieldset>
                    <label><span id="required">*</span> Main Category</label>
                    <fieldset class="main_category">
                        <select class="js-example-basic-single" name="menu_categories" id="menu_type_select4" required>
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
                <div class="form-column menu_group" id="menu_group_start">
                    @for ($i=0; $i<count($menu_choices_group); $i++)
                        @if ($i < count($menu_choices_group)/2)
                            @php
                                $choices_group_less = 'choices_group_'.strval($i+1);
                                $choices_skugroup_less = 'choices_skugroup_'.strval($i+1);                   
                            @endphp  
                            <label>{{ ucwords(strtolower($menu_choices_group[$i]->menu_choice_group_column_description)) }}</label>
                            <fieldset class="choices_group">
                                <input class="group" type="text" name="choices_group_{{ $i+1 }}" id="input_type_group_{{ $i+1 }}" placeholder="Enter choices group {{ $i+1 }}" value="{{ $row->$choices_group_less }}" oninput="this.value = this.value.toUpperCase()">
                            </fieldset>
                            <label> {{ ucwords(strtolower($menu_choices_group[$i]->menu_choice_group_column_description)) }} SKU</label>
                            <fieldset class="choices_group">
                                <select class="js-example-basic-multiple sku group" name="choices_skugroup_{{ $i+1 }}[]" multiple="multiple" id="menu_type_select_sku{{ $i+1 }}">
                                    @php
                                        $choices_skugroup = DB::table('menu_items')->where('id',$row->id)->get()->first();
                                        $list_of_sku_group = explode((', '),$choices_skugroup->$choices_skugroup_less);
                                        foreach($list_of_sku_group as $value){
                                            $menu_desc = DB::table('menu_items')->where('tasteless_menu_code', $value)->get()->first();
                                            if($value != null){
                                                echo "<option value='".$menu_desc->tasteless_menu_code."'"." selected='selected'".">".$menu_desc->menu_item_description."</option>";
                                            }
                                        }
                                    @endphp
                                </select>
                            </fieldset>
                        @endif
                    @endfor
                </div>
                <div class="form-column menu_group">
                    @for ($i=0; $i<count($menu_choices_group); $i++)
                        @if ($i >= count($menu_choices_group)/2)
                            @php
                                $choices_group_greater = 'choices_group_'.strval($i+1);
                                $choices_skugroup_greater = 'choices_skugroup_'.strval($i+1);
                            @endphp
                            <label>{{ ucwords(strtolower($menu_choices_group[$i]->menu_choice_group_column_description)) }}</label>
                            <fieldset class="choices_group">
                                <input class="group" type="text" name="choices_group_{{ $i+1 }}" placeholder="Enter choices group {{ $i+1 }}" value="{{ $row->$choices_group_greater }}" oninput="this.value = this.value.toUpperCase()">
                            </fieldset>
                            <label>{{ ucwords(strtolower($menu_choices_group[$i]->menu_choice_group_column_description)) }} SKU</label>
                            <fieldset class="choices_group">
                                <select class="js-example-basic-multiple sku group" name="choices_skugroup_{{ $i+1 }}[]" multiple="multiple" id="menu_type_select_sku{{ $i+1 }}" >
                                @php
                                    $choices_skugroup_row = DB::table('menu_items')->where('id',$row->id)->get()->first();
                                    $list_of_sku_group_greater = explode((', '),$choices_skugroup->$choices_skugroup_greater);
                                    foreach($list_of_sku_group_greater as $value){
                                        $menu_desc = DB::table('menu_items')->where('tasteless_menu_code', $value)->get()->first();
                                        if($value != null){
                                            echo "<option value='".$menu_desc->tasteless_menu_code."'"." selected='selected'".">".$menu_desc->menu_item_description."</option>";
                                        }
                                    }
                                @endphp
                                </select>
                            </fieldset>
                        @endif
                    @endfor
                </div>
                <div class="form-column">
                    <label><span id="required">*</span> Sub Category</label>
                    <fieldset>
                        <span>{{ $row->$menu_subcategories_id  }}</span>
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
                    <label><span id="required">*</span> Original Concept</label>
                    <fieldset>
                        <select class="js-example-basic-multiple" name="original_concept[]" multiple="multiple" id="menu_type_select6" required>
                            @foreach ($segmentations as $segmentation)
                                <option {{in_array($segmentation->id, explode(',', $row->segmentations_id)) ? 'selected' : ''}} value="{{ $segmentation->id }}">{{ $segmentation->segment_column_description }}</option>
                            @endforeach
                        </select>
                    </fieldset> 
                    <label><span id="required">*</span> Store List</label>
                    <fieldset>
                        <select class="js-example-basic-multiple" name="menu_segment_column_description[]" multiple="multiple" id="menu_type_select1" required>
                            @foreach ($menu_segmentations as $concept)
                                @if(in_array($concept->id, $store_list_id))
                                    <option value="{{ $concept->id }}" selected>{{ $concept->menu_segment_column_description }}</option>
                                @else
                                    <option value="{{ $concept->id }}">{{ $concept->menu_segment_column_description }}</option>
                                @endif
                            @endforeach
                        </select>
                    </fieldset>    
                    <label>Status</label>
                    <fieldset>
                        <select class="js-example-basic-single" name="status" id="status_select2" required>
                            <option value="ACTIVE" {{ $row->status == 'ACTIVE' ? 'selected':'' }}>ACTIVE</option>
                            <option value="INACTIVE" {{ $row->status == 'INACTIVE' ? 'selected':'' }} {{ $rnd_menu_items_id ? 'disabled' : '' }}>INACTIVE</option>
                        </select>
                    </fieldset>
                </div>
            </div>
            <button id="submit-button" type="submit" class="hide">Submit</button>
        </form>
        @if ($rnd_menu_items_id)
        <div class="row">
            <div class="col-md-6">
                <hr>
                <h4 class="text-center">Comments</h4>
                @include('rnd-menu/chat-app', $comments_data)
            </div>
        </div>
        @endif
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
        @if ($rnd_menu_items_id)
        <button type="button" class="btn btn-primary pull-right save-btn" id="save-edit-rnd"><i class="fa fa-save"></i> Save</button>
        <button class="btn btn-warning pull-right return-btn" type="button" _return_to="chef" style="margin-right: 10px;"><i class="fa fa-mail-reply" ></i> Return to Chef</button>
        @else
        <button type="submit" class="actual-submit-btn hide">submit</button>
        <button type="button" id="save-edit-menu" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save</button>
        @endif
    </div>
</div>

<script>
const table = {!! json_encode($table) !!};

$(document).on('click', '#save-edit-menu', function() {
    Swal.fire({
        title: 'Do you want to save the changes?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        returnFocus: false,
    }).then((result) => {
        if (result.isConfirmed) {
            $('#submit-button').click();
        }
    });

    $('.actual-submit-btn').click();
});

$('#menu_type_select1').select2({
        placeholder: "Select stores",
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%'
    });
    $('#menu_type_select2').select2({
        placeholder: "Select a concept",
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%'
    });
    $('#menu_type_select3').select2({
        placeholder: "Select a menu type",
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%'
    });
    $('#menu_type_select4').select2({
        placeholder: "Select a main category",
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%'
    });
    $('#menu_type_select5').select2({
        placeholder: "Select a sub category",
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%'

    });
    $('#menu_type_select6').select2({
        placeholder: "Select concepts",
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%'

    });

    $('#status_select2').select2({
        allowClear: true,
        dropdownAutoWidth: true,
        width: '100%' 
    })

    // Choices Group SKU 1
    $('.sku').select2({
        placeholder: "Select a choice group",
        // dropdownAutoWidth: true,
        width: '100%',
        ajax: {
            url: '{{ url('/add_menu_items') }}',
            dataType: 'json',
            delay: 250,
            type: 'POST',
            data: function (params) {
            return {
                q: params.term,
                _token: '{!! csrf_token() !!}'
            };
            },
            processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                console.log(item.tasteless_menu_code)
                return {
                    text: item.menu_item_description,
                    id: item.tasteless_menu_code
                }
                })
            };
            },
            cache: true
        },
        id: 'id'
        // minimumInputLength: 2
    });

    // Menu Type
    $('.menu_group').hide();
    $('.choices_group').hide();
    $('.form-column').css('margin', '0 4vw')
    $('#menu_group_start').children().eq(0).prepend('<span id="required">* </span>');
    $('#menu_group_start').children().eq(2).prepend('<span id="required">*</span>');

    if($('#menu_type_select3 option:selected').text() == 'PROMO'){
        $('#menu_type_select3').attr('disabled', true);
    }

    // Menu Type - Menu Build choices
    if($('#menu_type_select3 option:selected').text() == 'MENU BUILD - CHOICES'){
        $('.choices_group').show();
        $('.menu_group').show();
        $('.form-column').css('margin', '0 1vw')
        $('#input_type_group_1').attr('required', true);
        $('#menu_type_select_sku1').attr('required', true)
        $('#menu_type_select3 option:contains("PROMO")').prop('disabled', true);
    }else{
        $('#menu_type_select3 option:contains("PROMO")').prop('disabled', true);
    }

    $('#menu_type_select3').change(function() {
        let menu_type = $('#menu_type_select3 option:selected').text();
        if(menu_type == 'MENU BUILD - CHOICES'){
            $('.choices_group').show();
            $('.menu_group').show();
            $('.form-column').css('margin', '0 1vw')
            $('#input_type_group_1').attr('required', true);
            $('#menu_type_select_sku1').attr('required', true)

        }else{
            $('.choices_group').hide();
            $('.group').empty();
            $('.group').val();
            $('.menu_group').hide();
            $('.form-column').css('margin', '0 4vw')
            $('.choices_group input').val('');
            $('.choices_group select').val('');
            $('#input_type_group_1').attr('required', false);
            $('#menu_type_select_sku1').attr('required', false)
        }
    }); 

    $('#save-edit-btn').on('click', function() {
        $('#submit-button').click();
    });

    @if ($rnd_menu_items_id)
        $(document).on('click', '#save-edit-rnd', function() {
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
                    $('#submit-button').click();
                }
            });
        });

        $('#form-edit').submit(function(event) {
            const formData = $('#form-edit').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('edit_new_menu', ['id' => $row->id]) }}",
                data: formData,
                dataType: "json",
                encode: true,
                success: function(response) {
                    Swal.fire({
                        title: `✔️ New Menu Item Updated!`,
                        html: '📄 Do you want to continue to Costing?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Not now',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.href = "{{ CRUDBooster::mainPath() }}" + '/edit/' + "{{ $rnd_menu_items_id }}";
                        } else {
                            location.href = "{{ CRUDBooster::mainPath() }}";
                        }
                    });
                },
                error: function(response) { 
                    console.log(response);
                    Swal.fire({
                        title: 'Oops',
                        html: 'Something went wrong.',
                        icon: 'error'
                    });
                } 
            });

            event.preventDefault();
        });

        $(document).on('click', '.return-btn', function() {
        const returnTo = $(this).attr('_return_to');
        const action = 'return';
        Swal.fire({
            title: `Do you want to return this item?`,
            html: `🟠 Doing so will return this item to <label class="label label-warning">${returnTo.toUpperCase()}</label>.` +
                `<br/> ⚠️ You won't be able to revert this.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $(document.createElement('form'))
                    .attr('method', 'POST')
                    .attr('action', "{{ route('return_rnd_menu') }}")
                    .hide();

                const csrf = $(document.createElement('input'))
                    .attr('name', '_token')
                    .val("{{csrf_token()}}");

                const actionInput = $(document.createElement('input'))
                    .attr('name','action')
                    .val('return');

                const returnToInput = $(document.createElement('input'))
                    .attr('name', 'return_to')
                    .val(returnTo)

                const rndMenuItemsId = $(document.createElement('input'))
                    .attr('name', 'rnd_menu_items_id')
                    .val("{{ $rnd_menu_items_id }}");

                form.append(csrf, actionInput, returnToInput, rndMenuItemsId);
                $('.panel-body').append(form);
                form.submit();
            }
        });
    });
    @endif

</script>
@endsection
