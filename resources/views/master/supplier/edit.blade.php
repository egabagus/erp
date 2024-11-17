<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formEditSupplier">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel">Edit Data Vendor</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idSupplier">
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kode Vendor</label>
                        <input type="text" name="kode_vendor" id="kode_supp" class="form-control col-md-9" readonly
                            placeholder="Otomatis" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Nama Vendor</label>
                        <input type="text" name="nama_supp" id="nama_supp" class="form-control col-md-9" />
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

<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="paymentForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Payment Vendor</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kode Vendor</label>
                        <div class="col-md-9">
                            <input type="text" name="kode_vendor" id="vendorCode" class="form-control"
                                readonly />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Nama Vendor</label>
                        <div class="col-md-9">
                            <input type="text" name="nama_supp" id="vendorName" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <hr>
                            <b>PAYMENT 1</b>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Bank</label>
                        <div class="col-md-9">
                            <input type="text" name="bank[]" id="bankOne" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Rekening</label>
                        <div class="col-md-9">
                            <input type="text" name="rekening[]" id="rekOne" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <hr>
                            <b>PAYMENT 2</b>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Bank</label>
                        <div class="col-md-9">
                            <input type="text" name="bank[]" id="bankTwo" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Rekening</label>
                        <div class="col-md-9">
                            <input type="text" name="rekening[]" id="rekTwo" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="savePayment()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editSupplier(data) {
        $('#editSupplierModal').modal('show')

        $('#kode_supp').val(data.kode_supp)
        $('#nama_supp').val(data.nama_supp)
        $('#pic').val(data.pic)
        $('#handphone').val(data.handphone)
        $('#email').val(data.email)
        $('#alamat').val(data.alamat)
        $('#deskripsiEdit').val(data.deskripsi)
        $('#statusEdit').val(data.status)
        $('#idSupplier').val(data.id)
    }

    function payment(code, name) {
        $('#paymentModal').modal('show')
        $('#vendorCode').val(code)
        $('#vendorName').val(name)

        $.ajax({
            url: `{{ url('master/supplier/payment') }}/${code}`,
            method: 'GET',
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoading();
            },
            success: (response) => {
                console.log(response)
                var value = response.data

                if (value) {
                    $('#bankOne').val(value[0].name)
                    $('#rekOne').val(value[0].desc)
                }
                if (value[1]) {
                    $('#bankTwo').val(value[1].name)
                    $('#rekTwo').val(value[1].desc)
                }
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

    function update() {
        var form = document.getElementById('formEditSupplier')
        var formData = new FormData(form)
        var id = $('#idSupplier').val();
        console.log(formData)
        Swal.fire({
            title: "Yakin untuk mengubah data vendor?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function() {
            $.ajax({
                url: `{{ url('master/supplier/update') }}/${id}`,
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
                        supplierTable.ajax.reload()
                        $('#editSupplierModal').modal('hide')
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

    function savePayment() {
        var form = document.getElementById('paymentForm')
        var formData = new FormData(form)

        $.ajax({
            url: `{{ url('master/supplier/payment') }}`,
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
                    $('#paymentModal').modal('hide')
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
