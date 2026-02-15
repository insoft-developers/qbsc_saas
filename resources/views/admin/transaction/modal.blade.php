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
                                  <label for="userid" class="form-label">Email - Nama - Perusahaan</label>
                                  <select id="userid" name="userid" class="form-control">
                                      <option value="">Pilih</option>
                                      @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->email }} - ({{ $user->name }}) - ({{ $user->company->company_name }})</option>
                                      @endforeach
                                  </select>
                              </div>
                              
                              <div class="mb-2">
                                  <label for="paket_id" class="form-label">Paket Pembelian</label>
                                  <select id="paket_id" name="paket_id" class="form-control">
                                      <option value="">Pilih</option>
                                      @foreach($pakets as $paket)
                                        <option value="{{ $paket->id }}">{{ $paket->nama_paket }} {{ $paket->company_type == 1 ? ' (Peternakan)':' (Reguler)' }} Rp. {{ number_format($paket->harga) }}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="mb-2">
                                  <label for="payment_status" class="form-label">Status Pembayaran</label>
                                  <select id="payment_status" name="payment_status" class="form-control">
                                      <option value="">Pilih</option>
                                      <option value="PAID">PAID</option>
                                      <option value="PENDING">PENDING</option>
                                  </select>
                              </div>
                              
                              <div class="mb-2">
                                  <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                  <input type="date" id="payment_date" name="payment_date" class="form-control">
                              </div>
                              
                              <div class="mb-2">
                                  <label for="note" class="form-label">Catatan</label>
                                  <textarea id="note" name="note" class="form-control"></textarea>
                              </div>
                              <div class="mb-2">
                                  <label for="reference" class="form-label">Reference</label>
                                  <input type="text" id="reference" name="reference" class="form-control">
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



  