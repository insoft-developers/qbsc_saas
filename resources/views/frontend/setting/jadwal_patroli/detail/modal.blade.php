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
                            <input type="hidden" id="patroli_id" name="patroli_id" value="{{ $id }}">

                              <div class="mb-2">
                                  <label for="location_id" class="form-label">Lokasi Patroli</label>
                                  <select id="location_id" name="location_id" class="form-control">
                                      <option value="">Pilih</option>
                                      @foreach ($locations as $location)
                                          <option value="{{ $location->id }}">{{ $location->nama_lokasi }}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="mb-2">
                                  <label for="urutan" class="form-label">Urutan</label>
                                  <input type="number" id="urutan" name="urutan" class="form-control"
                                      placeholder="Kosongkan maka akan terisi otomatis">
                              </div>
                              <div class="mb-2">
                                  <div class="row">
                                      <div class="col-6">
                                          <label for="jam_awal" class="form-label">Jam Patroli Mulai Per Lokasi</label>
                                          <input type="time" id="jam_awal" name="jam_awal" class="form-control"
                                              placeholder="Contoh: 13:00">
                                      </div>
                                      <div class="col-6">
                                        <label for="jam_akhir" class="form-label">Jam Patroli Akhir Per Lokasi</label>
                                          <input type="time" id="jam_akhir" name="jam_akhir" class="form-control"
                                              placeholder="Contoh: 14:00">
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
