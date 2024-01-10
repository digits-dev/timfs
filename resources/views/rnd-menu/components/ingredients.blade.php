@push('head')
<style>
    .ingredient-component-section .primary_ingredient_description {
        background: yellow;
    }

    .ingredient-component-section th, .ingredient-component-section tbody td {
        border: 1px solid #aaaaaa !important;
        text-align: center;
    }

    .ingredient-component-section td {
        border: 1px solid #aaaaaa !important;
    }

    .ingredient-component-section thead{
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

<div class="ingredient-component-section">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Status</th>
                    <th scope="col">From</th>
                    <th scope="col">Item Code</th>
                    <th scope="col">Ingredient</th>
                    <th scope="col">Packaging Size</th>
                    <th scope="col">Preparation Qty</th>
                    <th scope="col">UOM</th>
                    <th scope="col">Yield</th>
                    <th scope="col">TTP</th>
                    <th scope="col">Ingredient Qty</th>
                    <th scope="col">Cost</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grouped_ingredients = [];
                    foreach ($ingredients as $ingredient) {
                        $key = $ingredient->ingredient_group;
                        $grouped_ingredients[$key][] = $ingredient;
                    }

                @endphp
                @foreach ($grouped_ingredients as $group)
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
                    @foreach ($group as $ingredient)
                        @php
                            $status = $ingredient->menu_item_status ??
                                    $ingredient->item_status ??
                                    $ingredient->new_ingredient_status ??
                                    $ingredient->batching_ingredient_status;

                            $description = $ingredient->full_item_description ??
                                    $ingredient->menu_item_description ??
                                    $ingredient->ingredient_description ??
                                    $ingredient->item_description;

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
                                @if ($ingredient->item_masters_id)
                                <span class="label label-info">IMFS</span>
                                @elseif ($ingredient->menu_item_description)
                                <span class="label label-warning">MIMF</span>
                                @elseif ($ingredient->ingredient_description)
                                <span class="label label-secondary">BATCH</span>
                                @else
                                <span class="label label-success">NEW</span>
                                @endif
                            </td>
                            <td>{{ $ingredient->item_code }}</td>
                            <td>
                                <span class="{{ $ingredient->is_checked ? 'primary_ingredient_description' : '' }}">
                                    {{ $description }}
                                </span>
                            </td>
                            <td>{{ (float) $ingredient->packaging_size }}</td>
                            <td>{{ (float) $ingredient->prep_qty }}</td>
                            <td>{{ $ingredient->packaging_description ?? $ingredient->uom_description }}</td>
                            <td>{{ (float) $ingredient->yield }}%</td>
                            <td>
                                {{ (float) $ingredient->ttp }}
                                @if ($ingredient->item_masters_id)
                                    <br>
                                    <span class="timeago date-updated" datetime="{{ $ingredient->updated_at ?? $ingredient->created_at }}"></span>
                                @endif
                            </td>
                            <td>{{ (float) $ingredient->ingredient_qty }}</td>
                            <td>{{ (float) $ingredient->cost }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-bold">
                    <td colspan="10" class="text-bold text-right">Total Ingredient Cost</td>
                    <td>{{ (float) $item->recipe_cost_wo_buffer }}</td>
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