<script>
    CKEDITOR.replace('content');

    $('#select-all-companies').on('click', function() {

        $('.company-checkbox')
            .prop('checked', true)
            .prop('disabled', false);

        $('#all_companies').prop('checked', false);
    });


    $('#clear-all-companies').on('click', function() {

        $('.company-checkbox')
            .prop('checked', false)
            .prop('disabled', false);

        $('#all_companies').prop('checked', false);
    });

    $('#all_companies').on('change', function() {

        if ($(this).is(':checked')) {

            $('.company-checkbox')
                .prop('checked', false)
                .prop('disabled', true);

        } else {

            $('.company-checkbox')
                .prop('disabled', false);

        }

    });

    $('.company-checkbox').on('change', function() {

        if ($('.company-checkbox:checked').length > 0) {
            $('#all_companies').prop('checked', false);
        }

    });


    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.slider.table') }}",
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
                data: 'image',
                name: 'image'
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'subtitle',
                name: 'subtitle'
            },
            {
                data: 'comid',
                name: 'comid'
            },
            {
                data: 'is_active',
                name: 'is_active'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },

        ]
    });

    function tambah_data() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Data Sliders");
        resetForm();
        $("#modal-tambah").modal("show");
    }


    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('backadmin/slider') }}";
        else url = "{{ url('backadmin/slider') . '/' }}" + id;

        var CKContent = CKEDITOR.instances.content.getData();
        let formData = new FormData($('#modal-tambah form')[0]);
        formData.append("content", CKContent);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
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
            url: "{{ url('/backadmin/slider') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Data Slider");
                $('#id').val(data.id);
                $("#title").val(data.title);
                $("#subtitle").val(data.subtitle);
                CKEDITOR.instances.content.setData(data.content);
                $("#image").val(null);
                $("#is_active").val(data.is_active);

                $('.company-checkbox')
                    .prop('checked', false)
                    .prop('disabled', false);

                $('#all_companies').prop('checked', false);


                // =====================================
                // SET PERUSAHAAN YANG DIPILIH
                // =====================================
                if (data.comid && data.comid.length > 0) {

                    data.comid.forEach(function(comid) {

                        $('#company_' + comid).prop('checked', true);

                    });

                } else {

                    // NULL / kosong = semua perusahaan
                    $('#all_companies')
                        .prop('checked', true);

                    $('.company-checkbox')
                        .prop('checked', false)
                        .prop('disabled', true);
                }

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
                    url: "{{ url('backadmin/slider') }}" + "/" + id,
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


    function payment(id) {
        Swal.fire({
            title: 'Yakin ingin ubah jadi PAID?',
            text: "Data ini status pembayarannya akan diubah jadi PAID",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: "Ubah Jadi PAID",
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.transaction.paid') }}",
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
</script>