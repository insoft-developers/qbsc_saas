<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('absen.location.table') }}',
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
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },

            {
                data: 'location_name',
                name: 'location_name'
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
                data: 'max_distance',
                name: 'max_distance'
            },
            {
                data: 'company',
                name: 'company'
            },

        ]
    });

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('absen_location') }}";
        else url = "{{ url('absen_location') . '/' }}" + id;
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
            url: "{{ url('/absen_location') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Pengaturan Lokasi Absen");
                $('#id').val(data.id);
                $("#location_name").val(data.location_name);
                $("#latitude").val(data.latitude);
                $("#longitude").val(data.longitude);
                $("#max_distance").val(data.max_distance);
                
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
