<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('laporan.kinerja.table') }}",
            data: function(d) {
                d.periode = $('#filter_periode').val();
                d.tahun = $('#filter_tahun').val(); 
            }
        },
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
                data: 'foto',
                name: 'foto'
            },
            {
                data: 'satpam_id',
                name: 'satpam_id'
            },
            {
                data: 'jabatan',
                name: 'jabatan'
            },
            {
                data: 'hadir',
                name: 'hadir'
            },

            {
                data: 'tepat_waktu',
                name: 'tepat_waktu'
            },
            {
                data: 'terlambat',
                name: 'terlambat'
            },

            {
                data: 'total_terlambat',
                name: 'total_terlambat'
            },
            {
                data: 'cepat_pulang',
                name: 'cepat_pulang'
            },
            {
                data: 'total_cepat_pulang',
                name: 'total_cepat_pulang'
            },
            {
                data: 'total_patroli',
                name: 'total_patroli'
            },
            {
                data: 'patroli_diluar_jadwal',
                name: 'patroli_diluar_jadwal'
            },
            {
                data: 'comid',
                name: 'comid'
            },

        ]
    });


    $('#btnFilter').on('click', function() {
        table.ajax.reload();
    });

    $('#btnReset').on('click', function() {
       const now = new Date();

        // bulan: 01 - 12
        const bulan = String(now.getMonth() + 1).padStart(2, '0');
        const tahun = now.getFullYear();

        $('#filter_periode').val(bulan);
        $('#filter_tahun').val(tahun);
        
        table.ajax.reload();
    });

    $('#btnExportXls').on('click', function() {
        let params = $.param({
            periode: $('#filter_periode').val() || '',
            tahun: $('#filter_tahun').val() || '',           
        });

        window.location.href = "{{ route('kinerja.export.xls') }}?" + params;
    });

    $('#btnExportPdf').on('click', function() {
        let params = $.param({
            periode: $('#filter_periode').val() || '',
            tahun: $('#filter_tahun').val() || '',           
        });

        window.location.href = "{{ route('kinerja.export.pdf') }}?" + params;

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
