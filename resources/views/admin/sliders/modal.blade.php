<!-- Standard modal content -->
<div id="modal-tambah" class="modal fade" tabindex="-1" role="dialog"
  aria-labelledby="standard-modalLabel" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">

      <form id="form-tambah" method="POST" enctype="multipart/form-data">

        {{ csrf_field() }}
        {{ method_field('POST') }}

        <div class="modal-header">
          <h4 class="modal-title" id="standard-modalLabel"></h4>

          <button type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close">
          </button>
        </div>

        <div class="modal-body">

          <div class="row">

            <div class="col-12">

              <input type="hidden" id="id" name="id">

              <!-- GAMBAR -->
              <div class="mb-3">
                <label for="image" class="form-label">
                  Gambar
                </label>

                <input
                  type="file"
                  id="image"
                  name="image"
                  class="form-control"
                  accept=".jpg,.jpeg,.png,.webp">

                <small class="text-muted">
                  Ukuran gambar akan otomatis disesuaikan menjadi
                  1740 × 904 px.
                </small>
              </div>


              <!-- JUDUL -->
              <div class="mb-3">
                <label for="title" class="form-label">
                  Judul
                </label>

                <input
                  type="text"
                  id="title"
                  name="title"
                  class="form-control"
                  placeholder="Masukkan judul slider">
              </div>


              <!-- SUBTITLE -->
              <div class="mb-3">
                <label for="subtitle" class="form-label">
                  Subtitle
                </label>

                <input
                  type="text"
                  id="subtitle"
                  name="subtitle"
                  class="form-control"
                  placeholder="Masukkan subtitle">
              </div>


              <!-- CONTENT -->
              <div class="mb-3">
                <label for="content" class="form-label">
                  Content
                </label>

                <textarea
                  id="content"
                  name="content"
                  class="form-control"
                  rows="6"
                  placeholder="Masukkan isi informasi slider..."></textarea>
              </div>


              <!-- PERUSAHAAN -->
              <div class="mb-3">
                <label class="form-label">
                  Target Perusahaan
                </label>

                <div class="border rounded p-3">

                 

                  <div class="d-flex gap-2 mb-2">

                    <button
                      type="button"
                      id="select-all-companies"
                      class="btn btn-sm btn-outline-primary">
                      Pilih Semua
                    </button>

                    <button
                      type="button"
                      id="clear-all-companies"
                      class="btn btn-sm btn-outline-secondary">
                      Hapus Semua
                    </button>

                  </div>

                  <hr class="my-2">

                  <!-- LIST PERUSAHAAN -->
                  <div
                    id="company-list"
                    style="
                max-height: 180px;
                overflow-y: auto;
            ">

                    @foreach ($companies as $company)

                    <div class="form-check mb-2">

                      <input
                        class="form-check-input company-checkbox"
                        type="checkbox"
                        name="comid[]"
                        value="{{ $company->id }}"
                        id="company_{{ $company->id }}">

                      <label
                        class="form-check-label"
                        for="company_{{ $company->id }}">
                        {{ $company->company_name }}
                      </label>

                    </div>

                    @endforeach

                  </div>

                </div>

                <small class="text-muted">
                  Pilih perusahaan tertentu, atau pilih
                  <strong>Semua Perusahaan</strong>.
                </small>
              </div>


              <!-- STATUS -->
              <div class="mb-2">

                <label for="is_active" class="form-label">
                  Status
                </label>

                <select
                  id="is_active"
                  name="is_active"
                  class="form-control">
                  <option value="">
                    Pilih
                  </option>

                  <option value="1">
                    Aktif
                  </option>

                  <option value="0">
                    Tidak Aktif
                  </option>
                </select>

              </div>

            </div>

          </div>

        </div>


        <div class="modal-footer">

          <button
            type="button"
            class="btn btn-light"
            data-bs-dismiss="modal">
            Batal
          </button>

          <button
            id="btn-save-data"
            type="submit"
            class="btn btn-primary">
            Simpan
          </button>

        </div>

      </form>

    </div>
  </div>
</div>