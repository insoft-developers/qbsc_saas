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
                    <h4 class="mb-0 fw-bold">Laporan Kinerja Satpam</h4>

                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <!-- Filter Range Tanggal + Satpam + Tombol Export -->
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row g-2 align-items-end">
                                    @php
                                        $bulanSekarang = date('m'); // 01 - 12
                                        $tahunSekarang = date('Y');
                                        $tahunMulai = $tahunSekarang - 5;
                                    @endphp

                                    <div class="col-md-2">
                                        <label for="filter_periode" class="form-label mb-0">Periode</label>
                                        <select id="filter_periode" class="form-control form-control-sm">
                                            <option value="">Pilih</option>
                                            <option value="01" {{ $bulanSekarang == '01' ? 'selected' : '' }}>Januari
                                            </option>
                                            <option value="02" {{ $bulanSekarang == '02' ? 'selected' : '' }}>Februari
                                            </option>
                                            <option value="03" {{ $bulanSekarang == '03' ? 'selected' : '' }}>Maret
                                            </option>
                                            <option value="04" {{ $bulanSekarang == '04' ? 'selected' : '' }}>April
                                            </option>
                                            <option value="05" {{ $bulanSekarang == '05' ? 'selected' : '' }}>Mei
                                            </option>
                                            <option value="06" {{ $bulanSekarang == '06' ? 'selected' : '' }}>Juni
                                            </option>
                                            <option value="07" {{ $bulanSekarang == '07' ? 'selected' : '' }}>Juli
                                            </option>
                                            <option value="08" {{ $bulanSekarang == '08' ? 'selected' : '' }}>Agustus
                                            </option>
                                            <option value="09" {{ $bulanSekarang == '09' ? 'selected' : '' }}>September
                                            </option>
                                            <option value="10" {{ $bulanSekarang == '10' ? 'selected' : '' }}>Oktober
                                            </option>
                                            <option value="11" {{ $bulanSekarang == '11' ? 'selected' : '' }}>November
                                            </option>
                                            <option value="12" {{ $bulanSekarang == '12' ? 'selected' : '' }}>Desember
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="filter_tahun" class="form-label mb-0">Tahun</label>
                                        <select id="filter_tahun" class="form-control form-control-sm">
                                            <option value="">Pilih Tahun</option>
                                            @for ($tahun = $tahunSekarang; $tahun >= $tahunMulai; $tahun--)
                                                <option value="{{ $tahun }}"
                                                    {{ $tahunSekarang == $tahun ? 'selected' : '' }}>{{ $tahun }}
                                                </option>
                                            @endfor
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
                                        <th>Foto</th>
                                        <th width="10%">Nama Satpam</th>
                                        <th>Jabatan</th>
                                        <th width="10%">Hadir</th>
                                        <th width="10%">Tepat<br>Waktu</th>
                                        <th width="10%">Terlambat</th>
                                        <th width="10%">Total<br>Terlambat</th>
                                        <th width="10%">Cepat<br>Pulang</th>
                                        <th width="10%">Total<br>Cepat Pulang</th>
                                        <th width="10%">Titik<br>Patroli</th>
                                        <th width="10%">Patroli<br>Diuar Jadwal</th>
                                        <th width="10%">Perusahaan</th>

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
    @include('frontend.laporan.kinerja.kinerja_js')
@endpush
