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
                                  <label for="qrcode" class="form-label">QRCODDE</label>
                                  <input readonly type="text" id="qrcode" name="qrcode" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
                                  <input type="text" id="nama_lokasi" name="nama_lokasi" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="latitude" class="form-label">Latitude</label>
                                  <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Diisi via app mobile">
                              </div>
                              <div class="mb-2">
                                  <label for="longitude" class="form-label">longitude</label>
                                  <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Diisi via app mobile">
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
