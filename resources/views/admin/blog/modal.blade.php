  <!-- Standard modal content -->
  <div id="modal-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
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
                                  <label for="title" class="form-label">Title</label>
                                  <input type="text" id="title" name="title" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="slug" class="form-label">Slug</label>
                                  <input readonly type="text" id="slug" name="slug" class="form-control">
                              </div>
                              <div class="mb-2">
                                  <label for="image" class="form-label">Image</label>
                                  <input type="file" id="image" name="image" class="form-control" accept=".jpg, .png, .jpeg">
                              </div>
                              <div class="mb-2">
                                  <label for="content" class="form-label">Content</label>
                                  <textarea style="height: 200px;" id="content" name="content" class="form-control"></textarea>
                              </div>
                              <div class="mb-2">
                                  <label for="is_active" class="form-label">Status</label>
                                  <select id="is_active" name="is_active" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="0">Not Active</option>
                                    <option value="1">Active</option>
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
