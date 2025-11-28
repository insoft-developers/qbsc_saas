<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bulanSekarang = new Date().toISOString().slice(5, 7); // "01" - "12"
        document.getElementById('filter_periode').value = bulanSekarang;
        $("#btnFilter").trigger("click");

    });


    $("#btnFilter").click(function() {
        $("#table-container").html('<center>loading data ...</center>');
        var periode = $("#filter_periode").val();
        var tahun = $("#filter_tahun").val();
        var kandang = $("#filter_kandang").val();

        $.ajax({
            url: "{{ route('tampilkan.laporan.kandang') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                "periode": periode,
                "tahun": tahun,
                "kandang": kandang,
                "_token": '{{ csrf_token() }}'
            },
            success: function(data) {
                console.log(data);
                if (data.success) {
                    var html = '';
                    var hari = data.data.hari;
                    var jam_kontrol = data.data.jam;

                    html +=
                        '<table style="font-size:11px;" id="table-laporan-kandang" class="table table-bordered w-100 align-middle nowrap">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<th rowspan="2">KANDANG</th>';
                    html += '<th rowspan="2">JAM</th>';
                    html += '<th rowspan="2">JENIS</th>';

                    html += '<th style="font-size:20px;" colspan="' + hari + '"><center>' +
                        formatPeriode(periode, tahun) + '</center></th>';

                    html += '</tr>';

                    html += '<tr>';
                    for (var i = 1; i <= hari; i++) {
                        html += '<th>' + i + '</th>';
                    }
                    html += '</tr>';

                    html += '</thead>';
                    html += '<tbody>';

                    var types = ["Suhu", "Kipas", "Alarm", "Lampu"];

                    jam_kontrol.forEach(function(item) {
                        types.forEach(function(type, index) {
                            if (type == 'Suhu') {
                                warna = 'color:green;';
                            } else if (type == 'Kipas') {
                                warna = 'color:orange;';
                            } else if (type == 'Alarm') {
                                warna = 'color:red;';
                            } else if (type == 'Lampu') {
                                warna = 'color:blue;';
                            }

                            html += '<tr>';

                            if (index === 0) {
                                html += '<td rowspan="4"><strong>' + data.data
                                    .kandang.name + '</strong></td>';
                                html += '<td rowspan="4"><strong>' + item +
                                    '</strong></td>';
                            }

                            html += '<td style="' + warna + '"><strong>' + type +
                                '</strong></td>';

                            for (var i = 1; i <= hari; i++) {

                                let tanggal =
                                    `${tahun}-${periode.padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                                let value = "-";

                                // CEK apakah ada data
                                if (
                                    data.data.laporan[tanggal] &&
                                    data.data.laporan[tanggal][item] &&
                                    data.data.laporan[tanggal][item][type] !==
                                    undefined
                                ) {
                                    value = data.data.laporan[tanggal][item][type];

                                    // ============================
                                    // 1️⃣ KONVERSI ALARM & LAMPU
                                    // ============================
                                    if (type === "Alarm" || type === "Lampu") {
                                        value = (value == 1) ? "on" : "off";
                                    }

                                    // ============================
                                    // 2️⃣ KIPAS: HITUNG 1/total
                                    // ============================
                                    if (type === "Kipas") {
                                        let arr = value.split(",").map(
                                            Number); // ubah "1,0,1" → array
                                        let onCount = arr.filter(v => v === 1)
                                            .length;
                                        let total = arr.length;
                                        value = `${onCount}/${total}`;
                                    }
                                }

                                html += `<td>${value}</td>`;

                            }
                            html += '</tr>';
                        });
                    });


                    html += '</tbody>';
                    html += '</table>';
                    $("#table-container").html(
                        html);
                }
            }
        })
    });



    function formatPeriode(bulan, tahun) {
        var periode = '';
        if (bulan == '01') {
            periode = 'Januari';
        } else if (bulan == '02') {
            periode = 'Februari';
        } else if (bulan == '03') {
            periode = 'Maret';
        } else if (bulan == '04') {
            periode = 'April';
        } else if (bulan == '05') {
            periode = 'Mei';
        } else if (bulan == '06') {
            periode = 'Juni';
        } else if (bulan == '07') {
            periode = 'Juli';
        } else if (bulan == '08') {
            periode = 'Agustus';
        } else if (bulan == '09') {
            periode = 'September';
        } else if (bulan == '10') {
            periode = 'Oktober';
        } else if (bulan == '11') {
            periode = 'November';
        } else if (bulan == '12') {
            periode = 'Desember';
        }

        return periode + ' ' + tahun;
    }


    $("#btnExportXls").click(function() {

        let periode = $("#filter_periode").val();
        let tahun = $("#filter_tahun").val();
        let kandang = $("#filter_kandang").val();

        $.ajax({
            url: "{{ route('laporan.kandang.export.xls') }}",
            type: "POST",
            data: {
                periode: periode,
                tahun: tahun,
                kandang: kandang,
                _token: "{{ csrf_token() }}"
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(blob) {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = "laporan-kandang.xlsx";
                a.click();
                window.URL.revokeObjectURL(url);
            }
        });

    });
</script>
