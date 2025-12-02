<script>


    function beli_paket(id) {

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
