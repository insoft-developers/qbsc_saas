<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('absensi.table') }}",
            data: function(d) {
                d.start_date = $('#filter_start').val();
                d.end_date = $('#filter_end').val();
                d.satpam_id = $('#filter_satpam').val();
                d.status = $('#filter_status').val();
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
                searchable: false
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'satpam_id',
                name: 'satpam_id'
            },
            {
                data: 'latitude',
                name: 'latitude'
            },
            {
                data: 'shift_name',
                name: 'shift_name'
            },
            {
                data: 'jam_setting_masuk',
                name: 'jam_setting_masuk'
            },
            {
                data: 'jam_masuk',
                name: 'jam_masuk'
            },
            {
                data: 'jam_setting_pulang',
                name: 'jam_setting_pulang'
            },
            {
                data: 'jam_keluar',
                name: 'jam_keluar'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'catatan_masuk',
                name: 'catatan_masuk'
            },
            {
                data: 'catatan_keluar',
                name: 'catatan_keluar'
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
        $('#filter_start').val('');
        $('#filter_end').val('');
        $('#filter_satpam').val('');
        $('#filter_status').val('');
        table.ajax.reload();
    });

    $('#btnExportXls').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            status: $('#filter_status').val() || ''
        });

        window.location.href = "{{ route('absensi.export.xls') }}?" + params;
    });

    $('#btnExportPdf').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',
            status: $('#filter_status').val() || ''
        });

        window.location.href = "{{ route('absensi.export.pdf') }}?" + params;

    });

    function tambah_data() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Data Absen");
        resetForm();
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('absensi') }}";
        else url = "{{ url('absensi') . '/' }}" + id;
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData($('#modal-tambah form')[0]),
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.success) {
                    $('#modal-tambah').modal('hide');
                    reloadTable();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: data.message,
                        showConfirmButton: false,
                        scrollbarPadding: false,
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors).map(e => e[0]).join('<br>');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        html: msg
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + xhr.responseJSON?.message
                    });
                }
            },
            complete: function() {
                $('#btn-save-data').prop('disabled', false).text('Simpan');
            }

        });
    });


    function editData(id) {
        save_method = "edit";
        $('input[name=_method]').val('PATCH');
        $.ajax({
            url: "{{ url('/absensi') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Data Absensi");
                $('#id').val(data.id);
                $("#tanggal").val(data.tanggal);
                $("#satpam_id").val(data.satpam_id);
                

                let jam_masuk_full = data.jam_masuk;

                // aman kalau null, undefined, atau format tidak sesuai
                let jam_masuk = '';

                if (jam_masuk_full && jam_masuk_full.includes(' ')) {
                    jam_masuk = jam_masuk_full.split(' ')[1];
                } else if (jam_masuk_full && jam_masuk_full.length === 8) {
                    // misal format sudah "11:10:09"
                    jam_masuk = jam_masuk_full;
                } else {
                    jam_masuk = ''; // kosongkan jika null
                }

                $("#jam_masuk").val(jam_masuk);


                let jam_keluar_full = data.jam_keluar;

                // aman kalau null, undefined, atau format tidak sesuai
                let jam_keluar = '';

                if (jam_keluar_full && jam_keluar_full.includes(' ')) {
                    jam_keluar = jam_keluar_full.split(' ')[1];
                } else if (jam_keluar_full && jam_keluar_full.length === 8) {
                    // misal format sudah "11:10:09"
                    jam_keluar = jam_keluar_full;
                } else {
                    jam_keluar = ''; // kosongkan jika null
                }

                $("#jam_keluar").val(jam_keluar);
                $("#status_absen").val(data.status);
                $("#description").val(data.description);

            }
        })
    }



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
                    url: "{{ url('absensi') }}" + "/" + id,
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

    function resetForm() {
        $("#tanggal").val(null);
        $("#satpam_id").val("");
        $("#jam_masuk").val("");
        $("#jam_keluar").val("");
        $("#status_absen").val("");
        $("#description").val("");
    }
</script>
