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
                                  <label for="foto" class="form-label">Foto Satpam</label>
                                  <input type="file" class="form-control" id="foto" name="foto"
                                      accept=".jpg, .jepg, .png">
                              </div>
                              <div class="mb-2">
                                  <label for="badge_id" class="form-label">Badge ID</label>
                                  <input readonly type="text" id="badge_id" name="badge_id" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="name" class="form-label">Nama Satpam</label>
                                  <input type="text" id="name" name="name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="whatsapp" class="form-label">Nomor Whatsapp</label>
                                  <input type="text" id="whatsapp" name="whatsapp" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="text" id="password" name="password" class="form-control">
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
