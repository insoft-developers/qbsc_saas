<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('asset.page.table') }}',
        order: [
            [0, 'desc']
        ],
        columns: [{
                data: 'id',
                name: 'id',
                orderable: true,
                visible: false
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'asset_name',
                name: 'asset_name'
            },
            {
                data: 'android_link',
                name: 'android_link'
            },
            {
                data: 'ios_link',
                name: 'ios_link'
            },
            {
                data: 'asset_description',
                name: 'asset_description'
            },
            
            {
                data: 'created_at',
                name: 'created_at'
            },
            

        ]
    });

    

    $(document).on('click', '.android-link', function() {
        let link = $(this).data('link');

        let temp = $('<input>');
        $('body').append(temp);
        temp.val(link).select();
        document.execCommand("copy");
        temp.remove();

        Swal.fire({
            icon: 'success',
            title: 'Link berhasil disalin!',
            text: link,
            timer: 1500,
            showConfirmButton: false
        });
    });


    $(document).on('click', '.ios-link', function() {
        let link = $(this).data('link');
       
        let temp = $('<input>');
        $('body').append(temp);
        temp.val(link).select();
        document.execCommand("copy");
        temp.remove();

        Swal.fire({
            icon: 'success',
            title: 'Link berhasil disalin!',
            text: link,
            timer: 1500,
            showConfirmButton: false
        });
    });
</script>
