<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formEditCategory">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Data Category</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idCategory">
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Kode Category</label>
                        <input type="text" name="kode_kategori" id="kode_kategori" class="form-control col-md-9"
                            readonly />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Deksripsi</label>
                        <textarea id="deskripsiEditCategory" rows="4" class="form-control col-md-9" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Status</label>
                        <select name="status" class="form-control" id="statusCategory" style="width:75%">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateCategory()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editCategory(data) {
        $('#editCategoryModal').modal('show')
        console.log(data)

        $('#kode_kategori').val(data.kode_kategori)
        $('#nama_kategori').val(data.nama_kategori)
        $('#deskripsiEditCategory').val(data.deskripsi)
        $('#idCategory').val(data.id)
        $('#statusCategory').val(data.status)
    }

    function updateCategory() {
        var form = document.getElementById('formEditCategory')
        var formData = new FormData(form)
        var id = $('#idCategory').val();
        console.log(formData)
        Swal.fire({
            title: "Yakin untuk mengubah data kategori?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function() {
            $.ajax({
                url: `{{ url('master/categories/update') }}/${id}`,
                data: formData,
                method: 'POST',
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
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
                        $('#editCategoryModal').modal('hide')
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
        });
    }
</script>
