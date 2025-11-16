@include('frontend.aktivitas.patroli_kandang.kandang_suhu_js')
@include('frontend.aktivitas.patroli_kandang.kandang_kipas_js')
@include('frontend.aktivitas.patroli_kandang.kandang_alarm_js')
@include('frontend.aktivitas.patroli_kandang.kandang_lampu_js')
<script>

    $('#btnFilter').on('click', function() {
        table_suhu.ajax.reload();
        table_kipas.ajax.reload();
        table_alarm.ajax.reload();
        table_lampu.ajax.reload();
    });

    $('#btnReset').on('click', function() {
        $('#filter_start').val('');
        $('#filter_end').val('');
        $('#filter_satpam').val('');
        $('#filter_kandang').val('');
        table_suhu.ajax.reload();
        table_kipas.ajax.reload();
        table_alarm.ajax.reload();
        table_lampu.ajax.reload();
    });

    $('#btnExportXls').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            kandang_id: $('#filter_kandang').val() || ''
        });

        window.location.href = "{{ route('patroli.kandang.export.xls') }}?" + params;
    });

    $('#btnExportPdf').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            kandang_id: $('#filter_kandang').val() || ''
        });

        window.location.href = "{{ route('patroli.kandang.export.pdf') }}?" + params;

    });

    function deleteData(id, type) {
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
                    url: "{{ url('patroli_kandang') }}" + "/" + id,
                    type: 'DELETE',
                    data: {
                        'type':type,
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
        table_suhu.ajax.reload(null, false);
        table_kipas.ajax.reload(null, false);
        table_alarm.ajax.reload(null, false);
        table_lampu.ajax.reload(null, false);
    }

   
</script>
