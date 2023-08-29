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

    function fetchMenuHistory(menuItemId, historyType) {
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
            data: { menu_items_id: menuItemId, history_type: JSON.stringify(historyType), _token: '{{ csrf_token() }}'},
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
    }

    function fetchRNDVersions(rndMenuItemsId, versionTypes) {
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
            url: "{{ route('get_rnd_versions') }}",
            method: 'GET',
            data: {
                rnd_menu_items_id: rndMenuItemsId,
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                const data = JSON.parse(response);
                Swal.close();
                console.log(data);
                showVersionChoices(data, versionTypes);
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    async function showVersionChoices(data, versionTypes) {
        const versionInputOptions = {};
        for (const version of data) {
            versionInputOptions[`${version.version_id} | ${timeago.format(version.updated_at || version.created_at)}`] = {};
            for (const type of versionTypes) {
                versionInputOptions[`${version.version_id} | ${timeago.format(version.updated_at || version.created_at)}`][`${version.version_id}.${type}`] = type.toUpperCase();
            }
        }


        const { value: chosenVersion } = await Swal.fire({
            title: 'RND Versions',
            input: 'select',
            inputOptions: versionInputOptions,
            inputPlaceholder: 'Select RND Menu Version',
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

        if (chosenVersion) {
            const [versionId, versionDetail] = chosenVersion.split('.');
            const chosenData = data.find(e => e.version_id === versionId);
            showVersionDetail(chosenData, versionDetail);
        }
    }

    function showVersionDetail(data, versionDetail) {
        const versionData = JSON.parse(data.history_json);
        let rows = versionData[versionDetail];
        if (!Array.isArray(rows)) {
            rows = [rows];
        }
        const table = $('<table>').addClass('swal-table');
        const notIncludedKeys = [
            'menu_ingredients_details_id', 
            'rnd_menu_ingredients_details_id', 
            'menu_items_id',
            'rnd_code', 
            'rnd_menu_items_id', 
            'tasteless_menu_code', 
            'menu_item_description',
            'id',
            'menu_packagings_details_id',
            'rnd_menu_packagings_details_id',
            'deleted_at',
            'pos_update',
            'end_date',
            'release_date',
            'segmentations_id',
            'updated_by',
            'created_by',
            'updated_at',
            'created_at',
            'status',
        ];

        if (['packaging', 'ingredient', 'costing'].includes(versionDetail)) {
            notIncludedKeys.push('rnd_menu_description');
        }

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
        const isUpdated = !!data.updated_at;
        Swal.fire({
            title: `${data.rnd_code}.${data.version_id} - ${versionDetail.toUpperCase()}`,
            html: outerHTML,
            width: '800px',
            showConfirmButton: false,
            showCloseButton: true,
            footer: `Version ${isUpdated ? 'updated' : 'created'} by ${data.updater || data.creator} ${timeago.format(data.updated_at || data.created_at)}.`
        });
    }

    $(document).on('click', '.show-menu-history', function() {
        const menuItemId = $(this).attr('menu-items-id');
        const rndMenuItemsId = $(this).attr('rnd-menu-items-id');
        const historyType = $(this).attr('history-type')?.split(' ');
        const versionType = $(this).attr('version-type')?.split(' ');
        Swal.fire({
            title: 'Which history do you want to see?',
            showDenyButton: true,
            showCloseButton: true,
            confirmButtonColor: '#3085d6',
            denyButtonColor: '#008d4c',
            confirmButtonText: '🕒 Menu History',
            denyButtonText: '📃 RND Versions',
            cancelButtonText: 'Cancel',
            returnFocus: false,
        }).then((result) => {
            if (result.isConfirmed) {
                fetchMenuHistory(menuItemId, historyType);
            } else if (result.isDenied) {
                fetchRNDVersions(rndMenuItemsId, versionType);
            }
        });
    });
</script>