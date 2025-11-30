@extends('frontend.master')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-bold">Pengaturan Profil</h4>
            </div>

            <!-- Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    @if (session('success'))
            <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
        @endif

                    <form id="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Left: Profile Photo -->
                            <div class="col-md-4 text-center border-end">
                                <div class="mb-3">
                                    <img id="preview-image" 
                                         src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('default.png') }}" 
                                         class="rounded-circle border shadow-sm" 
                                         width="160" height="160" 
                                         style="object-fit: cover;">
                                </div>

                                <label class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-camera"></i> Ganti Foto
                                    <input type="file" name="profile_image" class="d-none" id="image-input" accept="image/*">
                                </label>

                                <p class="text-muted small mt-2">Format: JPG, PNG, Max: 2MB</p>
                            </div>

                            <!-- Right: Form -->
                            <div class="col-md-8">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ $user->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input readonly type="email" name="email" class="form-control" 
                                           value="{{ $user->email }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Whatsapp</label>
                                    <input type="text" name="whatsapp" class="form-control" 
                                           value="{{ $user->whatsapp }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Level</label>
                                    <input readonly type="text" name="level" class="form-control" 
                                           value="{{ $user->level }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <input readonly type="text" name="status" class="form-control" 
                                           value="{{ $user->is_active == 1 ? 'AKTIF':'TDK AKTIF' }}" required>
                                </div>

                                

                                <div class="text-end">
                                    <button class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>

                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div> <!-- end container-fluid -->
    </div> <!-- end content -->

    @include('frontend.footer')
</div>

<!-- Script Preview Image -->
<script>
    document.getElementById('image-input').addEventListener('change', function(e) {
        let reader = new FileReader();
        reader.onload = function(e){
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    });
</script>

@endsection
