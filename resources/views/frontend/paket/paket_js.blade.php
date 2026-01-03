<script>
    function beli_paket(id) {
        $("#modal-pembayaran").modal('show');
        $("#id_pembayaran").val(id);
    }

    $("#modal-pembayaran #btn-payment").click(function() {
        var jenis_pembayaran = $("#jenis_pembayaran").val();
        if (jenis_pembayaran == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih metode pembayaran..!',
                text: 'Metode pembayaran belum dipilih...!'
            });
        }

        var id_pembayaran = $("#id_pembayaran").val();

        if (jenis_pembayaran == '1') {
            duitku_payment(id_pembayaran);
        } else if (jenis_pembayaran == '2') {
            manual_payment(id_pembayaran);
        }
    });

    function manual_payment(id) {
        $.ajax({
            url: "{{ route('whatsapp.payment') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "id": id,
                "_token": '{{ csrf_token() }}'
            },
            success: function(data) {

                let periode_langganan = data.data.periode == 1 ? 'perbulan' : 'pertahun';
                window.open(
                    'https://wa.me/6282379096235?text=Halo%20Admin%20QBSC,%20saya%20mau%20berlangganan%20paket%20QBSC%20*' +
                    data.data.nama_paket + '*%20dengan%20biaya%20langganan%20 *' + formatRupiah(data
                        .data.harga) + '*%20' + periode_langganan + '%20untuk user%20*' + data.user
                    .name + '*%20*(' + data.user.company.company_name + ')*%20dengan%20email%20*' + data
                    .user.email + '*%20tolong%20bisa%20diproses%20Terima Kasih.',
                    '_blank'
                );
            }
        })
    }

    function duitku_payment(id) {
        $.ajax({
            url: "{{ route('create.payment') }}",
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

    function paket_free(id) {
        $("#paket-gratis-id").val(id);
        $("#modal-gratis").modal("show");
    }

    $("#btn-coba-gratis").click(function() {
        var id = $("#paket-gratis-id").val();
        $.ajax({
            url: "{{ route('paket.gratis') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "id": id,
                "_token": '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    window.location = "{{ url('riwayat') }}";
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Paket Gratis Gagal',
                        text: 'Paket Gratis sudah pernah digunakan!'
                    });
                }

            }
        })
    });
</script>
