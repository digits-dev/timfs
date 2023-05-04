@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<link rel="stylesheet" href="{{asset('css/edit-rnd-menu.css')}}">
@endpush
@extends('crudbooster::admin_template')
@section('content')


{{-- 
    A COPY OF INGREDIENT ENTRY!!! FOR CLONING!!
    THIS IS HIDDEN FROM THE DOM!!! --> {display: none}
--}}

{{-- FOR INGREDIENTS !!!!! --}}

<div class="ingredient-wrapper" style="display: none;">
    <div class="ingredient-entry" isExisting="true">
        <div class="ingredient-inputs">
            <label class="ingredient-label">
                <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
                <div>
                    <input value="" type="text" class="ingredient form-control" required/>
                    <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                    <div class="item-list">
                    </div>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Preparation Qty
                <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient UOM
                <div>
                    <input type="text" class="form-control uom" value="" style="display: none;"/>
                    <input type="text" class="form-control display-uom" value="" readonly>
                </div>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Preparation
                <select class="form-control preparation" disabled>
                    @foreach ($preparations as $preparation)
                    <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Yield %
                <input value="" class="form-control yield" type="number" readonly required>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> TTP <span class="date-updated"></span>
                <input value="" class="form-control ttp" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Qty
                <input value="" class="form-control ing-quantity" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Cost
                <input value="" class="form-control cost" type="text" readonly required>
            </label>
        </div>
        <div class="actions">
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
</div>

