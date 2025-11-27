@extends('frontend.master')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 fw-bold">Laporan Kandang</h4>

                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">

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

                                        <div class="btn-group flex-fill">
                                            <button class="btn btn-sm btn-success dropdown-toggle w-100"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-download me-1"></i> Export
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" id="btnExportXls"><i
                                                            class="bi bi-file-earmark-excel me-1"></i> Excel</a></li>
                                                <li><a class="dropdown-item" href="#" id="btnExportPdf"><i
                                                            class="bi bi-file-earmark-pdf me-1"></i> PDF</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div id="table-container">
                                <center><p style="color:red;">*Silahkan Pilih Periode dan Kandang untuk ditampilkan di laporan kemudian tekan tombol proses</p></center>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end container-fluid -->
        </div> <!-- end content -->

        @include('frontend.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
@endsection

@push('scripts')
    @include('frontend.laporan.kandang.laporan_kandang_js')
@endpush
