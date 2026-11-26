@extends('admin.master')

@section('admin')
<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 3px 5px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #86b7fe;
    }

    .select2-dropdown {
        z-index: 9999;
    }
</style>
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-bold">Manajemen Paket Berlangganan</h4>

                <button type="button"
                    class="btn btn-info btn-sm rounded-pill"
                    onclick="tambah_data()">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Paket
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list-table"
                            class="table table-striped table-bordered w-100 align-middle nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Nama Paket</th>
                                    <th>Urutan</th>
                                    <th>Untuk</th>
                                    <th>Harga</th>
                                    <th>Periode</th>
                                    <th>Hari</th>
                                    <th>Satpam</th>
                                    <th>Lokasi</th>
                                    <th>Farm</th>
                                    <th>User Admin</th>
                                    <th>Jenis Perusahaan</th>
                                    <th>Fitur</th>
                                    <th>Status</th>
                                    <th>Show</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('frontend.footer')
</div>
@include('admin.package.modal')
@endsection

@push('admin_scripts')
@include('admin.package.js')
@endpush