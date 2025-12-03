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
                                  <label for="code" class="form-label">CODE</label>
                                  <input readonly type="text" id="code" name="code" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="name" class="form-label">Nama Kandang</label>
                                  <input type="text" id="name" name="name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="std_temp" class="form-label">Suhu Standard</label>
                                  <input type="number" id="std_temp" name="std_temp" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="fan_amount" class="form-label">Jumlah Kipas</label>
                                  <input type="number" id="fan_amount" name="fan_amount" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="is_empty" class="form-label">Kondisi Kandang</label>
                                  <select id="is_empty" name="is_empty" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="1">Kosong</option>
                                    <option value="0">Berisi</option>
                                </select>
                              </div>
                              <div class="mb-2">
                                  <label for="is_active" class="form-label">Status</label>
                                  <select id="is_active" name="is_active" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak</option>
                                </select>
                              </div>
                              <div class="mb-2">
                                  <label for="pic" class="form-label">PIC</label>
                                  <select id="pic" name="pic" class="form-control">
                                    <option value="">Pilih</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
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
