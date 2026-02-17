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
                                  <label for="icon" class="form-label">Gambar</label>
                                  <input type="file" id="icon" name="icon" class="form-control" accept=".jpg, .jpeg, .png">   
                              </div>
                              <div class="mb-2">
                                  <label for="asset_name" class="form-label">Judul Aplikasi</label>
                                  <input type="text" id="asset_name" name="asset_name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="asset_description" class="form-label">Deskripsi</label>
                                  <textarea id="asset_description" name="asset_description" class="form-control"></textarea>
                              </div>
                              <div class="mb-2">
                                  <label for="android_link" class="form-label">Android Link</label>
                                  <textarea id="android_link" name="android_link" class="form-control"></textarea>
                              </div>
                              <div class="mb-2">
                                  <label for="ios_link" class="form-label">Ios Link</label>
                                  <textarea id="ios_link" name="ios_link" class="form-control"></textarea>
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



  