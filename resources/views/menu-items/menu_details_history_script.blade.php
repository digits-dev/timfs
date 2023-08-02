@push('head')
    <link rel="stylesheet" href="{{ asset('css/swal-table.css') }}">
    <script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
@endpush

<script>
    async function showHistoryChoices(dataArray, historyType) {
        const inputOptions = {};
        inputOptions[historyType.toUpperCase()] = {};
        dataArray.forEach(row => {
            const key = row.created_at;
            inputOptions[key] = key;
        });

        const { value: history } = await Swal.fire({
            title: 'Menu Details History',
            input: 'select',
            inputOptions,
            inputPlaceholder: `Select ${historyType} history.`,
            showCancelButton: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value) {
                        resolve();
                    } else {
                        resolve('Please choose one.');
                    }
                });
            }
        });

        if (history) {
            const chosen = dataArray.find(e => e.created_at === history);
            showHistory(chosen);
        }
    }

    function showHistory({created_at, history_json, name}) {
        Swal.close()
        const rows = JSON.parse(history_json);
        const table = $('<table>').addClass('swal-table');
        const notIncludedKeys = [
            'menu_ingredients_details_id', 
            'menu_items_id', 
            'tasteless_menu_code', 
            'menu_item_description',
            'id'
        ];

        const headers = Object.keys(rows[0]).filter(key => !notIncludedKeys.includes(key));
        const headerRow = $('<tr>');
        headers.forEach(header => {
            const headerName = header.replace(/_/g, ' ').toUpperCase()
            $('<th>').addClass('text-center').text(headerName).appendTo(headerRow);
        });
        table.append(headerRow);

        rows.forEach(dataObject => {
            const bodyRow = $('<tr>');
            headers.forEach(header => {
                $('<td>').text(dataObject[header]).appendTo(bodyRow);
            });
            table.append(bodyRow);
        });
        const outerHTML = $('<div>').addClass('swal-table-wrapper').append(table).prop('outerHTML');
        Swal.fire({
            title: 'Menu Detail History: ' + created_at,
            html: outerHTML,
            width: '800px',
            showConfirmButton: false,
            showCloseButton: true,
            footer: `History created by ${name} ${timeago.format(created_at)}.`
        });
    } 

    $(document).on('click', '#show-menu-history', function() {
        const menuItemId = $(this).attr('menu-items-id');
        const historyType = $(this).attr('history-type');
        Swal.fire({
            title: 'Fetching...',
            html: 'Please wait...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });
        $.ajax({
            type: 'POST',
            url: '{{ route('get_menu_detail_history') }}',
            data: { menu_items_id: menuItemId, history_type: historyType, _token: '{{ csrf_token() }}',},
            success: function(response) {
                Swal.close();
                const data = JSON.parse(response) || [];
                showHistoryChoices(data, historyType);
            },
            error: function(response) { 
                console.log(response);
                Swal.close();
                
            }  
        });
    });
</script>