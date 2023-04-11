@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>

<style>
    #form input:valid {
        outline: 2px solid green;
    }
    #form input:invalid {
        outline: 2px solid red;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')

<p>
    <a title="Return" href="{{ CRUDBooster::mainpath() }}">
        <i class="fa fa-chevron-circle-left "></i>
        Back To List Data RND Menu Items (For Approval)
    </a>
</p>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-pencil"></i><strong> Edit RND Menu Item</strong>
    </div>
    <div class="panel-body">
        <form action="" id="form" class="form">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu Item Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item ? $item->rnd_menu_description : ''}}" type="text" class="form-control rnd_menu_description" placeholder="RND Menu Item Description" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">RND Menu SRP</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <span class="custom-icon"><strong>₱</strong></span>
                            </div>
                            <input value="{{$item ? (float) $item->rnd_menu_srp : ''}}" type="number" class="form-control rnd_menu_srp" placeholder="0.00" readonly>
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
            </div>
            <hr>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group"></div>
                    <label for="" class="control-label">Portion Size</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <span class="custom-icon"><strong>÷</strong></span>
                        </div>
                        <input value="{{(float) $item->portion_size}}" type="text" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group"></div>
                    <label for="" class="control-label">Ingredient Total Cost</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <span class="custom-icon"><strong>₱</strong></span>
                        </div>
                        <input value="{{(float) $item->computed_ingredient_total_cost}}" type="text" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group"></div>
                    <label for="" class="control-label">Food Cost</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <span class="custom-icon"><strong>₱</strong></span>
                        </div>
                        <input value="{{(float) $item->computed_food_cost}}" type="text" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group"></div>
                    <label for="" class="control-label">Food Cost Percentage</label>
                    <div class="input-group">
                        <input value="{{(float) $item->computed_food_cost_percentage}}" type="text" class="form-control" readonly>
                        <div class="input-group-addon">
                            <span class="custom-icon"><strong>%</strong></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"></div>
                    <label for="" class="control-label">* Packaging Cost</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <span class="custom-icon"><strong>₱</strong></span>
                        </div>
                        <input value="{{$item->packaging_cost ? (float) $item->packaging_cost : ''}}" type="number" class="form-control packaging-cost" placeholder="Enter Packaging Cost" required step="any" min="0.00001" max="100000">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-2"><strong>Published by:</strong></div>
                <div class="col-md-3">{{$item->published_by}}</div>
                <div class="col-md-2"><strong>Published at:</strong></div>
                <div class="col-md-3">{{$item->published_at}}</div>
            </div>
        </form>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
		<button class="btn btn-primary pull-right" id="save-btn"><i class="fa fa-save" ></i> Save</button>
    </div>
</div>
@endsection

@push('bottom')
<script>
    const item = {!! json_encode($item) !!};
    $(document).ready(function() {
        $(document).on('click', '#save-btn', function(event) {
            const packagingCostInput = $('.packaging-cost');
            const value = packagingCostInput.val();

            if (value && value > 0) {
                Swal.fire({
                    title: 'Do u want to save the changes?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $(document.createElement('form'))
                            .attr('method', 'POST')
                            .attr('action', "{{route('submit_packaging_cost')}}")
                            .hide();

                        const csrf = $(document.createElement('input'))
                            .attr({
                                type: 'hidden',
                                name: '_token',
                            }).val("{{ csrf_token() }}");

                        const rndMenuItemsId = $(document.createElement('input'))
                            .attr('name', 'rnd_menu_items_id')
                            .val(item.rnd_menu_items_id);

                        const packagingCost = $(document.createElement('input'))
                            .attr('name', 'packaging_cost')
                            .val(value);

                        form.append(csrf, rndMenuItemsId, packagingCost);
                        $('.panel-body').append(form);
                        form.submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill out all fields!',
                })
            }
        });
    });

</script>



@endpush