<div class="new-ingredient-wrapper" style="display: none;">
    <div class="ingredient-entry" isExisting="false">
        <div class="ingredient-inputs">
            <label class="ingredient-label">
                <span class="required-star">*</span> Ingredient <span class="item-from label label-secondary">USER</span>
                <div>
                    <input value="" type="text" class="ingredient-name form-control" required/>
                    <div class="item-list">
                    </div>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Packaging Size
                <input value="" class="form-control pack-size" type="number" required>
            </label>
            <label>
                <span class="required-star">*</span> Preparation Qty
                <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient UOM
                <select class="form-control uom">
                    @foreach ($uoms as $uom)
                    <option {{$uom->packaging_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->packaging_description}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Preparation
                <select class="form-control preparation">
                    @foreach ($preparations as $preparation)
                    <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Yield %
                <input value="" class="form-control yield" type="number" readonly required>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> TTP <span class="date-updated"></span>
                <input value="" class="form-control ttp" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Qty
                <input value="" class="form-control ing-quantity" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Ingredient Cost
                <input value="" class="form-control cost" type="text" readonly required>
            </label>
        </div>
        <div class="actions">
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
</div>

<div class="substitute-ingredient" style="display: none;" isExisting="true">
    <div class="ingredient-inputs">
        <label class="ingredient-label">
            <span class="required-star">*</span> Ingredient <span class="item-from label"></span> <span class="label label-danger"></span>
            <div>
                <input value="" type="text" class="ingredient form-control" required/>
                <input value="" type="text" class="form-control display-ingredient span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Preparation Qty
            <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient UOM
            <div>
                <input type="text" class="form-control uom" value="" style="display: none;"/>
                <input type="text" class="form-control display-uom" value="" readonly>
            </div>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Preparation
            <select class="form-control preparation" disabled>
                @foreach ($preparations as $preparation)
                <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Yield %
            <input value="" class="form-control yield" type="number" readonly required>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> TTP <span class="date-updated"></span>
            <input value="" class="form-control ttp" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Qty
            <input value="" class="form-control ing-quantity" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Cost
            <input value="" class="form-control cost" type="text" readonly required>
        </label>
    </div>
    <div class="actions">
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
    </div>
</div> 

<div class="new-substitute-ingredient" style="display: none;" isExisting="false">
    <div class="ingredient-inputs">
        <label class="ingredient-label">
            <span class="required-star">*</span> Ingredient <span class="item-from label label-secondary">USER</span>
            <div>
                <input value="" type="text" class="ingredient-name form-control" required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Packaging Size
            <input value="" class="form-control pack-size" type="number" required>
        </label>
        <label>
            <span class="required-star">*</span> Preparation Qty
            <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient UOM
            <select class="form-control uom">
                @foreach ($uoms as $uom)
                <option {{$uom->packaging_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->packaging_description}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Preparation
            <select class="form-control preparation">
                @foreach ($preparations as $preparation)
                <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Yield %
            <input value="" class="form-control yield" type="number" readonly required>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> TTP <span class="date-updated"></span>
            <input value="" class="form-control ttp" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Qty
            <input value="" class="form-control ing-quantity" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Ingredient Cost
            <input value="" class="form-control cost" type="text" readonly required>
        </label>
    </div>
    <div class="actions">
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
    </div>
</div> 

{{-- FOR PACKAGING --}}

<div class="packaging-wrapper" style="display: none;">
    <div class="packaging-entry" isExisting="true">
        <div class="packaging-inputs">
            <label class="packaging-label">
                <span class="required-star">*</span> Packaging <span class="item-from label"></span> <span class="label label-danger"></span>
                <div>
                    <input value="" type="text" class="packaging form-control hidden" required/>
                    <input value="" type="text" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                    <div class="item-list">
                    </div>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Preparation Qty
                <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
            </label>
            <label>
                <span class="required-star">*</span> UOM
                <div>
                    <input type="text" class="form-control uom" name="uom[]" value="" style="display: none;"/>
                    <input type="text" class="form-control display-uom" value="" readonly>
                </div>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Preparation
                <select class="form-control preparation" disabled>
                    @foreach ($preparations as $preparation)
                    <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Yield %
                <input value="" class="form-control yield" type="number" readonly required>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> TTP <span class="date-updated"></span>
                <input value="" class="form-control ttp" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Packaging Qty
                <input value="" class="form-control pack-quantity" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Cost
                <input value="" class="form-control cost" type="text" readonly required>
            </label>
        </div>
        <div class="actions">
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Packaging">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Packaging">
        <i class="fa fa-plus"></i>
    </div>
</div>

<div class="new-packaging-wrapper" style="display: none;">
    <div class="packaging-entry" isExisting="false">
        <div class="packaging-inputs">
            <label class="packaging-label">
                <span class="required-star">*</span> Packaging <span class="item-from label label-secondary">USER</span>
                <div>
                    <input value="" type="text" class="packaging-name form-control" required/>
                    <div class="item-list">
                    </div>
                </div>
            </label>
            <label>
                <span class="required-star">*</span> Packaging Size
                <input value="" class="form-control pack-size" type="number" required>
            </label>
            <label>
                <span class="required-star">*</span> Preparation Qty
                <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
            </label>
            <label>
                <span class="required-star">*</span> UOM
                <select class="form-control uom">
                    @foreach ($uoms as $uom)
                    <option {{$uom->packaging_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->packaging_description}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Preparation
                <select class="form-control preparation">
                    @foreach ($preparations as $preparation)
                    <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                    @endforeach
                </select>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> Yield %
                <input value="" class="form-control yield" type="number" readonly required>
            </label>
            <label class="label-wide">
                <span class="required-star">*</span> TTP <span class="date-updated"></span>
                <input value="" class="form-control ttp" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Packaging Qty
                <input value="" class="form-control pack-quantity" type="number" readonly required>
            </label>
            <label>
                <span class="required-star">*</span> Cost
                <input value="" class="form-control cost" type="text" readonly required>
            </label>
        </div>
        <div class="actions">
            <button class="btn btn-info move-up" title="Move Up" type="button"> <i class="fa fa-arrow-up" ></i></button>
            <button class="btn btn-info move-down" title="Move Down" type="button"> <i class="fa fa-arrow-down" ></i></button>
            <button class="btn btn-danger delete" title="Delete Ingredient" type="button"> <i class="fa fa-trash" ></i></button>
        </div>
    </div>
    <div class="add-sub-btn" title="Add Existing Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
    <div class="new-add-sub-btn" title="Add New Substitute Ingredient">
        <i class="fa fa-plus"></i>
    </div>
</div>

<div class="substitute-packaging" style="display: none;" isExisting="true">
    <div class="packaging-inputs">
        <label class="packaging-label">
            <span class="required-star">*</span> Packaging <span class="item-from label"></span> <span class="label label-danger"></span>
            <div>
                <input value="" type="text" class="packaging form-control hidden" required/>
                <input value="" type="text" class="form-control display-packaging span-2" placeholder="Search by Item Desc, Brand or Item Code" required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Preparation Qty
            <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> UOM
            <div>
                <input type="text" class="form-control uom" name="uom[]" value="" style="display: none;"/>
                <input type="text" class="form-control display-uom" value="" readonly>
            </div>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Preparation
            <select class="form-control preparation" disabled>
                @foreach ($preparations as $preparation)
                <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Yield %
            <input value="" class="form-control yield" type="number" readonly required>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> TTP <span class="date-updated"></span>
            <input value="" class="form-control ttp" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Packaging Qty
            <input value="" class="form-control pack-quantity" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Cost
            <input value="" class="form-control cost" type="text" readonly required>
        </label>
    </div>
    <div class="actions">
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
    </div>
</div> 

<div class="new-substitute-packaging" style="display: none;" isExisting="false">
    <div class="packaging-inputs">
        <label class="packaging-label">
            <span class="required-star">*</span> Packaging <span class="item-from label label-secondary">USER</span>
            <div>
                <input value="" type="text" class="packaging-name form-control" required/>
                <div class="item-list">
                </div>
            </div>
        </label>
        <label>
            <span class="required-star">*</span> Packaging Size
            <input value="" class="form-control pack-size" type="number" required>
        </label>
        <label>
            <span class="required-star">*</span> Preparation Qty
            <input value="" class="form-control prep-quantity" type="number" min="0" step="any" readonly required/>
        </label>
        <label>
            <span class="required-star">*</span> UOM
            <select class="form-control uom">
                @foreach ($uoms as $uom)
                <option {{$uom->packaging_description == 'GRM (GRM)' ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->packaging_description}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Preparation
            <select class="form-control preparation">
                @foreach ($preparations as $preparation)
                <option {{$preparation->preparation_desc == 'NONE' ? 'selected' : ''}} value="{{$preparation->id}}">{{$preparation->preparation_desc}}</option>
                @endforeach
            </select>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> Yield %
            <input value="" class="form-control yield" type="number" readonly required>
        </label>
        <label class="label-wide">
            <span class="required-star">*</span> TTP <span class="date-updated"></span>
            <input value="" class="form-control ttp" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Packaging Qty
            <input value="" class="form-control pack-quantity" type="number" readonly required>
        </label>
        <label>
            <span class="required-star">*</span> Cost
            <input value="" class="form-control cost" type="text" readonly required>
        </label>
    </div>
    <div class="actions">
        <button class="btn btn-info set-primary" title="Set Primary Ingredient" type="button"> <i class="fa fa-star" ></i></button>
        <button class="btn btn-danger delete-sub" title="Delete Ingredient" type="button"> <i class="fa fa-minus" ></i></button>
    </div>
</div> 


{{-- END OF COPY --}}

 {{-- DOM STARTS HERE !!!! --}}

<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data RND Menu Items
    </a>
</p>

<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil"></i><strong> {{str_replace('get', '', CRUDBooster::getCurrentMethod())}} RND Menu Item</strong>
    </div>
    <div class="panel-body">
        <form action="" id="form-ingredient" class="form">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="control-label"><span class="required-star">*</span> RND Temporary Menu Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_menu_description : ''}}" type="text" class="form-control rnd_menu_description" placeholder="RND Temporary Menu Description">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label"><span class="required-star">*</span> RND Menu SRP</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="custom-icon"><strong>₱</strong></span>
                            </div>
                            <input value="{{$item ? (float) $item->rnd_menu_srp : ''}}" type="number" class="form-control rnd_menu_srp" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu Item Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_code : ''}}" type="text" class="form-control rnd_code" placeholder="RND-XXXXX" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Tasteless Menu Code</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->menu_items_code : ''}}" type="text" class="form-control rnd_tasteless_code" placeholder="XXXXXX" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">Total Cost</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="custom-icon"><strong>₱</strong></span>
                            </div>
                            <input type="text" class="form-control total-cost" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b>Ingredients</b></h3>
                    </div>
                </div>
            </div>
            <section class="ingredient-section">
                <div class="no-ingredient-warning text-center">
                    No ingredients currently saved...
                </div>
            </section>
            <section class="section-footer">
                <div class="add-buttons">
                    <button class="btn btn-primary" id="add-existing-ingredient" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add existing ingredient</button>
                    <button class="btn btn-success" id="add-new-ingredient" name="button" type="button" value="add_ingredient"> <i class="fa fa-plus" ></i> Add new ingredient</button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="control-label"><span class="required-star">*</span> Portion Size</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="custom-icon"><strong>÷</strong></span>
                                </div>
                                <input value="{{$item ? (float) $item->portion_size : '1'}}" type="number" class="form-control portion" placeholder="Portion Size">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" style="display: none;">
                        <div class="form-group">
                            <label for="" class="control-label">Total Ingredient Cost</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <input type="text" class="form-control ingredient-total-cost" placeholder="Total Cost" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="control-label">Food Cost (<span class="percentage"></span>)</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="custom-icon"><strong>₱</strong></span>
                                </div>
                                <input type="text" class="form-control food-cost" placeholder="Food Cost" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
        <hr>
        <form action="" id="form-packaging">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header text-center">
                        <h3 class="box-title"><b>Packaging</b></h3>
                    </div>
                </div>
            </div>
            <section class="packaging-section">
                <div class="no-packaging-warning text-center">
                    No packaging currently saved...
                </div>
            </section>
            <section class="section-footer">
                <div class="add-buttons">
                    <button class="btn btn-primary" id="add-existing-packaging" name="button" type="button"> <i class="fa fa-plus" ></i> Add existing packaging</button>
                    <button class="btn btn-success" id="add-new-packaging" name="button" type="button"> <i class="fa fa-plus" ></i> Add new packaging</button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="" class="control-label">Packaging Cost</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="custom-icon"><strong>₱</strong></span>
                                </div>
                                <input type="text" class="form-control packaging-cost" placeholder="Packaging Cost" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
        @if ($action == 'edit' || $action == 'add')
		<button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save" ></i> Save</button>
		<button class="btn btn-success pull-right" id="publish-btn" style="margin-right: 10px;"><i class="fa fa-upload" ></i> Publish</button>
        @elseif ($action == 'approve')
        <button class="btn btn-success pull-right" id="approve-btn" style="margin-right: 10px;"><i class="fa fa-thumbs-up" ></i> Approve</button>
        <button class="btn btn-danger pull-right" id="reject-btn" style="margin-right: 10px;"><i class="fa fa-thumbs-down" ></i> Reject</button>
        @endif
    </div>
