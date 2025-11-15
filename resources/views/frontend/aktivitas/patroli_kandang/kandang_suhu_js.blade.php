<script>
    var table_suhu = $('#table-suhu').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('kandang.suhu.table') }}",
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
                data: 'temperature',
                name: 'temperature'
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


    $('#btnFilter').on('click', function() {
        table_suhu.ajax.reload();
    });

    $('#btnReset').on('click', function() {
        $('#filter_start').val('');
        $('#filter_end').val('');
        $('#filter_satpam').val('');
        $('#filter_lokasi').val('');
        table_suhu.ajax.reload();
    });

    $('#btnExportXls').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            location_id: $('#filter_lokasi').val() || ''
        });

        window.location.href = "{{ route('patroli.export.xls') }}?" + params;
    });

    $('#btnExportPdf').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            location_id: $('#filter_lokasi').val() || ''
        });

        window.location.href = "{{ route('patroli.export.pdf') }}?" + params;

    });

    function deleteData(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('patroli') }}" + "/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        reloadTable();
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.',
                            'error');
                    }
                });
            }
        });
    }



    function reloadTable() {
        table.ajax.reload(null, false);
    }

   
</script>
