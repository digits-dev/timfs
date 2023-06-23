@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.7.0/math.js" integrity="sha512-jVMFsAksn8aljb9IJ+3OCAq38dJpquMBjgEuz7Q5Oqu5xenfin/jxdbKw4P5eKjUF4xiG/GPT5CvCX3Io54gyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<style>
    .flex {
        display: flex;
    }

    .parent {
        flex-wrap: wrap;
    }

    .child {
        min-width: 500px;
        margin-top: 10px;
    }

    .child > * {
        width: 50%;
    }
    thead {
        background: rgb(213, 211, 211);
    }

    .swal2-html-container {
        line-height: 3rem;
    }

    td {
        vertical-align: middle !important;
    }

    table.costing-table td + td {
        border-left: 2px solid #b9b8b8 !important; 
    }

    table.costing-table, table.costing-table th {
        border: 2px solid #b9b8b8 !important; 
    }

    .divider {
        border-top: 2px solid #b9b8b8;
    }

    .srp-td {
        width: 30%;
        margin: auto 3px;
    }

    .row-srp {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-direction: row;
        flex-wrap: wrap;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        appearance: textfield;
        -moz-appearance: textfield; /* Firefox */
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
        <i class="fa fa-pencil"></i><strong> Edit {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>
    <div class="panel-body">
        <form action="{{ route('menu_item_submit_costing') }}" method="POST" id="form" class="form">
            @csrf
            <input type="text" name="menu_items_id" value="{{ $item->menu_items_id }}" class="hide">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="" class="control-label">Menu Item Description</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-sticky-note"></i>
                            </div>
                            <input value="{{$item->menu_item_description}}" type="text" class="form-control rnd_menu_description" placeholder="RND Menu Item Description" readonly>
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
            <h3 class="text-center"> MENU COSTING</h3>
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-striped table-bordered costing-table">
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
                                    <input type="number" class="form-control portion-size" placeholder="Portion Size" step="any" readonly>
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
                                    <input name="buffer" type="number" class="form-control buffer" placeholder="Buffer" step="any" required>
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
                <div class="col-md-3">
                    <table class="table table-striped table-bordered costing-table">
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
                                    <input name="ideal_food_cost" type="number" class="form-control ideal-food-cost" placeholder="Ideal Food Cost" step="any" required>
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
                <div class="col-md-5">
                    <table class="table table-striped table-bordered costing-table">
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
                            <tr class="divider">
                                <td class="text-center text-bold">Final SRP with VAT</td>
                                <td>
                                    <div class="row-srp">
                                        <div class="srp-td">
                                            <p class="text-center text-bold">Dine In</p>
                                            <input name="menu_price_dine" type="number" class="form-control final-srp-w-vat-dine-in" placeholder="0.00" required>
                                        </div>
                                        <div class="srp-td">
                                            <p class="text-center text-bold">Take Out</p>
                                            <input name="menu_price_take" type="number" class="form-control final-srp-w-vat-take-out" placeholder="0.00" required>
                                        </div>
                                        <div class="srp-td">
                                            <p class="text-center text-bold">Delivery</p>
                                            <input name="menu_price_dlv" type="number" class="form-control final-srp-w-vat-delivery" placeholder="0.00" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <button class="submit-button hide" type="submit">submit</button>
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
    const inputTriggerClasses = '.buffer, .ideal-food-cost, .final-srp-w-vat-dine-in';
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');
        
        function firstLoad() {
            $('.portion-size').val(parseFloat(item.portion_size) || 0);
            $('.recipe-cost-wo-buffer').val(parseFloat(item.recipe_cost_wo_buffer || 0));
            $('.buffer').val(parseFloat(item.buffer || 6.5));

            $('.packaging-cost').val(parseFloat(item.packaging_cost || 0));
            $('.ideal-food-cost').val(parseFloat(item.ideal_food_cost || 30));
            $('.final-srp-w-vat-dine-in').val(parseFloat(item.final_srp_w_vat_dine_in || 0));
            $('.final-srp-w-vat-take-out').val(parseFloat(item.final_srp_w_vat_take_out || item.final_srp_w_vat_dine_in || 0));
            $('.final-srp-w-vat-delivery').val(parseFloat(item.final_srp_w_vat_delivery || item.final_srp_w_vat_dine_in || 0));
        }

        function computeFormula() {
            const portionSize = $('.portion-size').val();
            const recipeCostWithoutBuffer = $('.recipe-cost-wo-buffer').val();
            const buffer = $('.buffer').val();
            
            const packagingCost = $('.packaging-cost').val();
            const idealFoodCost = $('.ideal-food-cost').val();
            const finalSrpWithVat = $('.final-srp-w-vat-dine-in').val();

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

            formatTotalCost();
        }

        function formatTotalCost() {
            const totalCost = $('.total-cost').val();
            const idealFoodCost = $('.ideal-food-cost').val();
            if (parseFloat(totalCost) > parseFloat(idealFoodCost)) {
                $('.total-cost').css({
                    color: 'red',
                    border: '2px solid red',
                });
            } else {
                $('.total-cost').css({
                    color: 'unset',
                    border: 'unset',
                });
            }
        }


        function priceIsValid() {
            const dineIn = $('.final-srp-w-vat-dine-in').val();
            const takeOut = $('.final-srp-w-vat-take-out').val();
            const delivery = $('.final-srp-w-vat-delivery').val();
            return parseFloat(dineIn) <= parseFloat(takeOut) && parseFloat(dineIn) <= parseFloat(delivery);
        }

        $(document).on('keyup', inputTriggerClasses, function() {
            computeFormula();
        });

        $('#save-btn').on('click', function() {
            const validPrice = priceIsValid();
            if (validPrice) {
                Swal.fire({
                    title: 'Do you want to save the changes?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Save',
                    returnFocus: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('.submit-button').click();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Oops..',
                    text: 'Please double check the SRPs. Prices for delivery and take out cannot be less than the dine in price.',
                    icon: 'error',
                });
            }
        });

        firstLoad();
        computeFormula();
    });

</script>



@endpush