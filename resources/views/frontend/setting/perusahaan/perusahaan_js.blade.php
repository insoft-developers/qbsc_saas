<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('perusahaan.table') }}',
        order: [[0, 'desc']],
        columns: [
            {
                data: 'id',
                name: 'id',
                orderable: true,
                visible: false
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: true,
                searchable: false,
                visible:false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },

            {
                data: 'company_name',
                name: 'company_name'
            },
            {
                data: 'company_address',
                name: 'company_address'
            },
            {
                data: 'company_pic',
                name: 'company_pic'
            },

            {
                data: 'company_email',
                name: 'company_email'
            },
            {
                data: 'company_phone',
                name: 'company_phone'
            },
            {
                data: 'is_peternakan',
                name: 'is_peternakan'
            },

        ]
    });

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('perusahaan') }}";
        else url = "{{ url('perusahaan') . '/' }}" + id;
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
            url: "{{ url('/perusahaan') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Pengaturan Perusahaan");
                $('#id').val(data.id);
                $("#company_name").val(data.company_name);
                $("#company_address").val(data.company_address);
                $("#company_pic").val(data.company_pic);
                $("#company_email").val(data.company_email);
                $("#company_phone").val(data.company_phone);
                var isPeternakan = data.is_peternakan == 1 ? 'Peternakan':'Perusahaan Lain';
                $("#is_peternakan").val(isPeternakan);
                
            }
        })
    }

   
    function reloadTable() {
        table.ajax.reload(null, false);
    }

    function resetForm() {
        $("#foto").val(null);
        $("#name").val("");
        $("#whatsapp").val("");
        $("#password").val("");
    }
</script>
