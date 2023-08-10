@push('head')
    <link rel="stylesheet" href="{{ asset('css/swal-table.css') }}">
    <script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
@endpush

<script>
    async function showHistoryChoices(dataObj) {
        const inputOptions = {};
        const historyTypes = Object.keys(dataObj);
        historyTypes.forEach(historyType => {
            inputOptions[historyType.toUpperCase()] = {}
            dataObj[historyType].forEach(row => {
                const key = row.created_at;
                inputOptions[historyType.toUpperCase()][`${historyType}_${key}`] = key;
            });
        });


        const { value: chosen } = await Swal.fire({
            title: 'Menu Details History',
            input: 'select',
            inputOptions,
            inputPlaceholder: 'Select Menu Detail History',
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

        if (chosen) {
            const [historyType, history] = chosen.split('_');
            const chosenValue = dataObj[historyType].find(e => e.created_at === history);
            showHistory(chosenValue);
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
            'id',
            'menu_packagings_details_id',
        ];

        const headers = Object.keys(rows[0]).filter(key => !notIncludedKeys.includes(key));
        const headerRow = $('<tr>');
        headers.forEach(header => {
            const headerName = header.replace(/_/g, ' ').toUpperCase();
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

    $(document).on('click', '.show-menu-history', function() {
        const menuItemId = $(this).attr('menu-items-id');
        let historyType = $(this).attr('history-type');
        historyType = historyType.split(' ');
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
            url: '{{ route('get_menu_detail_history') }}',
            data: { menu_items_id: menuItemId, history_type: JSON.stringify(historyType), _token: '{{ csrf_token() }}',},
            success: function(response) {
                Swal.close();
                const data = JSON.parse(response);
                showHistoryChoices(data);
            },
            error: function(response) { 
                console.log(response);
                Swal.close();
                
            }  
        });
    });
</script>