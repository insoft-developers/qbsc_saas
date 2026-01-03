<script>

    $("#btn-running-text").click(function(){

        loading("btn-running-text");
        var running_text = $("#running_text_id").val();
        $.ajax({
            url: "{{ route('running.text.update') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "running_text":running_text,
                "_token" : '{{ csrf_token() }}'
            },
            success: function(data) {
                location.reload();
            },
            complete:function() {
                $('#btn-running-text').prop('disabled', false).text('Buat/Update Running Text');
            }
        })
    });


</script>