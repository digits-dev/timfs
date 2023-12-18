@push('head')
<style>
    .packaging-component-section .primary_packaging_description {
        background: yellow;
    }

    .packaging-component-section th, .packaging-component-section tbody td {
        border: 1px solid #aaaaaa !important;
        text-align: center;
    }

    .packaging-component-section td {
        border: 1px solid #aaaaaa !important;
    }

    .packaging-component-section thead {
        background: #deeaee;
    }

    .label-secondary {
        background: #7e57c2;
    }

    .date-updated {
        font-size: 11px;
        font-style: italic;
        color: slategrey;
    }
</style>
    
@endpush

<div class="packaging-component-section">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Status</th>
                    <th scope="col">From</th>
                    <th scope="col">Item Code</th>
                    <th scope="col">Packaging</th>
                    <th scope="col">Packaging Size</th>
                    <th scope="col">Packaging Qty</th>
                    <th scope="col">UOM</th>
                    <th scope="col">TTP</th>
                    <th scope="col">Cost</th>
                </tr>
            </thead>
            <tbody class="packaging-tbody">
                @php
                    $grouped_packagings = [];
                    foreach ($packagings as $packaging) {
                        $key = $packaging->packaging_group;
                        $grouped_packagings[$key][] = $packaging;
                    }

                @endphp
                @foreach ($grouped_packagings as $group)
                    @php
                        $primary = array_filter($group, fn($obj) => $obj->is_selected == 'TRUE');
                        $column_name = !!$primary ? 'is_selected' : 'is_primary';
                        $group = array_map(function($obj) use ($column_name) {
                            if ($obj->{$column_name} == 'TRUE') {
                                $obj->is_checked = 'TRUE';
                            }
                            return $obj;
                        }, $group);

                    @endphp
                    @foreach ($group as $packaging)
                        @php
                            $status = $packaging->menu_item_status ??
                                    $packaging->item_status ??
                                    $packaging->new_packaging_status ??
                                    $packaging->batching_packaging_status;

                            $description = $packaging->full_item_description ??
                                    $packaging->item_description;

                        @endphp
                        <tr>
                            <td>
                                @if ($status == 'INACTIVE')
                                <span class="label label-danger">INACTIVE</span>
                                @elseif ($status == 'ACTIVE')
                                <span class="label label-success">ACTIVE</span>
                                @else
                                <span class="label label-primary">{{ $status }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($packaging->item_masters_id)
                                <span class="label label-info">IMFS</span>
                                @else
                                <span class="label label-success">NEW</span>
                                @endif
                            </td>
                            <td>{{ $packaging->item_code }}</td>
                            <td>
                                <span class="{{ $packaging->is_checked ? 'primary_packaging_description' : '' }}">
                                    {{ $description }}
                                </span>
                            </td>
                            <td>{{ (float) $packaging->packaging_size }}</td>
                            <td>{{ (float) $packaging->prep_qty }}</td>
                            <td>{{ $packaging->packaging_description ?? $packaging->uom_description }}</td>
                            <td>
                                {{ (float) $packaging->ttp }}
                                @if ($packaging->item_masters_id)
                                    <br>
                                    <span class="timeago date-updated" datetime="{{ $packaging->updated_at ?? $packaging->created_at }}"></span>
                                @endif
                            </td>
                            <td>{{ (float) $packaging->cost }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-bold">
                    <td colspan="8" class="text-bold text-right">Total packaging Cost</td>
                    <td>{{ (float) $item->packaging_cost }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@push('bottom')
<script>
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');

        const timeAgoNodes = $('.timeago').get();
        if (timeAgoNodes.length) timeago.render(timeAgoNodes);
        $('table th, table td').css('border', '1px solid #aaaaaa').css('vertical-align', 'middle');
        $('table thead').css('background', '#deeaee');
    });
    
</script>
@endpush