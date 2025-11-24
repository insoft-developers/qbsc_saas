<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('laporan.situasi.table') }}',
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
                data: 'satpam_id',
                name: 'satpam_id'
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'laporan',
                name: 'laporan'
            },
            {
                data: 'foto',
                name: 'foto'
            },
            {
                data: 'comid',
                name: 'comid'
            },

        ]
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
                    url: "{{ url('laporan_situasi') }}" + "/" + id,
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

    

    $(document).on('click', '.read-more', function() {
        let parent = $(this).closest('td');
        parent.find('.laporan-short').hide();
        parent.find('.laporan-full').show();
        $(this).text('Tutup').removeClass('read-more').addClass('read-less');
    });

    $(document).on('click', '.read-less', function() {
        let parent = $(this).closest('td');
        parent.find('.laporan-short').show();
        parent.find('.laporan-full').hide();
        $(this).text('Selengkapnya').removeClass('read-less').addClass('read-more');
    });
</script>
