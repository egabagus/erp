<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formEditCustomer">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Data Customer</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idCustomer">
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kode Customer</label>
                        <input type="text" name="kode_cust" id="kode_cust" class="form-control col-md-9" readonly />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Nama Customer</label>
                        <input type="text" name="nama_cust" id="nama_cust" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">PIC</label>
                        <input type="text" name="pic" id="pic" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Handphone</label>
                        <input type="number" name="handphone" id="handphone" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Email</label>
                        <input type="email" name="email" id="email" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Deksripsi</label>
                        <textarea name="deskripsi" id="deskripsiEdit" rows="4" class="form-control col-md-9"></textarea>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Status</label>
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
    function editCustomer(data) {
        $('#editCustomerModal').modal('show')

        $('#kode_cust').val(data.kode_cust)
        $('#nama_cust').val(data.nama_cust)
        $('#pic').val(data.pic)
        $('#handphone').val(data.handphone)
        $('#email').val(data.email)
        $('#alamat').val(data.alamat)
        $('#deskripsiEdit').val(data.deskripsi)
        $('#statusEdit').val(data.status)
        $('#idCustomer').val(data.id)
    }

    function update() {
        var form = document.getElementById('formEditCustomer')
        var formData = new FormData(form)
        var id = $('#idCustomer').val();
        Swal.fire({
            title: "Yakin untuk mengubah data customer?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function() {
            $.ajax({
                url: `{{ url('master/customer/update') }}/${id}`,
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
                        customerTable.ajax.reload()
                        $('#editCustomerModal').modal('hide')
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
