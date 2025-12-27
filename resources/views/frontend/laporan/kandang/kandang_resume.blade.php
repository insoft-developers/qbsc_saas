<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resume Kandang</title>

    <link href="{{ asset('template/frontend') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <style>
        /* =====================================================
   THEME VARIABLE
===================================================== */
        :root {
            --primary: #1e88e5;
            --primary-light: #e3f2fd;
            --success: #2e7d32;
            --danger: #c62828;
            --text-dark: #263238;
            --border: #dfe3e8;
        }

        /* =====================================================
   GLOBAL
===================================================== */
        body {
            background: #f4f6f8;
            font-family: "Roboto", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text-dark);
        }

        /* =====================================================
   CARD
===================================================== */
        .card {
            border-radius: 18px;
            border: none;
        }

        .card-body {
            padding: 14px;
        }

        /* =====================================================
   FILTER / FORM
===================================================== */
        label {
            font-size: 12px;
            font-weight: 600;
            color: #546e7a;
        }

        select.form-select {
            height: 44px;
            border-radius: 14px;
            font-size: 14px;
            border: 1px solid var(--border);
            padding-left: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        }

        #btnFilter {
            height: 46px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            background: linear-gradient(135deg, #1976d2, #2196f3);
            border: none;
            box-shadow: 0 4px 10px rgba(33, 150, 243, .3);
        }

        #btnFilter:active {
            transform: scale(.97);
        }

        /* =====================================================
   TABLE WRAPPER
===================================================== */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
        }

        /* =====================================================
   TABLE
===================================================== */
        #table-laporan-kandang {
            width: 100%;
            min-width: 1200px;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid var(--border);
        }

        /* HEADER */
        #table-laporan-kandang thead th {
            background: var(--primary-light);
            color: #0d47a1;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            padding: 10px 8px;
            border: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 3;
        }

        /* BODY */
        #table-laporan-kandang tbody td {
            font-size: 13px;
            padding: 10px 8px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid var(--border);
            background: #fff;
            letter-spacing: .3px;
        }

        /* ZEBRA */
        #table-laporan-kandang tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        /* HOVER */
        #table-laporan-kandang tbody tr:hover td {
            background: #e3f2fd;
        }


        /* =====================================================
   SCROLL CONTAINER
===================================================== */
        .table-scroll {
            max-height: 70vh;
            overflow: auto;
            position: relative;
        }

        /* =====================================================
   HEADER UTAMA (JAM | # | BULAN)
===================================================== */
        #table-laporan-kandang thead tr:first-child th {
            position: sticky;
            top: 0;
            background: #ffffff;
            z-index: 5;
        }

        /* =====================================================
   BARIS TANGGAL (1 - 31)
===================================================== */
        #table-laporan-kandang thead tr.row-tanggal th {
            position: sticky;
            top: 36px;
            /* HARUS = tinggi baris pertama */
            background: #fffde7;
            font-weight: 700;
            z-index: 4;
        }

        /* =====================================================
   FREEZE KOLOM JAM
===================================================== */
        #table-laporan-kandang th:first-child,
        #table-laporan-kandang td:first-child {
            position: sticky;
            left: 0;
            background: #ffffff;
            z-index: 6;
        }

        /* =====================================================
   FREEZE KOLOM #
===================================================== */
        #table-laporan-kandang th:nth-child(2),
        #table-laporan-kandang td:nth-child(2) {
            position: sticky;
            left: 70px;
            /* sesuaikan lebar kolom JAM */
            background: #ffffff;
            z-index: 6;
        }

        /* =====================================================
   PERTEMUAN STICKY (JAM / # / TANGGAL)
===================================================== */
        #table-laporan-kandang thead th:first-child,
        #table-laporan-kandang thead th:nth-child(2) {
            z-index: 10;
        }


        /* =====================================================
   LOADING / INFO
===================================================== */
        #table-container center {
            padding: 20px;
            font-size: 14px;
            color: #78909c;
        }

        /* =====================================================
   SCROLLBAR
===================================================== */
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #cfd8dc;
            border-radius: 10px;
        }

        
        /* =====================================================
   MOBILE OPTIMIZATION
===================================================== */
        @media (max-width: 768px) {

            .container-fluid {
                padding: 10px;
            }

            #table-laporan-kandang {
                min-width: 1300px;
            }

            #table-laporan-kandang thead th,
            #table-laporan-kandang tbody td {
                font-size: 14px;
            }
        }

        
    </style>




</head>

<body>
    <div class="container-fluid">


        <!-- Data Table -->
        <div class="card">

            <div class="card-body">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-2 align-items-end">

                            <div class="col-md-3">
                                <label for="filter_periode" class="form-label mb-0">Periode</label>
                                <select id="filter_periode" class="form-select form-select-sm">
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>

                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_tahun" class="form-label mb-0">Tahun</label>
                                <select id="filter_tahun" class="form-select form-select-sm">
                                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor


                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_kandang" class="form-label mb-0">Kandang</label>
                                <select id="filter_kandang" class="form-select form-select-sm">
                                    @foreach ($kandangs as $kandang)
                                        <option value="{{ $kandang->id }}">{{ $kandang->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 d-flex gap-1">
                                <button id="btnFilter" class="btn btn-sm btn-primary flex-fill">
                                    <i class="bi bi-filter me-1"></i> Proses
                                </button>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table-scroll">
                    <div id="table-container">
                        <center>
                            <p style="color:red;">*Silahkan Pilih Periode dan Kandang untuk ditampilkan di laporan
                                kemudian tekan tombol proses</p>
                        </center>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- end container-fluid -->
    <script src="{{ asset('template/frontend') }}/assets/js/vendor.min.js"></script>
    @include('frontend.laporan.kandang.resume_kandang_js')
</body>

</html>
