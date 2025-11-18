<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('doc.out.table') }}",
            data: function(d) {
                d.start_date = $('#filter_start').val();
                d.end_date = $('#filter_end').val();
                d.satpam_id = $('#filter_satpam').val();
                d.ekspedisi_id = $('#filter_ekspedisi').val();
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
                data: 'input_date',
                name: 'input_date'
            },
            {
                data: 'satpam_id',
                name: 'satpam_id'
            },
            {
                data: 'jumlah',
                name: 'jumlah'
            },
            {
                data: 'ekspedisi_id',
                name: 'ekspedisi_id'
            },
            {
                data: 'tujuan',
                name: 'tujuan'
            },
            {
                data: 'no_polisi',
                name: 'no_polisi'
            },
            {
                data: 'jenis',
                name: 'jenis'
            },
            {
                data: 'note',
                name: 'note'
            },
            {
                data: 'foto',
                name: 'foto'
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
        table.ajax.reload();
    });

    $('#btnReset').on('click', function() {
        $('#filter_start').val('');
        $('#filter_end').val('');
        $('#filter_satpam').val('');
        $('#filter_ekspedisi').val('');
        table.ajax.reload();
    });

    $('#btnExportXls').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            ekspedisi_id: $('#filter_ekspedisi').val() || ''
        });

        window.location.href = "{{ route('doc.export.xls') }}?" + params;
    });

    $('#btnExportPdf').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            ekspedisi_id: $('#filter_ekspedisi').val() || ''
        });

        window.location.href = "{{ route('doc.export.pdf') }}?" + params;

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
                    url: "{{ url('doc_out') }}" + "/" + id,
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
