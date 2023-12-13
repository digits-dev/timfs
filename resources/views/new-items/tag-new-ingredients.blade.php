@extends('crudbooster::admin_template')
@section('content')
<p class="noprint">
    <a title='Return' href="{{ CRUDBooster::mainPath() }}">
        <i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}
    </a>
</p>      
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> Tag {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-6">
                <form method="POST" action="{{ route('tag_new_ingredient', $item->new_ingredients_id) }}" id="tagging-form" autocomplete="off">
                    @csrf
                    <label for="">Tasteless Code</label>
                    <div class="flex">
                        <input value="{{ $item->tasteless_code }}" type="text" id="tasteless-code" class="form-control tasteless-code" name="tasteless_code" placeholder="Enter tasteless Code" {{ $item->tasteless_code ? 'readonly' : '' }}>
                        <button type="button" id="tag-btn" class="btn btn-primary" style="margin-left: 5px" {{ $item->tasteless_code ? 'disabled' : '' }}><i class="fa fa-tag"></i> Tag</button>
                    </div>
                </form>
            </div>
                <div class="col-sm-6">
                    <form method="POST" id="sourcing-form" action="{{ route('new_ingredients_submit_sourcing_status', $item->new_ingredients_id) }}">
                        @csrf
                        <label for="">Sourcing Status</label>
                        <div class="flex">
                            <select class="form-control" name="item_sourcing_statuses_id" id="item_sourcing_statuses_id">
                                @if($item->sourcing_status == 'CLOSED')
                                <option value="" selected>CLOSED</option>
                                @else
                                    @foreach ($sourcing_statuses as $sourcing_status)
                                    <option value="{{ $sourcing_status->id }}" {{ $item->sourcing_status == $sourcing_status->status_description ? 'selected' : '' }}>{{ $sourcing_status->status_description }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <button type="button" id="save-btn" class="btn btn-primary" style="margin-left: 5px"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <hr>
                <h3 class="text-center">ITEM DETAILS</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Tasteless Code</th>
                                <td>{{$item->tasteless_code}}</td>
                            </tr>
                            <tr>
                                <th>{{$table == 'new_ingredients' ? 'NWI Code' : 'NWP Code'}}</th>
                                <td>{{$item->nwi_code ?? $item->nwp_code}}</td>
                            </tr>
                            <tr>
                                <th>Item Type</th>
                                <td>{{$item->item_type_description}}</td>
                            </tr>
                            <tr>
                                <th>Item Description</th>
                                <td>{{$item->item_description}}</td>
                            </tr>
                            <tr>
                                <th>Packaging Size</th>
                                <td>{{(float) $item->packaging_size}}</td>
                            </tr>
                            <tr>
                                <th>UOM</th>
                                <td>{{$item->uom_description}}</td>
                            </tr>
                            <tr>
                                <th>TTP</th>
                                <td>{{(float) $item->ttp}}</td>
                            </tr>
                            <tr>
                                <th>Segmentations</th>
                                <td>
                                    @foreach ($segmentations as $segmentation)
                                    <span class="label label-primary">{{$segmentation}}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Reason</th>
                                <td>{{$item->reasons_description}}</td>
                            </tr>
                            @if($item->reasons_description == 'REPLACEMENT')
                                <tr>
                                    <th>Exisiting Ingredient</th>
                                    <td><b>{{ $item->existing_ingredient_code }}</b> - {{$item->existing_ingredient}}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Recommended Brand 1</th>
                                <td>{{$item->recommended_brand_one}}</td>
                            </tr>
                            <tr>
                                <th>Recommended Brand 2</th>
                                <td>{{$item->recommended_brand_two}}</td>
                            </tr>
                            <tr>
                                <th>Recommended Brand 3</th>
                                <td>{{$item->recommended_brand_three}}</td>
                            </tr>
                            <tr>
                                <th>Initial Qty Needed</th>
                                <td>{{(float)$item->initial_qty_needed}} {{$item->initial_qty_uoms}}</td>
                            </tr>
                            <tr>
                                <th>Forecast Qty Needed</th>
                                <td>{{(float)$item->forecast_qty_needed}} {{$item->forecast_qty_uoms}}</td>
                            </tr>
                            <tr>
                                <th>Budget Range</th>
                                <td>{{$item->budget_range}}</td>
                            </tr>
                            <tr>
                                <th>Reference Link</th>
                                <td>{{$item->reference_link}}</td>
                            </tr>
                            <tr>
                                <th>Term</th>
                                <td>{{$item->ingredient_terms}}</td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td>{{$item->duration}}</td>
                            </tr>
                            <tr>
                                <th>Created by</th>
                                <td>{{$item->creator_name}}</td>
                            </tr>
                            <tr>
                                <th>Created Date</th>
                                <td>{{$item->created_at}}</td>
                            </tr>
                            @if ($item->updator_name)
                            <tr>
                                <th>Updated By</th>
                                <td>{{$item->updator_name}}</td>
                            </tr>
                            @endif
                            @if ($item->updated_at)
                            <tr>
                                <th>Updated Date</th>
                                <td>{{$item->updated_at}}</td>
                            </tr>
                            @endif
                            @if ($item->tagger_name)
                            <tr>
                                <th>Tagged By</th>
                                <td>{{$item->tagger_name}}</td>
                            </tr>
                            @endif
                            @if ($item->approver_name)
                            <tr>
                                <th>Approval Status Updated By</th>
                                <td>{{$item->approver_name}}</td>
                            </tr>
                            @endif
                            @if ($item->approval_status_updated_at)
                            <tr>
                                <th>Approval Status Updated Date</th>
                                <td>{{$item->approval_status_updated_at}}</td>
                            </tr>
                            @endif
                            @if ($item->sourcer_name)
                            <tr>
                                <th>Sourcing Status Updated By</th>
                                <td>{{$item->sourcer_name}}</td>
                            </tr>
                            @endif
                            @if ($item->sourcing_status_updated_at)
                            <tr>
                                <th>Sourcing Status Updated Date</th>
                                <td>{{$item->sourcing_status_updated_at}}</td>
                            </tr>
                            @endif
                            @if ($item->tagged_at)
                            <tr>
                                <th>Tagged Date</th>
                                <td>{{$item->tagged_at}}</td>
                            </tr>
                            @endif
                            @if ($item->item_masters_id)
                            <tr>
                                <th>View Item Masters Details</th>
                                <td><a href="{{CRUDBooster::adminPath('item_masters/detail/' . $item->item_masters_id)}}" target="_blank">View Details</a></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <hr>
                <h3 class="text-center">COMMENTS</h3>
                <div class="chat-app">
                    @include('new-items/chat-app', $comments_data)
                </div>
                <div class="col-md-12">
                    <hr>
                    <h3 class="text-center">ITEM USAGE</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center">Item Description</th>
                                    <th class="text-center">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$item_usages)
                                <tr><td class="text-center" style="font-style: italic; color: grey" colspan="3">This item is currently not in use...</td></tr>
                                @endif
                                @foreach ($item_usages as $item_usage)
                                <tr>
                                    <td class="text-center">{{ $item_usage->item_code }}</td>
                                    <td class="text-center">{{ $item_usage->item_description }}</td>
                                    <td class="text-center">{{ $item_usage->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <a class="btn btn-default" href="{{ CRUDBooster::mainpath() }}" type="button"> Cancel </a>
    </div>
</div>




@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        function showSwalForTagging() {
            const tastelessCode = $('#tasteless-code').val().trim();
            if (!tastelessCode) {
                return;
            }
            Swal.fire({
                title: 'Fetching...',
                html: 'Please wait...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('search_item_for_tagging') }}",
                data: { tasteless_code:  tastelessCode, _token: "{{ csrf_token() }}",},
                success: function(response) {
                    const data = JSON.parse(response) || {};
                    let html = `⚠️ You won't be able to revert this action. This will update all rnd, menu, and batching that use this item.`;
                    if (data.full_item_description) {
                        html += '<br>';
                        html += `<strong>Item:</strong> ${data.full_item_description}`;
                    }
                    Swal.close();
                    Swal.fire({
                        title: 'Do you want to tag to this item?',
                        html,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Save',
                        returnFocus: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#tagging-form').submit();
                        }
                    });
                },
                error: function(response) { 
                    console.log(response);
                    Swal.close();
                    $('#submit-btn').click();
                }  
            });
        }

        function showSwalForSourcing() {
            const taggedItem = "{{ $item->item_masters_id }}";
            const selectedSourcingStatus = $("#item_sourcing_statuses_id option:selected").text()
            let html = null;
            if (selectedSourcingStatus == 'CLOSED' && !taggedItem) {
                html = '⚠️ Are you sure you want to update the status of this item sourcing to <span class="label label-success">CLOSED</span> without tagging?'
            }
            Swal.fire({
                title: 'Do you want save the changes?',
                html,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Save',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#sourcing-form').submit();
                }
            });
        }

        $('#tasteless-code').on('keypress', function(event) {
            if (event.keyCode === 13) {
                console.log('heey');
                event.preventDefault();
                showSwalForTagging();
            }
        });

        $('#tag-btn').on('click', showSwalForTagging);
        $('#save-btn').on('click', showSwalForSourcing);
    });
</script>


@endpush