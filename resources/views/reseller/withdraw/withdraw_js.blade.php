
<script>
var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reseller.withdraw.table') }}',
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
                
            },
            
            {
                data: 'reseller_id',
                name: 'reseller_id'
            },
            {
                data: 'invoice',
                name: 'invoice'
            },
            
            {
                data: 'jumlah',
                name: 'jumlah'
            },
            {
                data: 'rekening',
                name: 'rekening'
            },
            {
                data: 'keterangan',
                name: 'keterangan'
            },

            {
                data: 'payment_status',
                name: 'payment_status'
            },
            {
                data: 'bukti_transfer',
                name: 'bukti_transfer'
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
        $(".modal-title").text("Tambah Withdraw");
        resetForm();
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('reseller/withdraw') }}";
        else url = "{{ url('reseller/withdraw') . '/' }}" + id;
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
            url: "{{ url('/reseller/withdraw') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Withdraw");
                $('#id').val(data.id);
                $("#jumlah").val(data.jumlah);
                $("#keterangan").val(data.keterangan);
                $("#rekening").val(data.rekening);
                
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
                    url: "{{ url('reseller/withdraw') }}" + "/" + id,
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
        $("#jumlah").val('');
        $("#keterangan").val('');
        $("#rekening").val('');
    }

</script>

