<script>
    tampilkan_absensi_satpam();

    function tampilkan_absensi_satpam() {
        $.ajax({
            url: "{{ route('tampilkan.absensi.satpam') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    var absensi = data.data;
                    var html = '';
                    for (var i = 0; i < absensi.length; i++) {
                        html += `<tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm">
                                    <img style="width:60px;height:50px;" src="{{ asset('storage') }}/` + absensi[i].satpam.face_photo_path + `" 

                                        alt="" class="img-fluid rounded-circle">
                                </div>
                                <div class="ps-2">
                                    <h5 class="mb-1">${absensi[i].satpam.name ?? ''}</h5>
                                    <p class="text-muted fs-6 mb-0">${absensi[i].satpam.whatsapp ?? ''}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold">${absensi[i].tanggal}</span>
                        </td>
                        <td>
                            <h5 class="mb-0 ms-1">${absensi[i].shift_name}</h5>
                        </td>
                        <td>
                            <h5 class="mb-0 ms-1">America</h5>
                        </td>
                        <td>
                            <h5 class="mb-0 ms-1">America</h5>
                        </td>
                        <td>
                            <h5 class="mb-0">Wade Warren</h5>
                        </td>
                        
                        <td>
                            <span class="badge bg-primary-subtle text-primary">Pending
                                Approval</span>
                        </td>

                    </tr>`;

                        $("#table-dashboard-absensi tbody").html(html);
                    }
                } else {

                }

            }
        })
    }
</script>
