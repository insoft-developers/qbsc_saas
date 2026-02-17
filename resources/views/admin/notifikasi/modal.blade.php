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
                                  <label for="pengirim" class="form-label">Pengirim</label>
                                  <input type="text" id="pengirim" name="pengirim" class="form-control">
                              </div>
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
                                  <input type="file" id="image" name="image" class="form-control" accept=".jpg, .jpeg, .png">   
                              </div>
                              <div class="mb-2">
                                  <label for="comid" class="form-label">Kirim Ke Perusahaan</label>
                                  <select id="comid" name="comid" class="form-control">
                                      <option value="">Pilih</option>
                                      <option value="-1">Semua Perusahaan</option>
                                      @foreach($companies as $com)
                                      <option value="{{ $com->id }}">{{ $com->company_name }}</option>
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



  