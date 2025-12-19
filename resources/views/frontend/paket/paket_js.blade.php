<script>


    function beli_paket(id) {

        $.ajax({
            url: "{{ route('whatsapp.payment') }}",
            type: "POST",
            dataType: "JSON",
            data: {"id":id, "_token": '{{ csrf_token() }}'},
            success: function(data) {
                console.log(data);
                let periode_langganan = data.data.periode == 1 ? 'perbulan' : 'pertahun';
                 window.open(
            'https://wa.me/6282165174835?text=Halo%20Admin%20QBSC,%20saya%20mau%20berlangganan%20paket%20QBSC%20*'+data.data.nama_paket+'*%20dengan%20biaya%20langganan%20 *'+formatRupiah(data.data.harga)+'*%20'+periode_langganan+'%20untuk user%20*'+data.user.name+'*%20*('+data.user.company.company_name+')*%20dengan%20email%20*'+data.user.email+'*%20tolong%20bisa%20diproses%20Terima Kasih.',
            '_blank'
        );
            }
        })
    }


    function beli_paket_duitku(id) {
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
</script>
