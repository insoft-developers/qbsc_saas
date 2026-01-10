<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('jadwal.patroli.detail.table') }}',
            type: 'POST',
            data: {
                jadwal_id: '{{ $id }}',
                _token: '{{ csrf_token() }}'
            }
        },
        order: [
            [5, 'desc']
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
                data: 'location_id',
                name: 'location_id'
            },
            {
                data: 'patroli_id',
                name: 'patroli_id'
            },
            
            {
                data: 'urutan',
                name: 'urutan'
            },
            {
                data: 'jam_patroli',
                name: 'jam_patroli'
            },
            {
                data: 'comid',
                name: 'comid'
            }
        ]
    });


    function tambah_data() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Atur Jadwal Patroli");
        resetForm();
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('jadwal_patroli_detail') }}";
        else url = "{{ url('jadwal_patroli_detail') . '/' }}" + id;
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
            url: "{{ url('/jadwal_patroli_detail') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Pengaturan Patroli");
                $('#id').val(data.id);
                $("#patroli_id").val(data.patroli_id);
                $("#location_id").val(data.location_id);
                $("#jam_awal").val(data.jam_awal);
                $("#jam_akhir").val(data.jam_akhir);
                $("#urutan").val(data.urutan);

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
                    url: "{{ url('jadwal_patroli_detail') }}" + "/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            reloadTable();
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');

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



    function reloadTable() {
        table.ajax.reload(null, false);
    }

    function resetForm() {
        $('#form-tambah')[0].reset();

    }
</script>
