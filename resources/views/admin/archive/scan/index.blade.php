@extends('admin.master')

@section('admin')

    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->

    <style>
        <style>
        /* =====================================================
           PATROLI SCAN - PAGINATION
           ===================================================== */

        .patroli-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            padding: 18px 20px;

            border-top: 1px solid #edf0f2;
            background: #fff;
        }

        .patroli-pagination-info {
            color: #74788d;
            font-size: 13px;
            white-space: nowrap;
        }

        .patroli-pagination-info strong {
            color: #343a40;
            font-weight: 600;
        }

        .patroli-pagination-nav {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .patroli-page {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 34px;
            height: 34px;

            padding: 0;

            border: 1px solid #e5e7eb;
            border-radius: 6px;

            background: #fff;
            color: #495057;

            font-size: 13px;
            font-weight: 500;

            text-decoration: none !important;

            transition: all .15s ease;
        }

        .patroli-page:hover {
            background: #f5f6f8;
            border-color: #d5d9df;
            color: #343a40;
        }

        .patroli-page.active {
            background: #405189;
            border-color: #405189;
            color: #fff;
        }

        .patroli-page.disabled {
            background: #f8f9fa;
            border-color: #edf0f2;
            color: #b5b9c0;

            cursor: not-allowed;
        }

        .patroli-page i {
            font-size: 16px;
            line-height: 1;
        }


        /* =====================================================
           MOBILE
           ===================================================== */

        @media (max-width: 767.98px) {

            .patroli-pagination {
                flex-direction: column;
                justify-content: center;
                padding: 15px;
                gap: 12px;
            }

            .patroli-pagination-info {
                width: 100%;
                text-align: center;
            }

            .patroli-pagination-nav {
                justify-content: center;
            }

            .patroli-page {
                width: 32px;
                height: 32px;
            }
        }
    </style>
    </style>
    <div class="content-page">

        <div class="content">

            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="mb-1 fw-bold">
                            Scanner File Patroli
                        </h4>

                        <p class="text-muted mb-0">
                            Memeriksa file patroli di server dengan data pada database
                        </p>

                    </div>

                    <div>

                        <form method="POST" action="{{ route('backadmin.patroli-file-scan.scan') }}"
                            onsubmit="
                                return confirm(
                                    'Yakin ingin memulai scan seluruh file patroli? Proses akan berjalan melalui queue.'
                                );
                            ">

                            @csrf

                            <button type="submit" class="btn btn-primary">

                                <i class="ri-scan-line me-1"></i>

                                Scan File Patroli

                            </button>

                        </form>

                    </div>

                </div>


                {{-- SUCCESS MESSAGE --}}

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">

                        <i class="ri-checkbox-circle-line me-1"></i>

                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                        </button>

                    </div>
                @endif


                {{-- ERROR MESSAGE --}}

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">

                        <i class="ri-error-warning-line me-1"></i>

                        {{ session('error') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                        </button>

                    </div>
                @endif


                @if ($errors->any())
                    <div class="alert alert-danger">

                        <strong>Terjadi kesalahan:</strong>

                        <ul class="mb-0 mt-1">

                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach

                        </ul>

                    </div>
                @endif


                <!-- Statistik -->

                <div class="row g-3 mb-3">


                    {{-- TOTAL FILE --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Total File
                                        </p>

                                        <h3 class="mb-0 fw-bold">
                                            {{ number_format($totalFiles) }}
                                        </h3>

                                        <small class="text-muted">
                                            File ditemukan di folder patroli
                                        </small>

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


                    {{-- ADA DI DB --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Ada di Database
                                        </p>

                                        <h3 class="mb-0 fw-bold text-success">

                                            {{ number_format($exists) }}

                                        </h3>

                                        <small class="text-muted">
                                            File memiliki data patroli
                                        </small>

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


                    {{-- TIDAK ADA DI DB --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Tidak Ada di Database
                                        </p>

                                        <h3 class="mb-0 fw-bold text-danger">

                                            {{ number_format($orphan) }}

                                        </h3>

                                        <small class="text-danger">

                                            {{ $orphanSize }}

                                        </small>

                                    </div>

                                    <div class="avatar-sm bg-danger-subtle rounded">

                                        <span class="avatar-title text-danger fs-22">

                                            <i class="ri-error-warning-line"></i>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- TOTAL STORAGE --}}

                    <div class="col-xl-3 col-md-6">

                        <div class="card shadow-sm border-0">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <p class="text-muted mb-1">
                                            Total Storage
                                        </p>

                                        <h3 class="mb-0 fw-bold">

                                            {{ $totalSize }}

                                        </h3>

                                        <small class="text-muted">
                                            Ukuran seluruh file
                                        </small>

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


                <!-- Informasi -->

                <div class="alert alert-warning mb-3">

                    <div class="d-flex">

                        <div class="me-2">

                            <i class="ri-information-line fs-20"></i>

                        </div>

                        <div>

                            <strong>Informasi Scanner</strong>

                            <div class="mt-1">

                                Sistem hanya melakukan pemeriksaan file.
                                <strong>
                                    File tidak akan dihapus secara otomatis.
                                </strong>

                                File yang tidak ditemukan di database akan
                                ditandai sebagai <strong>Orphan</strong> dan
                                dapat Anda hapus secara manual.

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Filter -->

                <div class="card shadow-sm border-0 mb-3">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            <i class="ri-filter-3-line me-1"></i>

                            Filter File

                        </h5>

                    </div>


                    <div class="card-body">

                        <form method="GET" action="{{ route('backadmin.patroli-file-scan.index') }}">

                            <div class="row g-3">


                                {{-- SEARCH --}}

                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">
                                        Nama File
                                    </label>

                                    <input type="text" name="search" class="form-control"
                                        value="{{ request('search') }}" placeholder="Cari nama file...">

                                </div>


                                {{-- COMPANY --}}

                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Perusahaan
                                    </label>

                                    <select name="company_id" class="form-select">

                                        <option value="">
                                            -- Semua Perusahaan --
                                        </option>

                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>

                                                {{ $company->company_name }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                {{-- STATUS --}}

                                <div class="col-md-3">

                                    <label class="form-label fw-semibold">
                                        Status
                                    </label>

                                    <select name="status" class="form-select">

                                        <option value="">
                                            -- Semua Status --
                                        </option>

                                        <option value="exists" @selected(request('status') == 'exists')>

                                            Ada di Database

                                        </option>

                                        <option value="orphan" @selected(request('status') == 'orphan')>

                                            Tidak Ada di Database

                                        </option>

                                    </select>

                                </div>


                                {{-- BUTTON --}}

                                <div class="col-md-2 d-flex align-items-end">

                                    <button type="submit" class="btn btn-primary w-100">

                                        <i class="ri-search-line me-1"></i>

                                        Cari

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>


                <!-- Table -->

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="mb-0">

                                    <i class="ri-folder-image-line me-1"></i>

                                    Hasil Scan File

                                </h5>

                                <small class="text-muted">

                                    Menampilkan
                                    {{ $files->firstItem() ?? 0 }}
                                    -
                                    {{ $files->lastItem() ?? 0 }}
                                    dari
                                    {{ number_format($files->total()) }}
                                    file

                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th width="60">
                                            #
                                        </th>

                                        <th>
                                            File
                                        </th>

                                        <th>
                                            Perusahaan
                                        </th>

                                        <th width="130">
                                            Ukuran
                                        </th>

                                        <th width="160">
                                            Status
                                        </th>

                                        <th width="100" class="text-center">

                                            Aksi

                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse ($files as $file)
                                        <tr>

                                            {{-- NO --}}

                                            <td>

                                                {{ $files->firstItem() + $loop->index }}

                                            </td>


                                            {{-- FILE --}}

                                            <td>

                                                <div class="fw-semibold">

                                                    <a target="_blank" href="{{ asset('storage/patroli') }}/{{$file->file_name}}">{{ $file->file_name }}</a>

                                                </div>

                                                <small class="text-muted">

                                                    {{ $file->file_path }}

                                                </small>

                                            </td>


                                            {{-- COMPANY --}}

                                            <td>

                                                @if ($file->company_name)
                                                    <span class="fw-semibold">

                                                        {{ $file->company_name }}

                                                    </span>
                                                @else
                                                    <span class="text-muted">

                                                        <i class="ri-subtract-line"></i>

                                                        Tidak diketahui

                                                    </span>
                                                @endif

                                            </td>


                                            {{-- SIZE --}}

                                            <td>

                                                @php

                                                    $size = $file->file_size;

                                                    if ($size >= 1024 * 1024 * 1024) {
                                                        $sizeText =
                                                            number_format($size / 1024 / 1024 / 1024, 2) . ' GB';
                                                    } elseif ($size >= 1024 * 1024) {
                                                        $sizeText = number_format($size / 1024 / 1024, 2) . ' MB';
                                                    } elseif ($size >= 1024) {
                                                        $sizeText = number_format($size / 1024, 2) . ' KB';
                                                    } else {
                                                        $sizeText = $size . ' B';
                                                    }

                                                @endphp

                                                {{ $sizeText }}

                                            </td>


                                            {{-- STATUS --}}

                                            <td>

                                                @if ($file->status === 'exists')
                                                    <span class="badge bg-success-subtle text-success">

                                                        <i class="ri-checkbox-circle-line me-1"></i>

                                                        Ada di DB

                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">

                                                        <i class="ri-error-warning-line me-1"></i>

                                                        Tidak Ada di DB

                                                    </span>
                                                @endif

                                            </td>


                                            {{-- ACTION --}}

                                            <td class="text-center">

                                                @if ($file->status === 'orphan')
                                                    <form method="POST"
                                                        action="{{ route('backadmin.patroli-file-scan.destroy', $file) }}"
                                                        onsubmit="
                                                            return confirm(
                                                                'PERINGATAN! File ini tidak memiliki data di database. Yakin ingin menghapus file secara permanen dari server?'
                                                            );
                                                        ">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Hapus File">

                                                            <i class="ri-delete-bin-line"></i>

                                                        </button>

                                                    </form>
                                                @else
                                                    <span class="text-muted">

                                                        <i class="ri-lock-line"></i>

                                                    </span>
                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="6" class="text-center py-5">

                                                <div class="text-muted">

                                                    <i class="ri-folder-search-line fs-48"></i>

                                                    <h5 class="mt-3">

                                                        Belum Ada Data Scan

                                                    </h5>

                                                    <p class="mb-0">

                                                        Silakan klik
                                                        <strong>
                                                            Scan File Patroli
                                                        </strong>
                                                        untuk memulai pemeriksaan.

                                                    </p>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- PAGINATION --}}

                    @if ($files->hasPages())
                        <div class="patroli-pagination">

                            <div class="patroli-pagination-info">
                                Menampilkan
                                <strong>{{ $files->firstItem() }}</strong>
                                -
                                <strong>{{ $files->lastItem() }}</strong>
                                dari
                                <strong>{{ number_format($files->total()) }}</strong>
                                file
                            </div>

                            <div class="patroli-pagination-nav">

                                {{-- Previous --}}
                                @if ($files->onFirstPage())
                                    <span class="patroli-page disabled">
                                        <i class="ri-arrow-left-s-line"></i>
                                    </span>
                                @else
                                    <a href="{{ $files->previousPageUrl() }}" class="patroli-page">
                                        <i class="ri-arrow-left-s-line"></i>
                                    </a>
                                @endif


                                {{-- Nomor halaman --}}
                                @foreach ($files->getUrlRange(max(1, $files->currentPage() - 2), min($files->lastPage(), $files->currentPage() + 2)) as $page => $url)
                                    @if ($page == $files->currentPage())
                                        <span class="patroli-page active">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
                                            class="patroli-page">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach


                                {{-- Next --}}
                                @if ($files->hasMorePages())
                                    <a href="{{ $files->nextPageUrl() }}" class="patroli-page">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                @else
                                    <span class="patroli-page disabled">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </span>
                                @endif

                            </div>

                        </div>
                    @endif

                </div>


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
