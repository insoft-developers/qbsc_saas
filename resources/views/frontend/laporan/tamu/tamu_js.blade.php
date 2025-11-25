<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('tamu.table') }}",
            data: function(d) {
                d.start_date = $('#filter_start').val();
                d.end_date = $('#filter_end').val();
                d.satpam_id = $('#filter_satpam').val();
                d.user_id = $("#filter_user").val();
            }
        },
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
                data: 'nama_tamu',
                name: 'nama_tamu'
            },
            {
                data: 'jumlah_tamu',
                name: 'jumlah_tamu'
            },
            {
                data: 'tujuan',
                name: 'tujuan'
            },
            {
                data: 'whatsapp',
                name: 'whatsapp'
            },
            {
                data: 'arrive_at',
                name: 'arrive_at'
            },
            {
                data: 'leave_at',
                name: 'leave_at'
            },
            {
                data: 'is_status',
                name: 'is_status'
            },
            {
                data: 'foto',
                name: 'foto'
            },
            {
                data: 'satpam_id',
                name: 'satpam_id'
            },
            {
                data: 'catatan',
                name: 'catatan'
            },
            {
                data: 'created_by',
                name: 'created_by'
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
        $(".modal-title").text("Tambah Tamu");
        resetForm();
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        e.preventDefault();
        loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('tamu') }}";
        else url = "{{ url('tamu') . '/' }}" + id;
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
            url: "{{ url('/tamu') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-tambah').modal("show");
                $('.modal-title').text("Edit Tamu");
                $('#id').val(data.id);
                $("#nama_tamu").val(data.nama_tamu);
                $("#jumlah_tamu").val(data.jumlah_tamu);
                $("#tujuan").val(data.tujuan);
                $("#whatsapp").val(data.whatsapp);
                $("#foto").val(null);
                $("#catatan").val(data.catatan);
            }
        })
    }



    $('#btnFilter').on('click', function() {
        table.ajax.reload();
    });

    $('#btnReset').on('click', function() {
        $('#filter_start').val('');
        $('#filter_end').val('');
        $('#filter_satpam').val('');
        $("#filter_user").val('');

        table.ajax.reload();
    });

    $('#btnExportXls').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',

        });

        window.location.href = "{{ route('situasi.export.xls') }}?" + params;
    });

    $('#btnExportPdf').on('click', function() {
        let params = $.param({
            start_date: $('#filter_start').val() || '',
            end_date: $('#filter_end').val() || '',
            satpam_id: $('#filter_satpam').val() || '',

        });

        window.location.href = "{{ route('situasi.export.pdf') }}?" + params;

    });


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
                    url: "{{ url('laporan_situasi') }}" + "/" + id,
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

    $(document).on('click', '.copyLink', function() {
        let link = $(this).data('link');

        let temp = $('<input>');
        $('body').append(temp);
        temp.val(link).select();
        document.execCommand("copy");
        temp.remove();

        Swal.fire({
            icon: 'success',
            title: 'Link QR berhasil disalin!',
            text: link,
            timer: 1500,
            showConfirmButton: false
        });
    });
</script>
