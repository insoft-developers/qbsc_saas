<script>
    let countIndex = 1;

    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('lokasi.table') }}',
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
                data: 'qrcode',
                name: 'qrcode'
            },
            {
                data: 'nama_lokasi',
                name: 'nama_lokasi'
            },
            // {
            //     data: 'jam_awal',
            //     name: 'jam_awal'
            // },
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
        var qrcode = generateCode("LOC") + "-" + "{{ Auth::user()->company_id }}";
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
                $('#id').val(data.data.id);
                $("#nama_lokasi").val(data.data.nama_lokasi);
                $("#qrcode").val(data.data.qrcode);
                $("#latitude").val(data.data.latitude);
                $("#longitude").val(data.data.longitude);
                show_editted_jam(data.awal, data.akhir);
            }
        })
    }

    function show_editted_jam(jam_awal, jam_akhir) {
        countIndex = 0;
        $("#container-jam").html('');
        if (jam_awal.length > 0) {



            for (var i = 0; i < jam_awal.length; i++) {
                countIndex++;
                var html = '';
                html += `<div class="row mt-2" id="row_${countIndex}">
            <div class="col-md-5">
                <label for="jam_awal" class="form-label">Jam Awal</label>
                <input value="${jam_awal[i]}" type="time" id="jam_awal_${countIndex}" name="jam_awal[]" class="form-control"
                    step="1">
            </div>
            <div class="col-md-5">
                <label for="jam_akhir" class="form-label">Jam Akhir</label>
                <input value="${jam_akhir[i]}" type="time" id="jam_akhir_${countIndex}" name="jam_akhir[]" class="form-control"
                    step="1">
            </div>
            <div class="col-md-1" >
                <center>
                    <button type="button" onclick="tambahJam()" title="Edit Data"
                        class="me-0 btn btn-insoft btn-jam btn-success"><i
                            class="bi bi-plus-lg"></i></button>
                    
                </center>
            </div>
           <div class="col-md-1" style="margin-left: -5px;">
                <center>

                    <button type="button" onclick="hapusJam(${countIndex})" title="Hapus Data"
                        class="btn btn-insoft btn-jam btn-danger"><i
                            class="bi bi-trash3"></i></button>
                </center>
            </div>
        </div>`;

                $("#container-jam").append(html);

            }


        } else {
            tambahJam();
        }
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
                    url: "{{ url('lokasi') }}" + "/" + id,
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
        $("#container-jam").children().not("#row_1").remove();
        countIndex = 1;
        $("#jam_awal_1").val("");
        $("#jam_akhir_1").val("");


    }



    function tambahJam() {
        countIndex++;

        var html = '';
        html += `<div class="row mt-2" id="row_${countIndex}">
            <div class="col-md-5">
                <label for="jam_awal" class="form-label">Jam Awal</label>
                <input type="time" id="jam_awal_${countIndex}" name="jam_awal[]" class="form-control"
                    step="1">
            </div>
            <div class="col-md-5">
                <label for="jam_akhir" class="form-label">Jam Akhir</label>
                <input type="time" id="jam_akhir_${countIndex}" name="jam_akhir[]" class="form-control"
                    step="1">
            </div>
            <div class="col-md-1" >
                <center>
                    <button type="button" onclick="tambahJam()" title="Edit Data"
                        class="me-0 btn btn-insoft btn-jam btn-success"><i
                            class="bi bi-plus-lg"></i></button>
                    
                </center>
            </div>
           <div class="col-md-1" style="margin-left: -5px;">
                <center>

                    <button type="button" onclick="hapusJam(${countIndex})" title="Hapus Data"
                        class="btn btn-insoft btn-jam btn-danger"><i
                            class="bi bi-trash3"></i></button>
                </center>
            </div>
        </div>`;

        $("#container-jam").append(html);
    }


    function hapusJam(index) {
        $("#row_" + index).remove();
    }
</script>
