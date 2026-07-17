<script>

    $("#btn-save-report-image").click(function(){

        loading("btn-save-report-image");
        var report_image = $("#report_image_setting_status").val();
        $.ajax({
            url: "{{ route('report.image.update') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "report_image":report_image,
                "_token" : '{{ csrf_token() }}'
            },
            success: function(data) {
                location.reload();
            },
            complete:function() {
                $('#btn-save-report-image').prop('disabled', false).text('Simpan Perubahan');
            }
        })
    });


</script>