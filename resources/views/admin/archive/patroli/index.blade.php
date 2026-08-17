@extends('admin.master')

@section('admin')

    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->

    <div class="content-page">

        <div class="content">

            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <h4 class="mb-1 fw-bold">
                            Arsip Foto Patroli
                        </h4>

                        <p class="text-muted mb-0">
                            Pindahkan foto patroli ke Google Drive
                        </p>
                    </div>

                </div>


                {{-- SUCCESS MESSAGE --}}

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">

                        <i class="ri-checkbox-circle-line me-1"></i>

                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                    </div>
                @endif


                {{-- ERROR MESSAGE --}}

                @if ($errors->any())
                    <div class="alert alert-danger">

                        <strong>Terjadi kesalahan:</strong>

                        <ul class="mb-0 mt-1">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>
                @endif


                <!-- Filter -->
                <div class="card shadow-sm border-0 mb-3">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            <i class="ri-filter-3-line me-1"></i>
                            Filter Arsip
                        </h5>

                    </div>


                    <div class="card-body">

                        <form method="GET" action="{{ route('backadmin.patroli_archive.index') }}">

                            <div class="row g-3">


                                {{-- COMPANY --}}

                                <div class="col-md-5">

                                    <label class="form-label fw-semibold">
                                        Perusahaan
                                    </label>

                                    <select name="company_id" class="form-select" required>

                                        <option value="">
                                            -- Pilih Perusahaan --
                                        </option>

                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                {{-- TANGGAL MULAI --}}

                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Tanggal Mulai
                                    </label>

                                    <input type="date" name="tanggal_mulai" class="form-control"
                                        value="{{ request('tanggal_mulai') }}" required>

                                </div>


                                {{-- TANGGAL AKHIR --}}

                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Tanggal Akhir
                                    </label>

                                    <input type="date" name="tanggal_akhir" class="form-control"
                                        value="{{ request('tanggal_akhir') }}" required>

                                </div>


                                {{-- BUTTON --}}

                                <div class="col-md-1 d-flex align-items-end">

                                    <button type="submit" class="btn btn-primary w-100" title="Cari">

                                        <i class="ri-search-line"></i>

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>


                <!-- Statistik -->

                <div class="row g-3 mb-3">


                    {{-- TOTAL FOTO --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Total Foto
                                        </p>

                                        <h3 class="mb-0 fw-bold">
                                            {{ number_format($totalFoto) }}
                                        </h3>

                                    </div>

                                    <div class="avatar-sm bg-primary-subtle rounded">

                                        <span class="avatar-title text-primary fs-22">

                                            <i class="ri-image-line"></i>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SUDAH --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Sudah Diarsipkan
                                        </p>

                                        <h3 class="mb-0 fw-bold text-success">
                                            {{ number_format($sudahDiarsipkan) }}
                                        </h3>

                                    </div>

                                    <div class="avatar-sm bg-success-subtle rounded">

                                        <span class="avatar-title text-success fs-22">

                                            <i class="ri-checkbox-circle-line"></i>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- BELUM --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Belum Diarsipkan
                                        </p>

                                        <h3 class="mb-0 fw-bold text-warning">
                                            {{ number_format($belumDiarsipkan) }}
                                        </h3>

                                    </div>

                                    <div class="avatar-sm bg-warning-subtle rounded">

                                        <span class="avatar-title text-warning fs-22">

                                            <i class="ri-time-line"></i>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- UKURAN --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Total Ukuran
                                        </p>

                                        <h3 class="mb-0 fw-bold">

                                            {{ number_format($totalUkuran / 1024 / 1024 / 1024, 2) }}

                                            <small class="fs-14">
                                                GB
                                            </small>

                                        </h3>

                                    </div>

                                    <div class="avatar-sm bg-info-subtle rounded">

                                        <span class="avatar-title text-info fs-22">

                                            <i class="ri-hard-drive-3-line"></i>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Action -->

                @if (request()->filled('company_id'))
                    <div class="card shadow-sm border-0">

                        <div class="card-body">


                            <div class="alert alert-warning">

                                <div class="d-flex">

                                    <div class="me-2">

                                        <i class="ri-information-line fs-20"></i>

                                    </div>

                                    <div>

                                        <strong>Perhatian</strong>

                                        <div class="mt-1">

                                            Foto asli di server
                                            <strong>tidak akan dihapus</strong>.

                                            Sistem hanya akan membuat salinan
                                            ke Google Drive.

                                        </div>

                                    </div>

                                </div>

                            </div>


                            @if ($belumDiarsipkan > 0)
                                <form method="POST" action="{{ route('backadmin.patroli-archive.archive') }}"
                                    onsubmit="
                                        return confirm(
                                            'Yakin ingin memasukkan foto yang dipilih ke antrean Google Drive?'
                                        );
                                    ">

                                    @csrf


                                    <input type="hidden" name="company_id" value="{{ request('company_id') }}">

                                    <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">

                                    <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">


                                    <button type="submit" class="btn btn-success">

                                        <i class="ri-google-drive-line me-1"></i>

                                        Arsipkan ke Google Drive

                                        <span class="badge bg-white text-success ms-1">

                                            {{ number_format($belumDiarsipkan) }}

                                        </span>

                                    </button>



                                </form>
                            @else
                                <div class="alert alert-success mb-0">

                                    <i class="ri-checkbox-circle-line me-1"></i>

                                    Semua foto pada filter ini sudah
                                    diarsipkan ke Google Drive.

                                </div>
                            @endif

                            @if ($sudahDiarsipkan > 0)
                                <form method="POST" action="{{ route('backadmin.patroli_archive.delete_local') }}"
                                    class="d-inline"
                                    onsubmit="
            return confirm(
                'PERINGATAN! Foto yang sudah diupload ke Google Drive akan dihapus dari VPS. Lanjutkan?'
            );
        ">

                                    @csrf

                                    <input type="hidden" name="company_id" value="{{ request('company_id') }}">

                                    <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">

                                    <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">

                                    <button style="float: right;" type="submit" class="btn btn-danger">

                                        <i class="ri-delete-bin-line me-1"></i>

                                        Hapus Foto Lokal

                                        <span class="badge bg-white text-danger ms-1">
                                            {{ number_format($sudahDiarsipkan) }}
                                        </span>

                                    </button>

                                </form>
                            @endif

                        </div>

                    </div>
                @else
                    <div class="card shadow-sm border-0">

                        <div class="card-body text-center py-5">

                            <i class="ri-filter-3-line fs-48 text-muted"></i>

                            <h5 class="mt-3">
                                Silakan pilih perusahaan dan tanggal
                            </h5>

                            <p class="text-muted mb-0">
                                Gunakan filter di atas untuk melihat
                                foto patroli yang akan diarsipkan.
                            </p>

                        </div>

                    </div>
                @endif


            </div>
            <!-- end container-fluid -->

        </div>
        <!-- end content -->


        @include('frontend.footer')

    </div>

    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->

@endsection
