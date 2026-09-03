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
                                  <input readonly type="text" id="name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="email" class="form-label">Email</label>
                                  <input readonly type="email" id="email" class="form-control">
                              </div>
                            
                              <div class="mb-2">
                                  <label for="paket_id" class="form-label">Paket</label>
                                  <select id="paket_id" name="paket_id" class="form-control">
                                      <option value="">Pilih</option>
                                      @foreach($pakets as $paket)
                                        <option value="{{ $paket->id }}">{{ $paket->nama_paket }} {{ $paket->company_type == 1 ? ' (Peternakan)':' (Reguler)' }} Rp. {{ number_format($paket->harga) }}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="mb-2">
                                  <label for="is_active" class="form-label">Status Paket</label>
                                  <select id="is_active" name="is_active" class="form-control">
                                      <option value="">Pilih</option>
                                      <option value="1">Aktif</option>
                                      <option value="0">Tidak Aktif</option>
                                  </select>
                              </div>
                              <div class="mb-2">
                                  <label for="expired_date" class="form-label">Expired Date</label>
                                  <input type="date" id="expired_date" name="expired_date" class="form-control">
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



  