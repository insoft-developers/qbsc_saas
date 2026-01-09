<script>
var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reseller.user.table') }}',
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
                data: 'profile_image',
                name: 'profile_image'
            },
            {
                data: 'comname',
                name: 'comname'
            },
            {
                data: 'owner_name',
                name: 'owner_name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'whatsapp',
                name: 'whatsapp'
            },
            {
                data: 'paket_id',
                name: 'paket_id'
            },
            {
                data: 'expired_date',
                name: 'expired_date'
            },
            {
                data: 'status_paket',
                name: 'status_paket'
            },

            {
                data: 'register_date',
                name: 'register_date'
            },

        ]
    });

</script>