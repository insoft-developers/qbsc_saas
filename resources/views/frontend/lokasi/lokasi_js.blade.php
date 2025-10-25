<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('lokasi.table') }}',
        columns: [{
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
                data: 'qrcode',
                name: 'qrcode'
            },
            {
                data: 'nama_lokasi',
                name: 'nama_lokasi'
            },
            {
                data: 'is_active',
                name: 'is_active'
            },
            {
                data: 'latitude',
                name: 'latitude'
            },
            {
                data: 'longitude',
                name: 'longitude'
            },
            {
                data: 'company',
                name: 'company'
            },

        ]
    });

    function tambah_data() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Data Lokasi");
        var qrcode = generateCode("LOC")+"-"+ "{{ Auth::user()->company_id }}";
        $("#qrcode").val(qrcode);
        resetForm();
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('lokasi') }}";
        else url = "{{ url('lokasi') . '/' }}" + id;
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
            url: "{{ url('/lokasi') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Data Lokasi");
                $('#id').val(data.id);
                $("#nama_lokasi").val(data.nama_lokasi);
                $("#qrcode").val(data.qrcode);
                $("#latitude").val(data.latitude);
                $("#longitude").val(data.longitude);
            }
        })
    }

    function activate(id, active) {
        Swal.fire({
            title: active == 1 ? 'Yakin ingin mengaktifkan?' : 'Yakin ingin menonaktifkan?',
            text: active == 1 ? "Data ini akan diaktifkan" : "Data ini akan dinonaktifkan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: active == 1 ? "Ya Aktifkan" : "Ya, Non Aktifkan",
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('lokasi.activate') }}",
                    type: 'POST',
                    data: {
                        id: id,
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
                    url: "{{ url('lokasi') }}"+"/"+ id,
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
        $("#nama_lokasi").val(null);
        $("#latitude").val("");
        $("#longitude").val("");
        
    }
</script>
