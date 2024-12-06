<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formEditPayment">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Edit Data Payment Method</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idEdit">
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory col-form-label">Name</label>
                        <input type="text" name="name" id="nameEdit" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory col-form-label">Value</label>
                        <input type="text" name="value" id="valueEdit" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory col-form-label">Status</label>
                        <select name="status" class="form-control" id="statusEdit" style="width:75%">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="update()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editPayment(id, name, value, status) {
        $('#editPaymentModal').modal('show')
        $('#idEdit').val(id)
        $('#nameEdit').val(name)
        $('#valueEdit').val(value)
        $('#statusEdit').val(status).change()
    }

    function update() {
        var form = document.getElementById('formEditPayment')
        var formData = new FormData(form)
        var id = $('#idEdit').val()

        $.ajax({
            url: `{{ url('master/payment-method/data') }}/${id}`,
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
                    table.ajax.reload()
                    $('#editPaymentModal').modal('hide')
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
