<script>
    var table_alarm = $('#table-alarm').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('kandang.alarm.table') }}",
            data: function(d) {
                d.start_date = $('#filter_start').val();
                d.end_date = $('#filter_end').val();
                d.satpam_id = $('#filter_satpam').val();
                d.kandang_id = $('#filter_kandang').val();
            }
        },

        order: [
            [3, 'desc'],
            [4, 'desc']
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
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'jam',
                name: 'jam'
            },
            {
                data: 'kandang_id',
                name: 'kandang_id'
            },
            {
                data: 'satpam_id',
                name: 'satpam_id'
            },
            {
                data: 'is_alarm_on',
                name: 'is_alarm_on'
            },
            
            {
                data: 'latitude',
                name: 'latitude'
            },
            {
                data: 'foto',
                name: 'foto'
            },
            {
                data: 'note',
                name: 'note'
            },

            {
                data: 'comid',
                name: 'comid'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },

        ]
    });

</script>
