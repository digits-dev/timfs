@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style>
    thead {
        background: rgb(213, 211, 211);
    }

    .swal2-html-container {
        line-height: 3rem;
    }

    table td + td {
        border-left: 2px solid #b9b8b8 !important; 
    }

    table, th {
        border: 2px solid #b9b8b8 !important; 
    }

    .divider {
        border-top: 2px solid #b9b8b8;
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
                            <input value="{{$item ? $item->tasteless_menu_code : ''}}" type="text" class="form-control rnd_tasteless_code" placeholder="XXXXXX" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h3 class="text-center">RND MENU COSTING</h3>
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">PARTICULARS</th>
                                <th class="text-center">VALUES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-bold">Portion Size</td>
                                <td class="text-center">
                                    <input type="number" class="form-control portion-size" placeholder="Portion Size" step="any" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">Recipe Cost Without Buffer</td>
                                <td class="text-center">
                                    <input type="number" class="form-control recipe-cost-wo-buffer" placeholder="Recipe Cost Without Buffer" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Buffer</td>
                                <td class="text-center">
                                    <input type="number" class="form-control buffer" placeholder="Buffer" step="any" required>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td class="text-center text-bold">Final Recipe Cost</td>
                                <td class="text-center">
                                    <input type="number" class="form-control final-recipe-cost" placeholder="Final Recipe Cost" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">PARTICULARS</th>
                                <th class="text-center">VALUES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-bold">Packaging Cost</td>
                                <td class="text-center">
                                    <input type="number" class="form-control packaging-cost" placeholder="Packaging Cost" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Ideal Food Cost</td>
                                <td class="text-center">
                                    <input type="number" class="form-control ideal-food-cost" placeholder="Ideal Food Cost" step="any" required>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td class="text-center text-bold">Suggested Final SRP With VAT</td>
                                <td class="text-center">
                                    <input type="number" class="form-control suggested-final-srp-w-vat" placeholder="Suggested Final SRP With VAT" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">PARTICULARS</th>
                                <th class="text-center">VALUES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-bold">Final SRP without VAT</td>
                                <td class="text-center">
                                    <input type="number" class="form-control final-srp-wo-vat" placeholder="Final SRP without VAT" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">Final SRP with VAT</td>
                                <td class="text-center">
                                    <input type="number" class="form-control final-srp-w-vat" placeholder="Final SRP with VAT" step="any" required>
                                </td>
                            </tr>
                            <tr class="divider">
                                <td class="text-center text-bold">% Cost Packaging From Final SRP</td>
                                <td class="text-center">
                                    <input type="number" class="form-control cost-packaging-from-final-srp" placeholder="% Cost Packaging From Final SRP" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Food Cost from Final SRP</td>
                                <td class="text-center">
                                    <input type="number" class="form-control food-cost-from-final-srp" placeholder="% Food Cost from Final SRP" step="any" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center text-bold">% Total Cost</td>
                                <td class="text-center">
                                    <input type="number" class="form-control total-cost" placeholder="% Total Cost" step="any" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-2 h-5"><strong>Published by:</strong></div>
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
        $('body').addClass('sidebar-collapse');
        
        function firstLoad() {
            $('.portion-size').val(parseFloat(item.portion_size) || 0);
            $('.recipe-cost-wo-buffer').val(parseFloat(item.computed_food_cost || 0));
            $('.buffer').val(parseFloat(item.buffer || 6.5));

            $('.packaging-cost').val(parseFloat(item.computed_packaging_total_cost || 0));
            $('.ideal-food-cost').val(parseFloat(item.ideal_food_cost || 30));
            $('.final-srp-w-vat').val(parseFloat(item.rnd_menu_srp || 0));
        }

        function computeFormula() {
            const portionSize = $('.portion-size').val();
            const recipeCostWithoutBuffer = $('.recipe-cost-wo-buffer').val();
            const buffer = $('.buffer').val();
            
            const packagingCost = $('.packaging-cost').val();
            const idealFoodCost = $('.ideal-food-cost').val();
            const finalSrpWithVat = $('.final-srp-w-vat').val();

            const finalRecipeCost = math.round((recipeCostWithoutBuffer * (1 + (buffer / 100))) / portionSize, 4);
            const suggestedFinalSrpWithVAT = math.round(finalRecipeCost / (idealFoodCost / 100) * 1.12, 4);
            const finalSrpWithoutVAT = math.round(finalSrpWithVat / 1.12, 4);
            const costPackagingFromFinalSrp = math.round(packagingCost / finalSrpWithoutVAT * 100, 2);
            const foodCostFromFinalSrp = math.round(finalRecipeCost / finalSrpWithoutVAT * 100, 2);
            const totalCost = math.round(costPackagingFromFinalSrp + foodCostFromFinalSrp, 2);

            $('.rnd_menu_srp').val(finalSrpWithVat);
            $('.final-recipe-cost').val(finalRecipeCost);
            $('.suggested-final-srp-w-vat').val(suggestedFinalSrpWithVAT);
            $('.final-srp-wo-vat').val(finalSrpWithoutVAT);
            $('.cost-packaging-from-final-srp').val(costPackagingFromFinalSrp);
            $('.food-cost-from-final-srp').val(foodCostFromFinalSrp);
            $('.total-cost').val(totalCost);
        }

        function submitForm() {
            const rnd_menu_items_id = item.rnd_menu_items_id;
            const buffer = $('.buffer').val();
            const ideal_food_cost = $('.ideal-food-cost').val();
            const rnd_menu_srp = $('.final-srp-w-vat').val();

            const dataObj = {buffer, ideal_food_cost, rnd_menu_srp};

            const form = $(document.createElement('form'))
                .attr('method', 'POST')
                .attr('action', "{{route('submit_costing')}}")
                .hide();

            const csrf = $(document.createElement('input'))
                .attr('name', '_token')
                .val("{{ csrf_token() }}");

            const idInput = $(document.createElement('input'))
                .attr('name', 'rnd_menu_items_id')
                .val(rnd_menu_items_id);

            const menuId = $(document.createElement('input'))
                .attr('name', 'menu_items_id')
                .val("{{ $item->menu_items_id }}");

            const rndMenuData = $(document.createElement('input'))
                .attr('name', 'rnd_menu_data')
                .val(JSON.stringify(dataObj));

            form.append(csrf, idInput, menuId, rndMenuData);
            $('.panel-body').append(form);
            form.submit();
        }

        $(document).on('keyup', 'input', function() {
            computeFormula();
        });

        $('#save-btn').on('click', function() {
            const isValid = jQuery.makeArray($('input')).every(e => !!$(e).val());
            if (isValid) {
                Swal.fire({
                    title: 'Do you want to save the changes?',
                    html: '🔵 Doing so will forward this item to <label class="label label-info">MARKETING APPROVER</label>.' + 
                        '<br/> 📄 You won\'t be able to revert this.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Oops..',
                    text: 'Please fill out all fields.',
                    icon: 'error',
                });
            }
        });
        firstLoad();
        computeFormula();
    });

</script>



@endpush