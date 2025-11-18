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
                    <h4 class="mb-0 fw-bold">Data DOC Keluar</h4>
                    {{-- <button type="button" class="btn btn-info btn-sm rounded-pill" onclick="tambah_data()">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Data
                    </button> --}}
                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <!-- Filter Range Tanggal + Satpam + Tombol Export -->
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-2">
                                        <label for="filter_start" class="form-label mb-0">Dari Tanggal</label>
                                        <input type="date" id="filter_start" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filter_end" class="form-label mb-0">Sampai Tanggal</label>
                                        <input type="date" id="filter_end" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filter_satpam" class="form-label mb-0">Nama Satpam</label>
                                        <select id="filter_satpam" class="form-select form-select-sm">
                                            <option value="">Semua</option>
                                            @foreach($satpams as $satpam)
                                            <option value="{{ $satpam->id }}">{{ $satpam->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filter_ekspedisi" class="form-label mb-0">Ekspedisi</label>
                                        <select id="filter_ekspedisi" class="form-select form-select-sm">
                                            <option value="">Semua</option>
                                            @foreach($ekspeditions as $eks)
                                            <option value="{{ $eks->id }}">{{ $eks->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex gap-1">
                                        <button id="btnFilter" class="btn btn-sm btn-primary flex-fill">
                                            <i class="bi bi-filter me-1"></i> Filter
                                        </button>
                                        <button id="btnReset" class="btn btn-sm btn-secondary flex-fill">
                                            <i class="bi bi-arrow-repeat me-1"></i> Reset
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
                            <table id="list-table" class="table table-striped table-bordered w-100 align-middle nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center">Aksi</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Tgl Input</th>
                                        <th>Satpam</th>
                                        <th>Jumlah</th>
                                        <th>Ekspedisi</th>
                                        <th>Tujuan</th>
                                        <th>No Polisi</th>
                                        <th>Jenis</th>
                                        <th>Catatan</th>
                                        <th>Foto</th>
                                        <th>Perusahaan</th>
                                        <th>Sync Date</th>

                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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
    @include('frontend.aktivitas.doc.doc_js')
@endpush
