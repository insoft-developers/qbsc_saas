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
                                  <label for="location_name" class="form-label">Nama Lokasi</label>
                                  <input type="text" id="location_name" name="location_name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="latitude" class="form-label">Latitude</label>
                                  <input type="text" id="latitude" name="latitude" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="longitude" class="form-label">longitude</label>
                                  <input type="text" id="longitude" name="longitude" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="max_distance" class="form-label">Jarak Maksimal</label>
                                  <input type="number" id="max_distance" name="max_distance" class="form-control">
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
