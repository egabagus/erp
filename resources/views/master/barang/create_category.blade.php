<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formAddCategory">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Data Kategori</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kode Kategori</label>
                        <input type="text" name="kode_kategori" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Deksripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control col-md-9" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Status</label>
                        <select name="status" class="form-control" id="status" style="width:75%">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveCategory()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function saveCategory() {
        var form = document.getElementById('formAddCategory')
        var formData = new FormData(form)

        $.ajax({
            url: `{{ url('master/categories') }}`,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoading();
            },
            success: (data) => {
                Swal.fire({
                    title: "Berhasil!",
                    type: "success",
                    icon: "success",
                }).then(function() {
                    kategoriTable.ajax.reload()
                    $('#addCategoryModal').modal('hide')
                })
            },
            error: function(error) {
                hideLoading();
                handleErrorAjax(error)
            },
            complete: function() {
                hideLoading();
            },
        })
    }
</script>
