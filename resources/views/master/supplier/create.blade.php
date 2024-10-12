<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formAddSupplier">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel">Tambah Data Vendor</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kode Vendor</label>
                        <input type="text" name="kode_vendor" class="form-control col-md-9" readonly
                            placeholder="Otomatis" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Nama Vendor</label>
                        <input type="text" name="nama_supp" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">PIC</label>
                        <input type="text" name="pic" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Handphone</label>
                        <input type="number" name="handphone" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Email</label>
                        <input type="email" name="email" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Alamat</label>
                        <input type="text" name="alamat" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Deksripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control col-md-9"></textarea>
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
                    <button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addSupplier() {
        $('#addSupplierModal').modal('show')
    }

    function save() {
        var form = document.getElementById('formAddSupplier')
        var formData = new FormData(form)

        $.ajax({
            url: `{{ url('master/supplier') }}`,
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
                    supplierTable.ajax.reload()
                    $('#addSupplierModal').modal('hide')
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
