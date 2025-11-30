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
                                  <label for="judul" class="form-label">Judul</label>
                                  <input type="text" id="judul" name="judul" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="pesan" class="form-label">Pesan</label>
                                  <textarea id="pesan" name="pesan" class="form-control"></textarea>
                              </div>
                              <div class="mb-2">
                                  <label for="image" class="form-label">Gambar</label>
                                  <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png" class="form-control">
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
