@extends('frontend.master')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 fw-bold">Ganti Password</h4>
                </div>

                <!-- Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        @if (session('success'))
                            <div class="alert alert-success bg-success text-white border-0">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.password.update') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold">Password Lama</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Masukkan password lama Anda" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="Masukkan password baru" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="form-control" placeholder="Ulangi password baru Anda" required>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

        @include('frontend.footer')
    </div>
@endsection
