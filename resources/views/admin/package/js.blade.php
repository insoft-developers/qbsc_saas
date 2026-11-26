<script>
    $('#comids').select2({
        placeholder: 'Pilih perusahaan...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modal-tambah')
    });



    var save_method = 'add';

    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.paket.table') }}",
        order: [
            [0, 'desc']
        ],
        columns: [{
                data: 'id',
                name: 'id',
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
                data: 'nama_paket',
                name: 'nama_paket'
            },

            {
                data: 'urutan',
                name: 'urutan'
            },
            {
                data: 'comids',
                name: 'comids'
            },
            {
                data: 'harga',
                name: 'harga'
            },
            {
                data: 'periode_text',
                name: 'periode'
            },
            {
                data: 'hari',
                name: 'hari'
            },
            {
                data: 'jumlah_satpam',
                name: 'jumlah_satpam'
            },
            {
                data: 'jumlah_lokasi',
                name: 'jumlah_lokasi'
            },
            {
                data: 'jumlah_farm',
                name: 'jumlah_farm'
            },
            {
                data: 'jumlah_user_admin',
                name: 'jumlah_user_admin'
            },
            {
                data: 'company_type_text',
                name: 'company_type'
            },
            {
                data: 'fitur',
                name: 'fitur',
                orderable: false,
                searchable: false
            },
            {
                data: 'status',
                name: 'is_active',
                orderable: false,
                searchable: false
            },
            {
                data: 'is_show',
                name: 'is_show'
            },
        ]
    });

    function tambah_data() {
        save_method = 'add';

        resetForm();

        $('input[name=_method]').val('POST');

        $('.modal-title').text('Tambah Paket Berlangganan');

        $('#modal-tambah').modal('show');
    }

    function editData(id) {
        save_method = 'edit';

        $.ajax({
            url: "{{ url('backadmin/paket') }}/" + id + "/edit",
            type: "GET",
            dataType: "JSON",

            success: function(data) {

                resetForm();

                $('input[name=_method]').val('PATCH');

                $('#id').val(data.id);
                $('#nama_paket').val(data.nama_paket);
                $('#subtitle').val(data.subtitle);
                $('#harga').val(data.harga);
                $('#periode').val(data.periode);
                $('#hari').val(data.hari);

                $('#company_type').val(data.company_type);
                $('#is_active').val(data.is_active);

                $('#jumlah_satpam').val(data.jumlah_satpam);
                $('#jumlah_lokasi').val(data.jumlah_lokasi);
                $('#jumlah_farm').val(data.jumlah_farm);
                $('#jumlah_user_admin').val(data.jumlah_user_admin);

                // Checkbox fitur
                $('#is_broadcast').prop('checked', data.is_broadcast == 1);
                $('#is_scan_tamu').prop('checked', data.is_scan_tamu == 1);
                $('#is_user_area').prop('checked', data.is_user_area == 1);
                $('#is_mobile_app').prop('checked', data.is_mobile_app == 1);
                $('#is_google_meet').prop('checked', data.is_google_meet == 1);
                $('#is_request_feature').prop('checked', data.is_request_feature == 1);

                $('#urutan').val(data.urutan);
                $('#is_show').val(data.is_show);

                // Ambil ID perusahaan yang dipisahkan koma
                let perusahaan = data.comids ?
                    data.comids.split(',') : [];

                $('#comids').val(perusahaan).trigger('change');

                $('.modal-title').text('Edit Paket Berlangganan');

                $('#modal-tambah').modal('show');
            },

            error: function(xhr) {
                Swal.fire('Gagal!', xhr.responseJSON?.message || 'Gagal mengambil data paket.', 'error');
            }
        });
    }

    // Simpan dan update
    $('#form-tambah').submit(function(e) {
        e.preventDefault();

        loading('btn-save-data');

        var id = $('#id').val();

        var url = save_method == 'add' ?
            "{{ url('backadmin/paket') }}" :
            "{{ url('backadmin/paket') }}/" + id;

        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,

            success: function(data) {
                if (data.success) {
                    $('#modal-tambah').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    reloadTable();
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
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
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan.'
                    });
                }
            },

            complete: function() {
                $('#btn-save-data').prop('disabled', false).text('Simpan');
            }
        });
    });

    function deleteData(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus paket?',
            text: 'Data paket akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('backadmin/paket') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },

                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        reloadTable();
                    },

                    error: function(xhr) {
                        Swal.fire(
                            'Gagal!',
                            xhr.responseJSON?.message || 'Terjadi kesalahan.',
                            'error'
                        );
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
        $('#id').val('');

        $('#form-tambah input[type=checkbox]').prop('checked', false);
        $("#comids").val(null).trigger('change');
    }
</script>