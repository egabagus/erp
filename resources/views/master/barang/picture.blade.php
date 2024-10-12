<div class="modal fade" id="pictureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formPicture" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Picture</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control col-md-9" id="pictureKodeBarang"
                            readonly />
                        <input type="hidden" id="idCategoryPhoto">
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Upload</label>
                        <input type="file" name="foto" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Picture</label>
                        <div class="col-md-9">
                            <img id="itemPicture">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="upload()">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function uploadPhoto(data) {
        $('#pictureModal').modal('show')
        $('#pictureKodeBarang').val(data.kode_barang)
        $('#idCategoryPhoto').val(data.id)
        $('#itemPicture').attr('src', `{{ asset('storage/item-picture') }}/${data.foto}`)
    }

    function upload() {
        var form = document.getElementById('formPicture')
        var formData = new FormData(form)
        var id = $('#idCategoryPhoto').val()

        $.ajax({
            url: `{{ url('master/barang/upload') }}/${id}`,
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
                    barangTable.ajax.reload()
                    $('#pictureModal').modal('hide')
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
