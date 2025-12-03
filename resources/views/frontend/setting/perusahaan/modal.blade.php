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
                                  <label for="company_name" class="form-label">Nama Perusahaan</label>
                                  <input readonly type="text" id="company_name" name="company_name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="company_address" class="form-label">Alamat Perusahaan</label>
                                  <input type="text" id="company_address" name="company_address" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="company_pic" class="form-label">Penanggung Jawab</label>
                                  <input type="text" id="company_pic" name="company_pic" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="company_email" class="form-label">Email Perusahaan</label>
                                  <input type="text" id="company_email" name="company_email" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="company_phone" class="form-label">Telp Perusahaan</label>
                                  <input type="text" id="company_phone" name="company_phone" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="is_peternakan" class="form-label">Jenis Perusahaan</label>
                                  <input readonly type="text" id="is_peternakan" class="form-control">
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