</div>

@endsection

@push('bottom')

<script>
    document.title = 'Add New RND Menu Item Description';
    $('body').addClass('sidebar-collapse');
    const savedIngredients = {!! json_encode($ingredients) !!} || [];
    const savedPackagings = {!! json_encode($packagings) !!} || [];
    const rndMenuItem = {!! json_encode($item) !!};
    const action = "{{$action}}";
    const privilege = "{{$privilege}}";
    const addButtonsId = '#add-existing-ingredient, #add-new-ingredient, #add-existing-packaging, #add-new-packaging';
    $(document).ready(function() {

        const debounce = (func, wait, immediate)=> {
            let timeout;

            return function executedFunction() {
                const context = this;
                const args = arguments;    
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            }
        }

        $.fn.firstLoad = function() {
            if (savedIngredients) $('.no-ingredient-warning').remove();
            if (savedPackagings) $('.no-packaging-warning').remove();

            const ingredientGroupCount = [...new Set([...savedIngredients.map(e => e.ingredient_group)])];
            const ingredientSection = $('.ingredient-section');
            const packagingGroupCount = [...new Set([...savedPackagings.map(e => e.packaging_group)])];
            const packagingSection = $('.packaging-section');
            //looping through saved ingredients
            for (i of ingredientGroupCount) {
                const groupedIngredients = savedIngredients.filter(e => e.ingredient_group == i);
                const wrapperTemplate = $(document.createElement('div'));
                wrapperTemplate
                    .addClass('ingredient-wrapper')
                    .append($('.add-sub-btn').eq(0).clone())
                    .append($('.new-add-sub-btn').eq(0).clone());

                groupedIngredients.forEach(savedIngredient => {
                    let element;
                    if (savedIngredient.is_primary == 'TRUE') {
                        if (savedIngredient.is_existing == 'TRUE') {
                            //primary and existing
                            element = $('.ingredient-wrapper .ingredient-entry').eq(0).clone();
                        } else {
                            //primary and new
                            element = $('.new-ingredient-wrapper .ingredient-entry').eq(0).clone();
                            element.find('.ttp').attr('readonly', false);
                        }
                    } else {
                        if (savedIngredient.is_existing == 'TRUE') {
                            //substitute and existing
                            element = $('.substitute-ingredient').eq(0).clone();
                            if (savedIngredient.is_selected == 'TRUE') element.attr('primary', true);
                        } else {
                            //substitute and new
                            element = $('.new-substitute-ingredient').eq(0).clone();
                            if (savedIngredient.is_selected == 'TRUE') element.attr('primary', true);
                        }
                    }
                    if (savedIngredient.menu_status == 'INACTIVE' || savedIngredient.item_status == 'INACTIVE') 
                        element.find('.label-danger').text('⚠️INACTIVE');

                    if (savedIngredient.item_masters_id && !savedIngredient.menu_as_ingredient_id)
                        element.find('.item-from').addClass('label label-info').text('IMFS');

                    if (savedIngredient.menu_as_ingredient_id && !savedIngredient.item_masters_id)
                        element.find('.item-from').addClass('label-warning').text('MIMF');

                    const ingredientInput = element.find('.ingredient');
                    ingredientInput.val(savedIngredient.item_masters_id || savedIngredient.menu_as_ingredient_id);
                    ingredientInput.attr({
                        cost: savedIngredient.ingredient_cost || savedIngredient.food_cost,
                        uom: savedIngredient.packagings_id,
                        item_id: savedIngredient.item_masters_id,
                        menu_item_id: savedIngredient.menu_as_ingredient_id,
                    });

                    if (savedIngredient.item_masters_id) element.find('.date-updated').html(
                        savedIngredient.updated_at ? `${timeago.format(savedIngredient.updated_at)}` :
                        savedIngredient.created_at ? `${timeago.format(savedIngredient.created_at)}` :
                        ''
                    );
                    element.find('.display-ingredient').val(savedIngredient.full_item_description || savedIngredient.menu_item_description);
                    element.find('.ingredient-name').val(savedIngredient.ingredient_name);
                    element.find('.pack-size').val(parseFloat(savedIngredient.packaging_size));
                    element.find('.prep-quantity').val(parseFloat(savedIngredient.prep_qty) || 0).attr('readonly', false);
                    element.find('.uom').val(savedIngredient.packagings_id);
                    element.find('.uom_name').val(savedIngredient.uom_name);
                    element.find('.display-uom').val(savedIngredient.packaging_description);
                    element.find('.preparation option').attr('selected', false);
                    element.find('.preparation').val(savedIngredient.menu_ingredients_preparations_id)
                    element.find('.yield').val(parseFloat(savedIngredient.yield) || 0);
                    element.find('.ttp').val(parseFloat(savedIngredient.ttp) || 0).attr('packaging_size', savedIngredient.packaging_size);
                    element.find('.ingredient-name').attr('item_masters_temp_id', savedIngredient.item_masters_temp_id);
                    element.find('.ttp').attr('readonly', true);
                    element.find('.uom').attr('disabled', true);
                    element.find('.pack-size').parents('label').remove();
                    $.fn.computeIngredientOrPackagingCost(element);
                    element.css('display', '');
                    wrapperTemplate.append(element);
                });

                wrapperTemplate
                    .find('.preparation, .yield')
                    .attr('readonly', false)
                    .attr('disabled', false);
                ingredientSection.append(wrapperTemplate);
            }

            //looping through saved packagings
            for (i of packagingGroupCount) {
                const groupedPackaging = savedPackagings.filter(e => e.packaging_group == i);
                const wrapperTemplate = $(document.createElement('div'));
                wrapperTemplate
                    .addClass('packaging-wrapper')
                    .append($('.add-sub-btn').eq(0).clone())
                    .append($('.new-add-sub-btn').eq(0).clone());

                groupedPackaging.forEach(savedPackaging => {
                    let element;
                    if (savedPackaging.is_primary == 'TRUE') {
                        if (savedPackaging.is_existing == 'TRUE') {
                            //primary and existing
                            element = $('.packaging-wrapper .packaging-entry').eq(0).clone();
                        } else {
                            //primary and new
                            element = $('.new-packaging-wrapper .packaging-entry').eq(0).clone();
                            element.find('.ttp').attr('readonly', false);
                        }
                    } else {
                        if (savedPackaging.is_existing == 'TRUE') {
                            //substitute and existing
                            element = $('.substitute-packaging').eq(0).clone();
                            if (savedPackaging.is_selected == 'TRUE') element.attr('primary', true);
                        } else {
                            //substitute and new
                            element = $('.new-substitute-packaging').eq(0).clone();
                            if (savedPackaging.is_selected == 'TRUE') element.attr('primary', true);
                        }
                    }
                    if (savedPackaging.menu_status == 'INACTIVE' || savedPackaging.item_status == 'INACTIVE') 
                        element.find('.label-danger').text('⚠️INACTIVE');

                    if (savedPackaging.item_masters_id && !savedPackaging.menu_as_ingredient_id)
                        element.find('.item-from').addClass('label label-info').text('IMFS');

                    if (savedPackaging.menu_as_ingredient_id && !savedPackaging.item_masters_id)
                        element.find('.item-from').addClass('label-warning').text('MIMF');

                    const packagingInput = element.find('.packaging');
                    packagingInput.val(savedPackaging.item_masters_id);
                    packagingInput.attr({
                        cost: savedPackaging.packaging_cost,
                        uom: savedPackaging.uom_id,
                        item_id: savedPackaging.item_masters_id,
                    });

                    if (savedPackaging.item_masters_id) element.find('.date-updated').html(
                        savedPackaging.updated_at ? `${timeago.format(savedPackaging.updated_at)}` :
                        savedPackaging.created_at ? `${timeago.format(savedPackaging.created_at)}` :
                        ''
                    );
                    element.find('.display-packaging').val(savedPackaging.full_item_description);
                    element.find('.packaging-name').val(savedPackaging.packaging_name);
                    element.find('.pack-size').val(parseFloat(savedPackaging.packaging_size));
                    element.find('.prep-quantity').val(parseFloat(savedPackaging.prep_qty) || 0).attr('readonly', false);
                    element.find('.uom').val(savedPackaging.uom_id);
                    element.find('.uom_name').val(savedPackaging.uom_name);
                    element.find('.display-uom').val(savedPackaging.uom_description);
                    element.find('.preparation option').attr('selected', false);
                    element.find('.preparation').val(savedPackaging.menu_ingredients_preparations_id)
                    element.find('.yield').val(parseFloat(savedPackaging.yield) || 0);
                    element.find('.ttp').val(parseFloat(savedPackaging.ttp) || 0).attr('packaging_size', savedPackaging.packaging_size);
                    element.find('.packaging-name').attr('item_masters_temp_id', savedPackaging.item_masters_temp_id);
                    element.find('.ttp').attr('readonly', true);
                    element.find('.uom').attr('disabled', true);
                    element.find('.pack-size').parents('label').remove();
                    $.fn.computeIngredientOrPackagingCost(element);
                    element.css('display', '');
                    wrapperTemplate.append(element);
                });

                wrapperTemplate
                    .find('.preparation, .yield')
                    .attr('readonly', false)
                    .attr('disabled', false);
                packagingSection.append(wrapperTemplate);
            }
        }

        $.fn.reload = function() {
            if($('.ingredient-wrapper').length == 1) {
                $('.no-ingredient-warning').css('display', '')
            }

            $('.display-ingredient, .display-packaging, .ingredient-name, .packaging-name').keyup(debounce(function() {
                const entry = $(this).parents(`
                    .ingredient-entry,
                    .substitute-ingredient,
                    .packaging-entry,
                    .substitute-packaging,
                    .new-substitute-ingredient,
                    .new-substitute-packaging
                `);

                const isNewItem = entry.attr('isExisting') == 'false';
                const query = $(this).val().toLowerCase().replace(/\s+/g, ' ').trim().split(' ')?.filter(e => e != '');
                const itemList = entry.find('.item-list');

                if (!query.length || isNewItem && !entry.find('.ingredient-name, .packaging-name').is(':focus')) {
                    $('.item-list').html('');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: isNewItem ? "{{ route('search_temp_items') }}" : "{{ route('search_ingredient') }}",
                    data: { content: JSON.stringify(query)},
                    success: function(response) {
                        const searchResult = JSON.parse(response);
                        $.fn.renderSearchResult(entry, itemList, searchResult);
                    },
                    error: function(response) { 
                        console.log(response); 
                    }  
                });
            }, 750));

            $('input').keydown(function(event) {
                if (event.keyCode == 13 && !$(this).hasClass('type-message')) {
                    event.preventDefault();
                    return false;
                }
            });

            $('form input, form select').keyup(function() {
                $('form input:valid, #form select:valid').css('outline', 'none');
            });

            $('.prep-quantity').keyup(function() {
                const entry = $(this).parents(`
                    .ingredient-entry,
                    .substitute-ingredient,
                    .new-substitute-ingredient,
                    .packaging-entry,
                    .substitute-packaging,
                    .new-substitute-packaging
                `);
                $.fn.computeIngredientOrPackagingCost(entry);
            });

            $('.yield').keyup(function() {
                const entry = $(this).parents(`
                    .ingredient-entry,
                    .substitute-ingredient,
                    .new-substitute-ingredient,
                    .packaging-entry,
                    .substitute-packaging,
                    .new-substitute-packaging
                `);
                $.fn.computeIngredientOrPackagingCost(entry);
            });

            $('.ttp').keyup(function() {
                const ttp = $(this);
                const entry = $(this).parents(`
                    .ingredient-entry,
                    .substitute-ingredient,
                    .new-substitute-ingredient,
                    .packaging-entry,
                    .substitute-packaging,
                    .new-substitute-packaging
                `);
                const [int, dec] = ttp.val().split('.');
                if (dec && dec.length > 4) {
                    const value = `${int}.${dec.slice(0,4)}`;
                    ttp.val(value);
                }
                $.fn.computeIngredientOrPackagingCost(entry);
            });

            $('.cost').keyup(function() {
                const entry = $(this).parents(`
                    .ingredient-entry,
                    .substitute-ingredient,
                    .new-substitute-ingredient,
                    .packaging-entry,
                    .substitute-packaging,
                    .new-substitute-packaging
                `);
                $.fn.computeIngredientOrPackagingCost(entry);
            });

            $('.pack-size').keyup(function() {
                const entry = $(this).parents(`
                    .ingredient-entry, 
                    .substitute-ingredient, 
                    .new-substitute-ingredient,
                    .packaging-entry,
                    .substitute-packaging, 
                    .new-substitute-packaging
                `);
                const value = $(this).val();
                if (value && value > 0) {
                    entry.find('.prep-quantity').attr('readonly', false);
                    entry.find('.yield').attr('readonly', false);
                    entry.find('.ttp').attr('readonly', false);
                    $.fn.computeIngredientOrPackagingCost(entry);
                } else {
                    entry.find('.prep-quantity').attr('readonly', true);
                    entry.find('.yield').attr('readonly', true);
                    entry.find('.ttp').attr('readonly', true);
                }
            });

            $('.portion').keyup(function() {
                const value = $(this).val();
                if (value && value > 0) $.fn.sumCost();
                else return;
            });

            $('.ingredient-name, .packaging-name').keyup(function() {
                const value = $(this).val();
                $(this).val(value.toUpperCase());
            });

            $('.rnd_menu_description').keyup(function() {
                const value = $(this).val();
                $(this).val(value.toUpperCase());
            });

            $('.rnd_menu_srp').keyup(function() {
                $.fn.sumCost();
            });

            $('.ingredient-name, .packaging-name').focusout(function() {
                $('.item-list').fadeOut();
            });
        }

        $.fn.sumCost = function() {
            const ingredientWrappers = jQuery.makeArray($('.ingredient-section .ingredient-wrapper, .ingredient-section .new-ingredient-wrapper'));
            const packagingWrappers = jQuery.makeArray($('.packaging-section .packaging-wrapper, .packaging-section .new-packaging-wrapper'));
            const lowCost = Number(localStorage.getItem('lowCost')) || 30;
            const totalIngredientCostInput = $('.ingredient-total-cost');
            const totalCostInput = $('.total-cost');
            const foodCostInput = $('.food-cost');
            const portionInput = $('.portion');
            const srpInput = $('.rnd_menu_srp');
            const srp = srpInput.val() || 0;
            const percentageText = $('.percentage');
            if (portionInput.val() <= 0) portionInput.val('1');
            const portionSize = portionInput.val();
            let ingredientSum = 0;
            let packagingSum = 0;

            //looping through ingredient wrappers
            ingredientWrappers.forEach(wrapper => {
                const primary = $(wrapper).find('.ingredient-entry');
                const substitute = jQuery.makeArray($(wrapper).find('.substitute-ingredient, .new-substitute-ingredient'));
                const markedSub = substitute.filter(e => $(e).attr('primary') == 'true');
                if (!!markedSub.length) {
                    ingredientSum += Number($(markedSub[0]).find('.cost').val().replace(/[^0-9.]/g, ''));
                } else {
                    ingredientSum += Number(primary.find('.cost').val().replace(/[^0-9.]/g, ''));
                }
            });
            ingredientSum = math.round(ingredientSum, 4);
            const foodCost = math.round(ingredientSum / portionSize, 4);
            totalIngredientCostInput.val(ingredientSum);
            foodCostInput.val(foodCost);
            const percentage = srp > 0 ? math.round(foodCost / srp * 100, 2) : 0;
            
            //formatting the percentage text depending on the low cost
            $(percentageText).text(`${percentage}%`);
            if (percentage > lowCost) {
                $(percentageText).css('color', 'red');
                foodCostInput.css({'color': 'red', 'outline': '2px solid red', 'font-weight': 'bold',});
            } else {
                $(percentageText).css('color', '');
                foodCostInput.css({'color': '', 'outline': '', 'font-weight': 'normal'});    
            }

            //looping through packaging wrappers 
            packagingWrappers.forEach(wrapper => {
                const primary = $(wrapper).find('.packaging-entry');
                const substitute = jQuery.makeArray($(wrapper).find('.substitute-packaging, .new-substitute-packaging'));
                const markedSub = substitute.filter(e => $(e).attr('primary') == 'true');
                if (!!markedSub.length) {
                    packagingSum += Number($(markedSub[0]).find('.cost').val().replace(/[^0-9.]/g, ''));
                } else {
                    packagingSum += Number(primary.find('.cost').val()?.replace(/[^0-9.]/g, ''));
                }
            });
            packagingSum = math.round(packagingSum, 4);
            $('.packaging-cost').val(packagingSum);
            const totalCost = math.round(packagingSum + foodCost, 4);
            totalCostInput.val(totalCost);

        }

        $.fn.formatSelected = function() {
            const substitutes = jQuery.makeArray($(`
                .substitute-ingredient, 
                .new-substitute-ingredient,
                .substitute-packaging, 
                .new-substitute-packaging
            `));
            substitutes.forEach(sub => {
                if ($(sub).attr('primary') == 'true') {
                    $(sub).css('background', '#ffe662');
                    $(sub).find('.set-primary').css('color', 'black');
                } else {
                    $(sub).css('background', '');
                    $(sub).find('.set-primary').css('color', '');
                }
            });
        }

        $.fn.renderSearchResult = function(entry, itemList, searchResult) {
            const currentItems = {item_id: [], menu_item_id: [], item_id_temp: []};

            $('form .ingredient, form .packaging, form .ingredient-name, form .packaging-name').each(function(index) {
                const item = $(this);
                const itemIndex = $(`
                    form .ingredient, 
                    form .packaging, 
                    form .ingredient-name, 
                    form .packaging-name`
                ).index(entry.find('.ingredient, .packaging, .ingredient-name, .packaging-name'));
                if (index != itemIndex) {
                    if (item.attr('item_id'))  console.log('pumasok'),currentItems.item_id.push(item.attr('item_id'));
                    if (item.attr('menu_item_id')) currentItems.menu_item_id.push(item.attr('menu_item_id'));
                    if (item.attr('item_masters_temp_id')) currentItems.item_id_temp.push(item.attr('item_masters_temp_id'))
                }
            });

            const result = [...searchResult]
                .filter(item => !currentItems.item_id.includes(item.item_masters_id?.toString()) && 
                    !currentItems.menu_item_id.includes(item.menu_item_id?.toString()) &&
                    !currentItems.item_id_temp.includes(item.item_masters_temp_id?.toString()))
                .sort((a, b) => (a.full_item_description || a.menu_item_description)
                ?.localeCompare(b.full_item_description || b.menu_item_description));

            if (!result.length) {
                result.push({full_item_description: 'No Item Found'});
            }

            $('.item-list').html('');
            
            itemList.fadeIn('fast');

            const ul = $(document.createElement('ul'));
            ul.addClass('dropdown-menu');
            ul.css({
                display: 'block',
                position: 'absolute',
            });
            result.forEach(e => {
                const li = $(document.createElement('li'));
                const a = $(document.createElement('a'));
                if (!e.item_masters_id && !e.menu_item_id) {
                    a.css('color', 'red !important');
                }
                li.addClass('list-item dropdown-item');
                li.attr({
                    item_id: e.item_masters_id,
                    menu_item_id: e.menu_item_id,
                    item_masters_temp_id: e.item_masters_temp_id,
                    ttp: parseFloat(e.ttp) || parseFloat(e.food_cost) || 0,
                    packaging_size: e.packaging_size || 1,
                    uom: e.packagings_id || e.uoms_id,
                    uom_desc: e.packaging_description || e.uom_description,
                    food_cost_temp: e.food_cost_temp,
                    item_desc: e.full_item_description || e.menu_item_description || e.item_description,
                    date_updated: e.updated_at || e.created_at,
                });
                a.html(e.full_item_description && e.item_masters_id ? `<span class="label label-info">IMFS</span> ${e.full_item_description}`
                    : e.menu_item_description ? `<span class="label label-warning">MIMF</span> ${e.menu_item_description}` 
                    : e.item_masters_temp_id ? `<span class="label label-purple">USER</span> ${e.item_description}` 
                    : 'No Item Found');
                li.append(a);
                ul.append(li);
            });
            itemList.append(ul);
        }

        $.fn.computeIngredientOrPackagingCost = function(entry) {
            const yieldInput = entry.find('.yield');
            const ingredientQuantityInput = entry.find('.ing-quantity, .pack-quantity');
            const packagingSizeInput = entry.find('.pack-size');
            const preperationQuantity = entry.find('.prep-quantity').val();
            const ttpInput = entry.find('.ttp');
            const ttp = ttpInput.val() || 0;
            const costInput = entry.find('.cost');
            const yieldPercent = math.round(yieldInput.val() / 100, 4) || 0;
            const uomQty = 1;
            const packagingSize = packagingSizeInput.val() || ttpInput.attr('packaging_size');
            const ingredientModifier = math.round(uomQty / packagingSize * preperationQuantity * (1 + (1 - yieldPercent)), 4);
            const ingredientCost = math.round(ingredientModifier * ttp, 4);
            const ingredientQty = math.round(preperationQuantity * (1 + (1 - yieldPercent)), 4);

            ingredientQuantityInput.val(ingredientQty);
            costInput.val(ingredientCost);
            $.fn.sumCost();
        }

        $.fn.submitForm = function(buttonClicked) {
            
            // for ingredients
            const ingredientsArray = [];
            const ingredientGroups = jQuery.makeArray($('#form-ingredient .ingredient-wrapper, #form-ingredient .new-ingredient-wrapper'));
            ingredientGroups.forEach((ingredientGroup, groupIndex) => {
                const group = $(ingredientGroup);
                const ingredientArray = [];
                const ingredients = jQuery.makeArray(group.find('.ingredient-entry, .substitute-ingredient, .new-substitute-ingredient'));
                ingredients.forEach((ingredient, memberIndex) => {
                    const ingredientMember = $(ingredient);
                    const ingredientObject = {};
                    ingredientObject.is_existing = (ingredientMember.attr('isExisting') == 'true').toString().toUpperCase();
                    ingredientObject.is_primary = (ingredientMember.hasClass('ingredient-entry')).toString().toUpperCase();
                    ingredientObject.is_selected = (ingredientMember.attr('primary') == 'true').toString().toUpperCase();
                    ingredientObject.row_id = memberIndex;
                    ingredientObject.ingredient_group = groupIndex;
                    ingredientObject.item_masters_id = ingredientMember.find('.ingredient').attr('item_id');
                    ingredientObject.item_masters_temp_id = ingredientMember.find('.ingredient-name').attr('item_masters_temp_id');
                    ingredientObject.menu_as_ingredient_id = ingredientMember.find('.ingredient').attr('menu_item_id');
                    ingredientObject.ingredient_name = ingredientMember.find('.ingredient-name').val()?.trim().toUpperCase();
                    ingredientObject.packaging_size = ingredientMember.find('.pack-size').val();
                    ingredientObject.prep_qty = ingredientMember.find('.prep-quantity').val();
                    ingredientObject.uom_id = ingredientMember.find('.uom').val();
                    ingredientObject.uom_name = ingredientMember.find('.uom_name').val()?.trim().toUpperCase();
                    ingredientObject.menu_ingredients_preparations_id = ingredientMember.find('.preparation').val();
                    ingredientObject.yield = ingredientMember.find('.yield').val();
                    ingredientObject.ttp = ingredientMember.find('.ttp').val();
                    ingredientObject.qty = ingredientMember.find('.ing-quantity').val();
                    ingredientObject.cost = ingredientMember.find('.cost').val()?.replace(/[^0-9.]/g, '');
                    ingredientArray.push(ingredientObject);
                });
                if (ingredientArray.length) {
                    ingredientsArray.push(ingredientArray);
                }
            });

            // for packagings
            const packagingsArray = [];
            const packagingGroups = jQuery.makeArray($('#form-packaging .packaging-wrapper, #form-packaging .new-packaging-wrapper'));
            packagingGroups.forEach((packagingGroup, groupIndex) => {
                const group = $(packagingGroup);
                const packagingArray = [];
                const packagings = jQuery.makeArray(group.find('.packaging-entry, .substitute-packaging, .new-substitute-packaging'));
                packagings.forEach((packaging, memberIndex) => {
                    const packagingMember = $(packaging);
                    const packagingObject = {};
                    packagingObject.is_existing = (packagingMember.attr('isExisting') == 'true').toString().toUpperCase();
                    packagingObject.is_primary = (packagingMember.hasClass('packaging-entry')).toString().toUpperCase();
                    packagingObject.is_selected = (packagingMember.attr('primary') == 'true').toString().toUpperCase();
                    packagingObject.row_id = memberIndex;
                    packagingObject.packaging_group = groupIndex;
                    packagingObject.item_masters_id = packagingMember.find('.packaging').attr('item_id');
                    packagingObject.item_masters_temp_id = packagingMember.find('.packaging-name').attr('item_masters_temp_id');
                    packagingObject.packaging_name = packagingMember.find('.packaging-name').val()?.trim().toUpperCase();
                    packagingObject.packaging_size = packagingMember.find('.pack-size').val();
                    packagingObject.prep_qty = packagingMember.find('.prep-quantity').val();
                    packagingObject.uom_id = packagingMember.find('.uom').val();
                    packagingObject.uom_name = packagingMember.find('.uom_name').val()?.trim().toUpperCase();
                    packagingObject.menu_ingredients_preparations_id = packagingMember.find('.preparation').val();
                    packagingObject.yield = packagingMember.find('.yield').val();
                    packagingObject.ttp = packagingMember.find('.ttp').val();
                    packagingObject.qty = packagingMember.find('.ing-quantity').val();
                    packagingObject.cost = packagingMember.find('.cost').val()?.replace(/[^0-9.]/g, '');
                    packagingArray.push(packagingObject);
                });
                if (packagingArray.length) {
                    packagingsArray.push(packagingArray);
                }
            });
            const ingredientsJSON = JSON.stringify(ingredientsArray);
            const packagingsJSON = JSON.stringify(packagingsArray);

            const form = $(document.createElement('form'))
                .attr('method', 'POST')
                .attr('action', buttonClicked == 'save' ? "{{ route('edit_rnd_menu') }}" : "{{ route('publish_rnd_menu') }}")
                .css('display', 'none');

            const csrf = $(document.createElement('input'))
                .attr({
                    type: 'hidden',
                    name: '_token',
                }).val("{{ csrf_token() }}");

            const ingredientsData = $(document.createElement('input'))
                .attr('name', 'ingredients')
                .val(ingredientsJSON);

            const packagingsData = $(document.createElement('input'))
                .attr('name', 'packagings')
                .val(packagingsJSON);

            const rndMenuDescriptionData = $(document.createElement('input'))
                .attr('name', 'rnd_menu_description')
                .val($('.rnd_menu_description').val().trim());

            const rndMenuIdData = $(document.createElement('input'))
                .attr('name', 'rnd_menu_items_id')
                .val(rndMenuItem?.id);

            const srpData = $(document.createElement('input'))
                .attr('name', 'rnd_menu_srp')
                .val($('.rnd_menu_srp').val());
            
            const portionData = $(document.createElement('input'))
                .attr('name', 'portion_size')
                .val($('.portion').val());

            form.append(
                csrf,
                ingredientsData,
                packagingsData,
                rndMenuDescriptionData,
                rndMenuIdData,
                srpData,
                portionData,
            );
            $('.panel-body').append(form);
            form.submit();
        }

        $.fn.checkFormValidity = function() {
            const formValues = $(`
                .ingredient-section input, 
                .ingredient-section select,
                .packaging-section input, 
                .packaging-section select
            `);

            const isValid = jQuery.makeArray(formValues).every(e => !!$(e).val()) &&
                jQuery.makeArray($('form .cost')).every(e => !!$(e).val()?.replace(/[^0-9.]/g, '')) &&
                $('.portion').val() > 0 && $('.rnd_menu_description').val() && $('.rnd_menu_srp').val() > 0;

            const hasIngredient = $('#form-ingredient .ingredient-wrapper, #form-ingredient .new-ingredient-wrapper').length > 0;
            
            return [isValid, hasIngredient];
        }

        $.fn.formatInvalidInputs = function(isValid) {
            Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: !isValid ? 'Please fill out all fields!' : 'Please add ingredients!',
                }).then(() => {
                    $(`
                        .ingredient-section input:invalid, 
                        .ingredient-section select:invalid,
                        .packaging-section input:invalid, 
                        .packaging-section select:invalid
                    `).css('outline', '2px solid red');
                    $('.ingredient-section .ingredient:invalid, .packaging-section .packaging:invalid')
                        .parents('.ingredient-entry, .packaging-entry')
                        .find('.display-ingredient, .display-packaging')
                        .css('outline', '2px solid red');
                    if ($('.portion').val() == 0) $('.portion').css('outline', '2px solid red');
					if (!$('.rnd_menu_description').val()) $('.rnd_menu_description').css('outline', '2px solid red');
					if (!$('.rnd_menu_srp').val() || $('.rnd_menu_srp').val() <= 0) $('.rnd_menu_srp').css('outline', '2px solid red');
                });
        }

        $(document).on('click', '#save-btn', function(event) {
            const [isValid, hasIngredient] = $.fn.checkFormValidity();
            if (isValid) {
                Swal.fire({
                    title: action == 'add' ? 'Do you want to save this item?' : 'Do you want to save the changes?',
                    html: '📄 Newly added ingredients and packagings will be forwarded to <label class="label label-info">PURCHASING</label> for Item Creation in IMFS.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.fn.submitForm('save');
                    }
                });
            } else {
                $.fn.formatInvalidInputs(isValid);
            }
        }); 

        $(document).on('click', '#publish-btn', function(event) {
            const [isValid, hasIngredient] = $.fn.checkFormValidity();
            if (isValid && hasIngredient) {
                Swal.fire({
                    title: 'Do you want to publish this item?',
                    html: '🟠  Doing so will forward this item to <span class="label label-warning">MARKETING</span>.<br/>' +
                        `📄  You won't be able to edit this item again.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Publish'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.fn.submitForm('publish');
                    }    
                });
            } else {
                $.fn.formatInvalidInputs(isValid);
            }

        });

        $(document).on('click', '.list-item', function(event) {
            const item = $(this);
            let entry = item.parents(`
                .ingredient-entry, 
                .substitute-ingredient, 
                .packaging-entry, 
                .substitute-packaging,
                .new-substitute-ingredient,
                .new-substitute-packaging
            `);

            const ingredient_packaging = entry.find('.ingredient, .packaging');

            if (!item.attr('item_id') && !item.attr('menu_item_id') && !item.attr('item_masters_temp_id')) return;
            if (item.attr('item_id') && !item.attr('menu_item_id')) {
                entry.find('.item-from')
                    .removeClass('label-info label-warning label-success label-secondary label-primary')
                    .addClass('label-info')
                    .text('IMFS');
            } else if (item.attr('menu_item_id')) {
                entry.find('.item-from')
                    .removeClass('label-info label-warning label-success label-secondary label-primary')
                    .addClass('label-warning')
                    .text('MIMF')
            }
            
            entry.find('.label-danger').text('');
            entry.find('.date-updated').text('');
            ingredient_packaging.val(item.attr('item_id') || item.attr('menu_item_id'));
            ingredient_packaging.attr({
                cost: $(this).attr('cost'),
                food_cost_temp: $(this).attr('food_cost_temp'),
                uom: $(this).attr('uom'),
                item_id: $(this).attr('item_id'),
                menu_item_id: $(this).attr('menu_item_id'),
            });
            if (!item.attr('item_id')) ingredient_packaging.removeAttr('item_id');
            if (!item.attr('menu_item_id')) ingredient_packaging.removeAttr('menu_item_id');
            entry.find(`
                .display-ingredient, 
                .display-packaging, 
                .ingredient-name, 
                .packaging-name
            `).val(item.attr('item_desc'))
                .attr('item_masters_temp_id', item.attr('item_masters_temp_id'));
            entry.find('.uom').val(item.attr('uom'));
            entry.find('.display-uom').val(item.attr('uom_desc'));
            entry.find('uom').val(item.attr('uoms_id'));
            entry.find('.ttp')
                .val(item.attr('ttp'))
                .attr('ttp', item.attr('ttp'))
                .attr('packaging_size', item.attr('packaging_size'));
            entry.find('.yield').val('100').attr('readonly', false);
            entry.find('.preparation').attr('disabled', false);
            entry.find('.ing-quantity, .pack-quantity').val('1');
            entry.find('.prep-quantity')
                .val('1')
                .attr('readonly', false);
            if (item.attr('item_id') || item.attr('item_masters_temp_id')) {
                entry.find('.date-updated').text(
                    item.attr('date_updated') ?
                    `${timeago.format(item.attr('date_updated'))}` :
                    ''
                );
            }

            entry.find('select.uom').attr('disabled', true);
            entry.find('input.pack-size').parents('label').remove();
            $('#form input:valid, #form select:valid').css('outline', 'none');
            $('.item-list').html('');  
            $('.item-list').fadeOut();
            $.fn.computeIngredientOrPackagingCost(entry);
        });

        $(document).on('click', '.move-up', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper');
            const sibling = entry.prev()[0];
            if (!sibling) return;
            $(sibling).animate(
                {
                    top: `+=${$(entry).outerHeight()}`,
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
                    top: `-=${$(sibling).outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertBefore($(entry).prev());
                    }
                }
            );
        });

        $(document).on('click', '.move-down', function() {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper');
            const sibling = entry.next()[0];
            if (!sibling) return;

            $(sibling).animate(
                {
                    top: `-=${$(entry).outerHeight()}`,
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
                    top: `+=${$(sibling).outerHeight()}`
                },
                {
                    duration: 300,
                    queue: false,
                    done: function() {
                        entry.css('top', '0');
                        entry.insertAfter($(entry).next());
                    }
                }
            );
            
        });

        $(document).on('click', addButtonsId, function() {
                const id = $(this).attr('id');
                let wrapper;
                let section;
                if (id == 'add-existing-ingredient') {
                    wrapper = $('.ingredient-wrapper').eq(0).clone();
                    section = $('.ingredient-section');
                    $('.no-ingredient-warning').remove();
                } else if (id == 'add-new-ingredient') {
                    wrapper = $('.new-ingredient-wrapper').eq(0).clone();
                    section = $('.ingredient-section');
                    $('.no-ingredient-warning').remove();
                } else if (id == 'add-existing-packaging') {
                    wrapper = $('.packaging-wrapper').eq(0).clone();
                    section = $('.packaging-section');
                    $('.no-packaging-warning').remove();
                } else {
                    wrapper = $('.new-packaging-wrapper').eq(0).clone();
                    section = $('.packaging-section');
                    $('.no-packaging-warning').remove();
                }
                section.append(wrapper.show());
                wrapper.find('.display-ingredient, .display-packaging, .ingredient-name, .packaging-name').focus();
                $.fn.reload();
        });

        $(document).on('click', '.delete', function(event) {
            const entry = $(this).parents(
                '.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper'
            );
            entry.hide(300, function() {
                $(this).remove();
                $.fn.sumCost();
            });
        }); 

        $(document).on('click', '.add-sub-btn, .new-add-sub-btn', function(event) {
            const entry = $(this).parents('.ingredient-wrapper, .new-ingredient-wrapper, .packaging-wrapper, .new-packaging-wrapper');
            const slug = entry.hasClass('ingredient-wrapper') || entry.hasClass('new-ingredient-wrapper') ? 'ingredient' : 'packaging';
            const isExisting = $(this).hasClass('add-sub-btn');
            let substitute;
            if (isExisting) {
                substitute = $(`.substitute-${slug}`).eq(0).clone();
            } else {
                substitute = $(`.new-substitute-${slug}`).eq(0).clone();
            }
            entry.append(substitute.css('display', ''));
            substitute.find(`
                .display-ingredient,
                .display-packaging,
                .ingredient-name,
                .packaging-name
            `).focus();
            $.fn.reload();
        });

        $(document).on('click', '.set-primary', function(event) {
            const sub = $(this).parents(`
                .substitute-ingredient,
                .new-substitute-ingredient,
                .substitute-packaging,
                .new-substitute-packaging
            `);

            const ingredientWrapper = $(this).parents(`
                .ingredient-wrapper, 
                .new-ingredient-wrapper,
                .packaging-wrapper, 
                .new-packaging-wrapper
            `);

            const isPrimary = sub.attr('primary') == 'true';
            ingredientWrapper.find(`
                .substitute-ingredient, 
                .new-substitute-ingredient,
                .substitute-packaging, 
                .new-substitute-packaging
            `).attr('primary', false);
            
            if (!isPrimary) {
                sub.attr('primary', true);
            }
            $.fn.formatSelected();
            $.fn.sumCost();

        });

        $(document).on('click', '.delete-sub', function(event) {
            const subEntry = $(this).parents(`
                .substitute-ingredient, 
                .new-substitute-ingredient, 
                .substitute-packaging, 
                .new-substitute-packaging
            `);
            subEntry.hide('fast', function() {
                $(this).remove();
                $.fn.sumCost();
            });
        });


        $('.loading-label').remove();
        $.fn.firstLoad();
        $.fn.reload();
        $.fn.formatSelected();
        $.fn.sumCost();
    });
</script>

@endpush