<script>
var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reseller.transaction.table') }}',
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
                searchable: false,
                visible: false,
            },
            
            {
                data: 'comname',
                name: 'comname'
            },
            {
                data: 'invoice',
                name: 'invoice'
            },
            
            {
                data: 'paket_id',
                name: 'paket_id'
            },
            {
                data: 'userid',
                name: 'userid'
            },
            {
                data: 'amount',
                name: 'amount'
            },

            {
                data: 'payment_status',
                name: 'payment_status'
            },
            {
                data: 'reference',
                name: 'reference'
            },

            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },

        ]
    });

</script>

{{-- <th>ID</th>
<th class="text-center" width="5%">No</th>
<th class="text-center">Aksi</th>
<th>Perusahaan</th>
<th>Invoice</th>
<th>Paket</th>
<th>User</th>
<th>Pembayaran</th>
<th>Status</th>
<th>Reference</th>
<th>Keterangan</th>
<th>Tanggal</th> --}}