<script>
    tampilkan_absensi_satpam();
    tampilkan_patroli_satpam();

    function tampilkan_absensi_satpam() {
        $("#table-dashboard-absensi tbody").html(
            '<tr><td colspan="7"><center><p>Refreshing data ....</p></center></td></tr>');
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
                        if (absensi[i].latitude && absensi[i].longitude) {
                            var lokasi_url = "https://www.google.com/maps/@" + absensi[i].latitude + "," +
                                absensi[i].longitude + ",21z";
                        } else {
                            var lokasi_url = "#";
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

                                <td><a href="${lokasi_url}" target="_blank">
                                    <small class="text-muted d-block">
                                        Lat: ${absensi[i].latitude ?? "-"}
                                    </small>
                                    <small class="text-muted d-block">
                                        Lng: ${absensi[i].longitude ?? "-"}
                                    </small></a>
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


    function tampilkan_patroli_satpam() {
        $("#table-dashboard-patroli tbody").html(
            '<tr><td colspan="7"><center><p>Refreshing data ....</p></center></td></tr>');
        $.ajax({
            url: "{{ route('tampilkan.patroli.satpam') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    var patroli = data.data;
                    var html = '';

                    for (var i = 0; i < patroli.length; i++) {
                        if (patroli[i].latitude && patroli[i].longitude) {
                            var lokasi_url = "https://www.google.com/maps/@" + patroli[i].latitude + "," +
                                patroli[i].longitude + ",21z";
                        } else {
                            var lokasi_url = "#";
                        }

                        var gambar = '';
                        if (patroli[i].photo_path !== null) {
                            gambar = `<div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <a href="{{ asset('storage') }}/` + (patroli[i].photo_path ??
                                '') + `" target="_blank"><img 
                                                src="{{ asset('storage') }}/` + (patroli[i].photo_path ??
                                '') + `" 
                                                alt="Foto Patroli"
                                                class="rounded-circle shadow-sm"
                                                style="width: 55px; height: 55px; object-fit: cover;"
                                            ></a>
                                        </div>

                                        
                                    </div>`;
                        }

                        html += `<tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <img 
                                                src="{{ asset('storage') }}/` + (patroli[i].satpam?.face_photo_path ??
                            '') + `" 
                                                alt="Foto Satpam"
                                                class="rounded-circle shadow-sm"
                                                style="width: 55px; height: 55px; object-fit: cover;"
                                            >
                                        </div>

                                        <div>
                                            <h6 class="mb-1 fw-semibold">${patroli[i].satpam?.name ?? "-"}</h6>
                                            <small class="text-muted">
                                                ${patroli[i].satpam?.whatsapp ?? "-"}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>${formatTanggal(patroli[i].tanggal)}</strong></td>
                                <td style="color:blue;">${patroli[i].jam}</td>
                                <td style="color:green;">${patroli[i].lokasi.nama_lokasi ?? ''}</td>
                                <td><a href="${lokasi_url}" target="_blank">
                                    <small class="text-muted d-block">
                                        Lat: ${patroli[i].latitude ?? "-"}
                                    </small>
                                    <small class="text-muted d-block">
                                        Lng: ${patroli[i].longitude ?? "-"}
                                    </small></a>
                                </td>
                                <td>
                                    ${gambar}
                                </td>
                                <td>${patroli[i].note}</td>
                                </tr>`;
                    }

                    $("#table-dashboard-patroli tbody").html(html);
                }
            }
        })
    }

    function refresh_patroli() {
        tampilkan_patroli_satpam();
    }
</script>
