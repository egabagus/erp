<div class="modal fade" id="editBarangModal" tabindex="-1" aria-labelledby="editBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formeditBarang">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editBarangModalLabel">Edit Data Barang</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idBarang">
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Kode Barang</label>
                        <input type="text" name="kode_barang" id="kode_barang" class="form-control col-md-9"
                            readonly />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Harga</label>
                        <input type="number" name="harga" id="harga" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Kategori</label>
                        <select name="kategori" class="form-control" id="kategoriSelectEdit" style="width:75%">
                        </select>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Satuan</label>
                        <input type="text" name="satuan" class="form-control col-md-9" id="satuan" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Deksripsi</label>
                        <textarea id="deskripsiEdit" rows="4" class="form-control col-md-9" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Status</label>
                        <select name="status" class="form-control" id="status" style="width:75%">
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
    function editBarang(data) {
        $('#editBarangModal').modal('show')
        console.log(data.deskripsi)

        $('#kode_barang').val(data.kode_barang)
        $('#nama_barang').val(data.nama_barang)
        $('#harga').val(data.harga)
        $('#satuan').val(data.satuan)
        $('#deskripsiEdit').val(data.deskripsi)
        $('#idBarang').val(data.id)
        $('#kategoriSelectEdit').append(`
            <option value="${data.category.kode_kategori}">${data.category.nama_kategori}</option>
        `)

        $(`#kategoriSelectEdit`).select2({
            placeholder: 'Pilih Kategori',
            theme: 'bootstrap',
            dropdownParent: '#editBarangModal',
            ajax: {
                delay: 750,
                url: `{{ url('master/categories') }}`,
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: `${item.kode_kategori} | ${item.nama_kategori}`,
                                id: item.kode_kategori
                            }
                        })
                    }
                }
            }
        })
    }

    function update() {
        var form = document.getElementById('formeditBarang')
        var formData = new FormData(form)
        var id = $('#idBarang').val();
        console.log(formData)
        Swal.fire({
            title: "Yakin untuk mengubah data user?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function() {
            $.ajax({
                url: `{{ url('master/barang/update') }}/${id}`,
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
                        barangTable.ajax.reload()
                        $('#editBarangModal').modal('hide')
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
