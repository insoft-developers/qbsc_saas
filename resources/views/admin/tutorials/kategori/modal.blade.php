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
                                  <label for="urutan" class="form-label">Urutan</label>
                                  <input type="number" id="urutan" name="urutan" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="category_name" class="form-label">Nama Kategori</label>
                                  <input type="text" id="category_name" name="category_name" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="category_slug" class="form-label">Slug</label>
                                  <input type="text" id="category_slug" name="category_slug" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="description" class="form-label">Deskripsi</label>
                                  <textarea id="description" name="description" class="form-control"></textarea>
                              </div>

                              <div class="mb-2">
                                  <label for="image" class="form-label">Image</label>
                                  <input type="file" id="image" name="image" class="form-control" accept=".jpg, .jpeg, .png">
                              </div>
                            
                              <div class="mb-2">
                                  <label for="is_active" class="form-label">Status</label>
                                  <select id="is_active" name="is_active" class="form-control">
                                      <option value="">Pilih</option>
                                      <option value="1">Aktif</option>
                                      <option value="0">Tidak Aktif</option>
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



  