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
        var url = window.location.href;
        var comid = url.split('/').pop();

        $.ajax({
            url: "{{ route('apibos.tampilkan.laporan.kandang') }}",
            type: "GET",
            dataType: "JSON",
            data: {
                "comid": comid,
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
                    // html += '<th rowspan="2">KANDANG</th>';
                    html += '<th style="background:white !important;" rowspan="2">JAM</th>';
                    html += '<th style="background:white !important;" rowspan="2">#</th>';

                    html += '<th style="font-size:20px;" colspan="' + hari + '"><center>' +
                        formatPeriode(periode, tahun) + '</center></th>';

                    html += '</tr>';

                    html += '<tr class="row-tanggal">';
                    for (var i = 1; i <= hari; i++) {
                        html += '<th style="background:white !important;">' + i + '</th>';
                    }
                    html += '</tr>';

                    html += '</thead>';
                    html += '<tbody>';

                    var types = ["Suhu", "Kipas", "Alarm", "Lampu"];

                    jam_kontrol.forEach(function(jam) {

                        types.forEach(function(type) {

                            let warna = '';
                            if (type === 'Suhu') warna = 'color:green;';
                            if (type === 'Kipas') warna = 'color:orange;';
                            if (type === 'Alarm') warna = 'color:red;';
                            if (type === 'Lampu') warna = 'color:blue;';

                            html += '<tr>';

                            // ✅ JAM (tanpa rowspan)
                            html +=
                                `<td style="background:white !important;" class="col-jam"><strong>${jam}</strong></td>`;

                            // ✅ JENIS
                            html +=
                                `<td class="col-jenis" style="${warna};text-align:left;background:white !important;"><strong>${type}</strong></td>`;

                            // ✅ TANGGAL
                            for (let i = 1; i <= hari; i++) {

                                let tanggal =
                                    `${tahun}-${periode.padStart(2,'0')}-${String(i).padStart(2,'0')}`;
                                let value = '-';

                                if (
                                    data.data.laporan[tanggal] &&
                                    data.data.laporan[tanggal][jam] &&
                                    data.data.laporan[tanggal][jam][type] !==
                                    undefined
                                ) {
                                    value = data.data.laporan[tanggal][jam][type];

                                    if (type === "Alarm" || type === "Lampu") {
                                        value = value == 1 ? "on" : "off";
                                    }

                                    if (type === "Kipas") {
                                        let arr = value.split(",").map(Number);
                                        value =
                                            `${arr.filter(v => v === 1).length}/${arr.length}`;
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
</script>
