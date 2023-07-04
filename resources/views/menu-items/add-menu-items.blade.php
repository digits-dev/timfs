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
    .swal2-html-container {
        line-height: 3rem;
    }

    .swal2-popup, .swal2-modal, .swal2-icon-warning .swal2-show {
        font-size: 1.6rem !important;
    }

    .form-column{
    margin: 0 1vw;
    width: 33.3%;
    width: 100%
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

    /* #menu_type_select1, #menu_type_select2, 
    #menu_type_select3, #menu_type_select4, 
    #menu_type_select5, #menu_type_select6{
        width: 100%;
    } */


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
    <div class='panel-heading'>Add Menu Items</div>
    <form method="POST" action="{{$item->rnd_menu_items_id ? route('add_new_menu') : CRUDBooster::mainpath('add-save')}}" id="form-add" autocomplete="off">
        <div class='panel-body'>
            @csrf
            @if ($item->rnd_menu_items_id) 
            <input type="text" class="hidden" name="rnd_menu_items_id" value="{{$item->rnd_menu_items_id}}">
            @endif
            <div class="add-content">
                <div class="form-column">
                    @if (!$item->rnd_menu_items_id) 
                    <fieldset>
                        <legend> POS Old Item Code 1</legend>
                        <input type="text" name="pos_item_code_1" placeholder="Enter pos old item code 1" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <fieldset>
                        <legend> POS Old Item Code 2</legend>
                        <input type="text" name="pos_item_code_2" placeholder="Enter pos old item code 2" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <fieldset>
                        <legend> POS Old Item Code 3</legend>
                        <input type="text" name="pos_item_code_3" placeholder="Enter pos old item code 3" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <fieldset>
                        <legend> POS Old Description</legend>
                        <input type="text" name="pos_item_description" placeholder="Enter pos old item description" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    @endif
                    <fieldset>
                        <legend><span id="required">*</span> Menu Description</legend>
                        <input type="text" name="menu_item_description" value="{{$item->rnd_menu_description ? $item->rnd_menu_description : ''}}" placeholder="Enter menu description" required oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <fieldset>
                        <legend><span id="required">*</span> Product Type</legend>
                        <input type="text" name="product_type" placeholder="Enter a product type" required oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <fieldset>
                        <legend><span id="required">*</span> Menu Type</legend>
                        <select class="js-example-basic-single" name="menu_type" id="menu_type_select3" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_types as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->menu_type_description }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="main_category">
                        <legend><span id="required">*</span> Main Category</legend>
                        <select class="js-example-basic-single" name="menu_categories" id="menu_type_select4" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_description }}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="form-column menu_group" id="menu_group_start">
                    @for ($i=0; $i<count($menu_choices_group); $i++)
                        @if ($i < count($menu_choices_group)/2)
                            <fieldset class="choices_group">
                                <legend> {{ $menu_choices_group[$i]->menu_choice_group_column_description }}</legend>
                                <input class="group" type="text" name="choices_group_{{ $i+1 }}" placeholder="Enter choices group {{ $i+1 }}" oninput="this.value = this.value.toUpperCase()">
                            </fieldset>
                            <fieldset class="choices_group">
                                <legend> {{ $menu_choices_group[$i]->menu_choice_group_column_description }} SKU</legend>
                                <select class="js-example-basic-multiple sku group" name="choices_skugroup_{{ $i+1 }}[]" multiple="multiple" id="menu_type_select_sku{{ $i+1 }}" >
                                </select>
                            </fieldset>
                        @endif
                    @endfor
                </div>
                <div class="form-column menu_group">
                    @for ($i=0; $i<count($menu_choices_group); $i++)
                        @if ($i >= count($menu_choices_group)/2)
                            <fieldset class="choices_group">
                                <legend>{{ $menu_choices_group[$i]->menu_choice_group_column_description }}</legend>
                                <input class="group" type="text" name="choices_group_{{ $i+1 }}" placeholder="Enter choices group {{ $i+1 }}" oninput="this.value = this.value.toUpperCase()">
                            </fieldset>
                            <fieldset class="choices_group">
                                <legend>{{ $menu_choices_group[$i]->menu_choice_group_column_description }} SKU</legend>
                                <select class="js-example-basic-multiple sku group" name="choices_skugroup_{{ $i+1 }}[]" multiple="multiple" id="menu_type_select_sku{{ $i+1 }}" >
                                </select>
                            </fieldset>
                        @endif
                    @endfor
                </div>
                <div class="form-column">
                    <fieldset>
                        <legend><span id="required">*</span> Sub Category</legend>
                        <select class="js-example-basic-single" name="sub_category" id="menu_type_select5" required>
                            <option value="" selected disabled></option>
                            @foreach ($menu_subcategories as $category)
                                <option value="{{ $category->id }}">{{ $category->subcategory_description }}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                    @if (!$item->rnd_menu_items_id) 
                    <fieldset>
                        <legend><span id="required">*</span> Price - Dine In</legend>
                        <input type="number" name="price_dine_in" value="{{$item->rnd_menu_srp ? (float) $item->rnd_menu_srp : ''}}" placeholder="Enter price - dine in" required oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <fieldset>
                        <legend> Price - Delivery</legend>
                        <input type="number" name="price_delivery" placeholder="Leave blank if same as dine in" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>
                    <fieldset>
                        <legend> Price - Take Out</legend>
                        <input type="number" name="price_take_out" placeholder="Leave blank if same as dine in" oninput="this.value = this.value.toUpperCase()">
                    </fieldset>  
                    @endif
                    <fieldset>
                        <legend><span id="required">*</span> Original Concept</legend>
                        <select class="js-example-basic-multiple" name="original_concept[]" multiple="multiple" id="menu_type_select6" required>
                            @foreach ($segmentations as $segmentation)
                                <option {{$item->segmentations_id == $segmentation->id ? 'selected' : ''}} value="{{ $segmentation->id }}">{{ $segmentation->segment_column_description }}</option>
                            @endforeach
                        </select>
                    </fieldset> 
                    <fieldset>
                        <legend><span id="required">*</span> Store List</legend>
                        <select class="js-example-basic-multiple" name="menu_segment_column_description[]" multiple="multiple" id="menu_type_select1" required>
                            @foreach ($menu_segmentations as $concept)
                                <option value="{{ $concept->id }}">{{ $concept->menu_segment_column_description }}</option>
                            @endforeach
                        </select>
                    </fieldset>    
                    <fieldset>
                        <legend>Status</legend>
                        <input type="text" name="status" value="ACTIVE" readonly id="success">
                    </fieldset> 
                </div>
            </div>
            <button id="submit-button" type="submit" class="hide">Submit</button>
        </form>
        @if ($item->rnd_menu_items_id)
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
        @if ($item->rnd_menu_items_id)
        <button type="button" class="btn btn-primary pull-right save-btn"><i class="fa fa-save"></i> Save</button>
        <button class="btn btn-warning pull-right return-btn" type="button" _return_to="chef" style="margin-right: 10px;"><i class="fa fa-mail-reply" ></i> Return to Chef</button>
        @else
        <input type="submit" class='btn btn-primary pull-right add-menu' value='Add Menu' onclick=""/>
        @endif
    </div>
