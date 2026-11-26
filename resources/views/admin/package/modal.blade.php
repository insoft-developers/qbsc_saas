<div id="modal-tambah" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="form-tambah">
                {{ csrf_field() }}
                {{ method_field('POST') }}

                <div class="modal-header">
                    <h4 class="modal-title">Tambah Paket</h4>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <h6 class="fw-bold mb-3">Informasi Paket</h6>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Nama Paket</label>
                            <input type="text" name="nama_paket"
                                id="nama_paket" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Subtitle</label>
                            <input type="text" name="subtitle"
                                id="subtitle" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Harga</label>
                            <input type="number" name="harga"
                                id="harga" class="form-control" min="0" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Periode</label>
                            <select name="periode" id="periode"
                                class="form-select" required>
                                <option value="1">Bulanan</option>
                                <option value="2">Tahunan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Tambahan Hari</label>
                            <input type="number" name="hari"
                                id="hari" class="form-control" min="0" value="0">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Jenis Perusahaan</label>
                            <select name="company_type" id="company_type"
                                class="form-select" required>
                                <option value="0">Reguler</option>
                                <option value="1">Peternakan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Status</label>
                            <select name="is_active" id="is_active"
                                class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Show?</label>
                            <select name="is_show" id="is_show"
                                class="form-select" required>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Urutan Tampil</label>
                            <input type="number" name="urutan"
                                id="urutan" class="form-control" min="0" value="0">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Berlaku Untuk Perusahaan</label>

                            <select name="comids[]" id="comids"
                                class="form-control select2" multiple="multiple"
                                style="width: 100%;">

                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">
                                    {{ $company->company_name }}
                                </option>
                                @endforeach

                            </select>

                            <small class="text-muted">
                                Bisa memilih beberapa perusahaan sekaligus.
                                Kosongkan jika berlaku untuk semua perusahaan.
                            </small>
                        </div>

                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Batas Penggunaan</h6>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Jumlah Satpam</label>
                            <input type="number" name="jumlah_satpam"
                                id="jumlah_satpam" class="form-control" min="0" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Jumlah Lokasi</label>
                            <input type="number" name="jumlah_lokasi"
                                id="jumlah_lokasi" class="form-control" min="0" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Jumlah Farm</label>
                            <input type="number" name="jumlah_farm"
                                id="jumlah_farm" class="form-control" min="0">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Jumlah User Admin</label>
                            <input type="number" name="jumlah_user_admin"
                                id="jumlah_user_admin" class="form-control" min="0">
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Fitur Paket</h6>

                    <div class="row">
                        @php
                        $features = [
                        'is_broadcast' => 'Broadcast',
                        'is_scan_tamu' => 'Scan Tamu',
                        'is_user_area' => 'User Area',
                        'is_mobile_app' => 'Mobile App',
                        'is_google_meet' => 'Google Meet',
                        'is_request_feature' => 'Request Feature',
                        ];
                        @endphp

                        @foreach($features as $field => $label)
                        <div class="col-md-6 mb-3">
                            <input type="hidden" name="{{ $field }}" value="0">

                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="{{ $field }}"
                                    id="{{ $field }}"
                                    value="1">

                                <label class="form-check-label"
                                    for="{{ $field }}">
                                    {{ $label }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">Batal</button>

                    <button id="btn-save-data" type="submit"
                        class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>