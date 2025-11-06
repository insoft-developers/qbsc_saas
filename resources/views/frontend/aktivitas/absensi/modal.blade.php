  <!-- Standard modal content -->
  <div id="modal-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form id="form-tambah" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }} {{ method_field('POST') }}
                  <div class="modal-header">
                      <h4 class="modal-title" id="standard-modalLabel"></h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-12">
                              <input type="hidden" id="id" name="id">

                              <div class="mb-2">
                                  <label for="tanggal" class="form-label">Tanggal</label>
                                  <input type="date" id="tanggal" name="tanggal" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="satpam_id" class="form-label">Nama Satpam</label>
                                  <select id="satpam_id" name="satpam_id" class="form-control">
                                     <option value="">Pilih</option>
                                      @foreach ($satpams as $satpam)
                                          <option value="{{ $satpam->id }}">{{ $satpam->name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="mb-2">
                                  <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                  <input type="time" step="1" id="jam_masuk" name="jam_masuk" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="jam_keluar" class="form-label">Jam Keluar</label>
                                  <input type="time" step="1" id="jam_keluar" name="jam_keluar" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="status_absen" class="form-label">Status</label>
                                  <select id="status_absen" name="status_absen" class="form-control">
                                      <option value="">Pilih</option>
                                      <option value="1">Masuk</option>
                                      <option value="2">Pulang</option>
                                  </select>
                              </div>
                              <div class="mb-2">
                                  <label for="description" class="form-label">Keterangan</label>
                                  <input type="text" id="description" name="description" class="form-control">
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                      <button id="btn-save-data" type="submit" class="btn btn-primary">Simpan</button>
                  </div>
              </form>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
