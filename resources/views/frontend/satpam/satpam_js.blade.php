<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('satpam.table') }}',
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'foto',
                name: 'foto'
            },
            {
                data: 'badge_id',
                name: 'badge_id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'whatsapp',
                name: 'whatsapp'
            },
            {
                data: 'password',
                name: 'password'
            },
            {
                data: 'company',
                name: 'company'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });

    function tambah_data() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Data Satpam");
        var badge_id = generateCode("SEC");
        $("#badge_id").val(badge_id);
        $("#modal-tambah").modal("show");
    }

    $("#form-tambah").submit(function(e) {
        loading("btn-save-data");
        e.preventDefault();
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('satpam') }}";
        else url = "{{ url('satpam') . '/' }}" + id;
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData($('#modal-tambah form')[0]),
            contentType: false,
            processData: false,
            success: function(data) {
                unloading("btn-save-data", "Save");
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
            }

        });
    });

    function reloadTable() {
        table.ajax.reload(null,false);
    }
</script>
