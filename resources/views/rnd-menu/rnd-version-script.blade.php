@push('head')
    <link rel="stylesheet" href="{{ asset('css/swal-table.css') }}">
    <script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
@endpush

<script>
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
                const value = dataObject[header];
                $('<td>').text(isNaN(value) ? value : Number(value)).appendTo(bodyRow);
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


    $('.show-rnd-version').on('click', function() {
        const id = $(this).attr('rnd-menu-items-id');
        const versionTypes = $(this).attr('version-type').split(' ').filter(e => e);

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
                rnd_menu_items_id: id,
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
    });
</script>