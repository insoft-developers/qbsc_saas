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
                                  <label for="name" class="form-label">Nama Lengkap</label>
                                  <input type="text" id="name" name="name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="email" class="form-label">Email</label>
                                  <input type="email" id="email" name="email" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="whatsapp" class="form-label">Nomor Whatsapp</label>
                                  <input type="text" id="whatsapp" name="whatsapp" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="password" id="password" name="password" class="form-control">
                              </div>
                              <div class="mb-2" id="user-area-container">
                                  <label for="is_area" class="form-label">User Area</label>
                                  <select id="is_area" name="is_area" class="form-control">
                                      <option value="">Pilih</option>
                                      <option value="0">Tidak</option>
                                      <option value="1">Ya</option>
                                  </select>
                              </div>
                              <div class="mb-2">
                                  <label for="profile_image" class="form-label">Foto Profil</label>
                                  <input type="file" id="profile_image" name="profile_image" accept=".jpg,.jpeg,.png"
                                      class="form-control">
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



  <div id="modal-area" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <form id="form-area" method="POST">
                 @csrf
                  <div class="modal-header">
                      <h4 class="modal-title" id="standard-modalLabel"></h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-10">
                              <input type="hidden" id="user_id_area" name="user_id_area">
                              <div class="mb-2">
                                  <label for="user_key_id" class="form-label">User Key ID</label>
                                  <input placeholder="Masukkan user key id dari company yang akan di monitoring"
                                      type="text" id="user_key_id" name="user_key_id" class="form-control">
                              </div>

                          </div>
                          <div class="col-2" style="margin-left:-14px;">

                              <div class="mb-2">
                                  <button id="btn-tambah-area" style="margin-top: 28px;"
                                      class="btn btn-success">Tambahkan</button>
                              </div>

                          </div>

                      </div>
                      <div class="row">
                          <div class="col-12">
                              <table id="table-area" class="table table-bordered table-striped">
                                <thead>  
                                <tr>
                                      <th>#</th>
                                      <th>User Area</th>
                                      <th>Perusahaan</th>
                                      <th>Perusahaan Dipantau</th>
                                      <th>Owner</th>
                                      <th>Aktif</th>
                                      <th>Tanggal</th>
                                      <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                  </div>
              </form>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
