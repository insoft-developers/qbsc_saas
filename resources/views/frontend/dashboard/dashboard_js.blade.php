<script>
    tampilkan_absensi_satpam();

    function tampilkan_absensi_satpam() {
        $("#table-dashboard-absensi tbody").html('<tr><td colspan="7"><center><p>Refreshing data ....</p></center></td></tr>');
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

                        if (absensi[i].status == 1) {
                            $masuk = `
                            <span class="badge bg-primary-subtle text-primary">
                                Masuk
                            </span>`;
                        } else {
                            $masuk = `
                            <span class="badge bg-danger-subtle text-danger">
                                Pulang
                            </span>`;
                        }

                        if (absensi[i].jam_keluar == null) {
                            jam_keluar = `<span class="text-danger">-
                                        </span>`;
                        } else {
                            jam_keluar = `<span class="text-danger">
                                        ${formatTanggalWaktu(absensi[i].jam_keluar)}<br><small class="text-muted">${absensi[i].catatan_keluar}</small>
                                    </span>`;
                        }

                        html += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <img 
                                                src="{{ asset('storage') }}/` + (absensi[i].satpam?.face_photo_path ??
                            '') + `" 
                                                alt="Foto Satpam"
                                                class="rounded-circle shadow-sm"
                                                style="width: 55px; height: 55px; object-fit: cover;"
                                            >
                                        </div>

                                        <div>
                                            <h6 class="mb-1 fw-semibold">${absensi[i].satpam?.name ?? "-"}</h6>
                                            <small class="text-muted">
                                                ${absensi[i].satpam?.whatsapp ?? "-"}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        ${formatTanggal(absensi[i].tanggal)}
                                    </span>
                                </td>

                                <td>
                                    <span class="fw-semibold text-dark">
                                        ${absensi[i].shift_name ?? "-"}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-success">
                                        ${formatTanggalWaktu(absensi[i].jam_masuk)}<br><small class="text-muted">${absensi[i].catatan_masuk}</small>
                                    </span>
                                </td>

                                <td>
                                    ${jam_keluar}
                                </td>

                                <td>
                                    <small class="text-muted d-block">
                                        Lat: ${absensi[i].latitude ?? "-"}
                                    </small>
                                    <small class="text-muted d-block">
                                        Lng: ${absensi[i].longitude ?? "-"}
                                    </small>
                                </td>

                                <td class="text-center">
                                    ${$masuk}
                                </td>
                            </tr>
                            `;


                        $("#table-dashboard-absensi tbody").html(html);
                    }
                } else {

                }

            }
        })
    }

    function refresh_absensi() {

        tampilkan_absensi_satpam();
    }
</script>
