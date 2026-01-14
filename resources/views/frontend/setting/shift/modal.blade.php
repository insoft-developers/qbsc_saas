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
                                  <label for="name" class="form-label">Nama Shift</label>
                                  <input type="text" id="name" name="name" class="form-control" placeholder="cth: Shift Pagi">
                              </div>
                              <div class="row">
                                  <div class="col-4" style="display: none;">
                                      <div class="mb-2">
                                          <label for="jam_masuk_awal" class="form-label">Jam Masuk Awal</label>
                                          <input step="1" type="time" id="jam_masuk_awal" name="jam_masuk_awal"
                                              class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-12">
                                      <div class="mb-2">
                                          <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                          <input step="1" type="time" id="jam_masuk" name="jam_masuk"
                                              class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-4" style="display: none;">
                                      <div class="mb-2">
                                          <label for="jam_masuk_akhir" class="form-label">Jam Masuk Akhir</label>
                                          <input step="1" type="time" id="jam_masuk_akhir" name="jam_masuk_akhir"
                                              class="form-control">
                                      </div>
                                  </div>
                              </div>


                              <div class="row">
                                  <div class="col-4" style="display: none;">
                                      <div class="mb-2">
                                          <label for="jam_pulang_awal" class="form-label">Jam Pulang Awal</label>
                                          <input step="1" type="time" id="jam_pulang_awal" name="jam_pulang_awal"
                                              class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-12">
                                      <div class="mb-2">
                                          <label for="jam_pulang" class="form-label">Jam Pulang</label>
                                          <input step="1" type="time" id="jam_pulang" name="jam_pulang"
                                              class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-4" style="display: none;">
                                      <div class="mb-2">
                                          <label for="jam_pulang_akhir" class="form-label">Jam Pulang Akhir</label>
                                          <input step="1" type="time" id="jam_pulang_akhir" name="jam_pulang_akhir"
                                              class="form-control">
                                      </div>
                                  </div>
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
