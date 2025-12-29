<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('user.table') }}',
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
                data: 'name',
                name: 'name'
            },
            {
                data: 'is_area',
                name: 'is_area'
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
                data: 'level',
                name: 'level'
            },
            {
                data: 'is_active',
                name: 'is_active'
            },
            {
                data: 'company_id',
                name: 'company_id'
            },

        ]
    });

    function tambah_data() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Data User");
        resetForm();
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('user') }}";
        else url = "{{ url('user') . '/' }}" + id;
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
            url: "{{ url('/user') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Data User");
                $('#id').val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#whatsapp").val(data.whatsapp);
                $("#password").val(null);
                $("#profile_image").val(null);
                $("#is_area").val(data.is_area);

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
                    url: "{{ url('user') }}" + "/" + id,
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
        $('#form-tambah')[0].reset();
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
                    url: "{{ route('user.activate') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            reloadTable();
                        } else {
                            Swal.fire('Warning!', response.message, 'error');

                        }

                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.',
                            'error');
                    }
                });
            }
        });
    }


    function areaData(el, id) {
        $("#modal-area").modal('show');
        var nama = $(el).data('name');
        $("#modal-area .modal-title").text('Setting User Area - ' + nama);
        $("#user_id_area").val(id);
        $("#user_key_id").val('');

        tampilkan_table_area(id);
    }


    $("#form-area").submit(function(e) {
        e.preventDefault();
        var userid = $("#user_id_area").val();
        var userkeyid = $("#user_key_id").val();
        loading("btn-tambah-area");
        $.ajax({
            url: "{{ route('tambah.user.area') }}",
            type: "POST",
            dataType: "JSON",
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil!', response.message, 'success');
                    $("#user_key_id").val('');
                    tampilkan_table_area(userid);
                } else {
                    Swal.fire('Warning!', response.message, 'error');

                }

            },
            error: function(xhr) {
                Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.',
                    'error');
            },
            complete: function() {
                $('#btn-tambah-area').prop('disabled', false).text('Tambahkan');
            }

        });

    });


    function tampilkan_table_area(userid) {
        var html = '';
        html += `<tr>
        <td colspan="8"><center>loading data......</center></td>
        </tr>`;
        $("#table-area tbody").html(html);
        $.ajax({
            url: "{{ route('tampilkan.area.table') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "userid": userid,
                "_token": '{{ csrf_token() }}'

            },
            success: function(data) {

                var html = '';
                if (data.data.length > 0) {
                    for (var i = 0; i < data.data.length; i++) {
                        var status_aktif = '';
                        var button_aktif = '';
                        if (data.data[i].is_active == 1) {
                            status_aktif = `<span class="badge bg-success">Aktif</span>`;
                            button_aktif =
                                `<button onclick="nonaktifkan(${data.data[i].id}, 0, ${data.data[i].userid})" title="nonaktifkan" type="button" class="btn btn-insoft btn-warning btn-sm"><i class="bi bi-x-lg"></i></button>`;
                        } else {
                            status_aktif = `<span class="badge bg-danger">Tidak</span>`;
                            button_aktif =
                                `<button onclick="nonaktifkan(${data.data[i].id}, 1, ${data.data[i].userid})" title="aktifkan" type="button" class="btn btn-insoft btn-success btn-sm"><i class="bi bi-check-circle"></i></button>`;
                        }

                        var button_hapus =
                            `<button onclick="hapus_area(${data.data[i].id}, ${data.data[i].userid})" title="Hapus Data" type="button" class="btn btn-insoft btn-danger btn-sm"><i class="bi bi-trash3"></i></button>`;

                        var button_copy =
                            `<button onclick="copy_key(this)" data-key="${data.data[i].user_key_id}" title="Copy Key" type="button" class="btn btn-insoft btn-info btn-sm"><i class="bi bi-clipboard "></i></button>`;



                        html += `<tr>
                        <td>${i+1}</td>
                        <td>${data.data[i].user.name ?? ''}</td>
                        <td>${data.data[i].company.company_name ?? ''}</td>
                        <td>${data.data[i].company_monitoring.company_name ?? ''}</td>
                        <td>${data.data[i].user_monitoring.name ?? ''}</td>
                        <td>${status_aktif}</td>
                        <td>${formatTgl(data.data[i].created_at)}</td>
                        <td>${button_aktif} ${button_hapus} ${button_copy}</td>
                        </tr>`;
                    }
                    $("#table-area tbody").html(html);
                } else {


                    html += `<tr>
                        <td colspan="8"><center>belum ada data user area</center></td>
                        
                        </tr>`;
                    $("#table-area tbody").html(html);
                }



            }
        })
    }

    function nonaktifkan(id, is_active, userid) {
        $.ajax({
            url: "{{ route('area.activate') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "id": id,
                "is_active": is_active,
                "_token": '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {

                    tampilkan_table_area(userid);
                } else {
                    Swal.fire('Warning!', response.message, 'error');
                }

            }
        })
    }

    function hapus_area(id, userid) {
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
                    url: "{{ route('hapus.user.area') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {

                        tampilkan_table_area(userid)
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.',
                            'error');
                    }
                });
            }
        });
    }


    function copy_key(el) {
        const key = $(el).data('key');

        navigator.clipboard.writeText(key)
            .then(() => {
                alert("Key berhasil disalin");
            })
            .catch(err => {
                console.error(err);
                alert("Gagal menyalin key");
            });
    }
</script>