</div>

<script>
    const item = {!! json_encode($item) !!} || {};

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
    $('#menu_group_start').children().eq(0).children().first().prepend('<span id="required">*</span>');
    $('#menu_group_start').children().eq(1).children().first().prepend('<span id="required">*</span>');

    $('#menu_type_select3').change(function() {
        let menu_type = $('#menu_type_select3 option:selected').text();
        if(menu_type == 'MENU BUILD - CHOICES'){
            $('.choices_group').show();
            $('.menu_group').show();
            $('.form-column').css('margin', '0 1vw')

        }else{
            $('.choices_group').hide();
            $('.group').empty();
            $('.group').val();
            $('.menu_group').hide();
            $('.form-column').css('margin', '0 4vw')
            $('.choices_group input').val('');
            $('.choices_group select').val('');
            
        }
    });

    $('.add-menu[type="submit"]').on('click', function() {
        $('#submit-button').click();
    })

    @if ($item->rnd_menu_items_id)
    $(document).on('click', '.save-btn', function() {
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

    $('#form-add').submit(function(event) {
        const formData = $('#form-add').serialize();
        $.ajax({
            type: "POST",
            url: "{{ route('add_new_menu') }}",
            data: formData,
            dataType: "json",
            encode: true,
            success: function(response) {
                console.log(response);return;
                Swal.fire({
                    title: `✔️ New Menu Item Created!`,
                    html: '📄 Do you want to continue to Costing?',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Not now',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "{{ CRUDBooster::mainPath() }}" + `/edit/${item.rnd_menu_items_id}`;
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
                    .val("{{ $item->rnd_menu_items_id }}");

                form.append(csrf, actionInput, returnToInput, rndMenuItemsId);
                $('.panel-body').append(form);
                form.submit();
            }
        });
    });
    @endif


</script>
@endsection
