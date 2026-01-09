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
                                  <label for="jumlah" class="form-label">Jumlah Penarikan Dana</label>
                                  <input type="text" id="jumlah" name="jumlah" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="rekening" class="form-label">Rekening</label>
                                  <textarea id="rekening" name="rekening" class="form-control"
                                      placeholder="Cth: BCA No. 12003913831 A/N JhON DOE"></textarea>
                              </div>
                              <div class="mb-2">
                                  <label for="keterangan" class="form-label">Keterangan</label>
                                  <textarea id="keterangan" name="keterangan" class="form-control"
                                      placeholder="Masukkan keterangan"></textarea>
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
