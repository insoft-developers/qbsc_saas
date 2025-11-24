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
                                  <label for="nama_tamu" class="form-label">Nama Tamu</label>
                                  <input type="text" id="nama_tamu" name="nama_tamu" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="jumlah_tamu" class="form-label">Jumlah Orang</label>
                                  <input type="number" id="jumlah_tamu" name="jumlah_tamu" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="tujuan" class="form-label">Tujuan</label>
                                  <input type="text" id="tujuan" name="tujuan" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="whatsapp" class="form-label">No Whatsapp</label>
                                  <input type="number" id="whatsapp" name="whatsapp" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="foto" class="form-label">Foto KTP/Orang</label>
                                  <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="catatan" class="form-label">Catatan</label>
                                  <input type="text" id="catatan" name="catatan" class="form-control">
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
