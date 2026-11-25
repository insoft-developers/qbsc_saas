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
                <h4 class="mb-0 fw-bold">Data User</h4>

            </div>

            <!-- Filter User -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Level User</label>
                    <select id="filter_level" class="form-select">
                        <option value="">Semua User</option>
                        <option value="owner">Owner</option>
                        <option value="user">User</option>
                    </select>
                </div>
                 <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Paket User</label>
                    <select id="filter_paket" class="form-select">
                        <option value="">Semua</option>
                        @foreach($pakets as $paket)
                             <option value="{{ $paket->id }}">{{ $paket->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Perusahaan</label>
                    <select id="filter_company" class="form-select">
                        <option value="">Semua</option>
                        @foreach($companies as $com)
                             <option value="{{ $com->id }}">{{ $com->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list-table" class="table table-striped table-bordered w-100 align-middle nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Aksi</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Paket</th>
                                    <th>Email</th>
                                    <th>Whatsapp</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Foto</th>


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

@include('admin.user.modal')
@endsection

@push('admin_scripts')
@include('admin.user.user_js')
@endpush