<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('custom.feature.table') }}',
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
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'finish_date',
                name: 'finish_date'
            },
            {
                data: 'order_id',
                name: 'order_id'
            },
            {
                data: 'feature',
                name: 'feature'
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'userid',
                name: 'userid'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'admin_response',
                name: 'admin_response'
            },
            {
                data: 'payment_status',
                name: 'payment_status'
            },
            {
                data: 'payment_code',
                name: 'payment_code'
            },
            {
                data: 'comid',
                name: 'comid'
            },

        ]
    });

    function tambah_data() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Request Fitur");
        resetForm();
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('custom_feature') }}";
        else url = "{{ url('custom_feature') . '/' }}" + id;
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
            url: "{{ url('/custom_feature') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Pesan");
                $('#id').val(data.id);
                $("#feature").val(data.feature);
                $("#description").val(data.description);
                $("#image").val(null);

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
                    url: "{{ url('custom_feature') }}" + "/" + id,
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


    function payData(id) {
        $.ajax({
            url: "{{ route('create.feature.payment') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "id": id,
                "_token": '{{ csrf_token() }}'
            },
            success: function(data) {
               console.log(data);
               window.location = data;
            }
        })
    }
</script>
