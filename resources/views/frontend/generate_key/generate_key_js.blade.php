<script>

    $("#btn-generate").click(function(){
        loading("btn-generate");
        $.ajax({
            url: "{{ route('generate.key') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                
                if(data.success) {
                    $("#user_key_id").val(data.token);
                }
            },
            complete:function() {
                $('#btn-generate').prop('disabled', false).text('Generate');
            }
        })
    });


</script>