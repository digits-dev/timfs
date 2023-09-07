@push('head')
<link rel="stylesheet" href="{{ asset('css/swal-table.css') }}">
@endpush
<div class="col-md-1">
    <label for=""> </label>
    <button class="btn btn-primary show-items-without-code-button" type="button">Show Items</button>
</div>

<div class="hide no-item-code-modal">
    <table class="swal-table">
        <thead>
            <tr>
                <th class="text-center">Item Status</th>
                <th class="text-center">Item Code</th>
                <th class="text-center">Item Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($no_codes as $new_item)
            <tr>
                <td>{{ $new_item->status }}</td>
                <td>{{ $new_item->item_code }}</td>
                <td>{{ $new_item->item_description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('bottom')
<script>
    $('.show-items-without-code-button').on('click', function() {
        const outerHTML = $('.no-item-code-modal').clone().removeClass('hide').prop('outerHTML');
        console.log(outerHTML);
        Swal.fire({
            title: 'New Items Used',
            html: outerHTML,
            width: '800px',
            showConfirmButton: false,
            showCloseButton: true,
        });
    });
</script>
@endpush