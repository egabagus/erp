<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Add Request Order') }}
        </h5>
    </x-slot>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Production</a></li>
            <li class="breadcrumb-item"><a href="#">Request Order</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Request</li>
        </ol>
    </nav>

    <div class="mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <form id="formAddRequest">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            Request Date</label>
                                        <input type="date" name="date" class="form-control col-md-9"
                                            value="<?= date('Y-m-d') ?>" />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Due Date</label>
                                        <input type="date" name="due_date" class="form-control col-md-9" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Req By</label>
                                        <input type="text" name="req_by" value="{{ Auth::user()->email }}"
                                            class="form-control col-md-9" readonly />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 col-form-label">Note</label>
                                        <textarea name="note" id="" rows="3" class="form-control col-md-9"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" width="100%" cellspacing="0" id="addRequestTable">
                                    <thead class="bg-primary text-white text-uppercase">
                                        <tr>
                                            <th width="5%"></th>
                                            <th width="75%">Item</th>
                                            <th width="20%">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemRequestList">
                                    </tbody>
                                </table>
                            </div>

                            <button class="btn btn-md btn-success" type="button" onclick="addRow()"><i
                                    class="fas fa-plus"></i></button>
                        </form>
                    </div>
                    <div class="card-footer d-flex flex-row-reverse">
                        <button class="btn btn-md btn-primary" style="margin-left: 10px;"
                            onclick="save()">Submit</button>
                        <button class="btn btn-md btn-secondary">Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    var itemRow = 0

    function addRow() {
        $('#itemRequestList').append(
            /* html */
            `<tr id="item_row_${itemRow}">
                <td><button class="btn btn-md btn-danger" onclick="deleteRow(${itemRow})" type="button"><i
                                class="fas fa-trash"></i></button></td>
                <td><select name="item_code[]" class="form-control item_code"></select>
                </td>
                <td><input type="number" name="qty[]" id="qty"
                        class="form-control">
                </td>
            </tr>`
        );

        itemRow++
        initItem()
    }

    function deleteRow(row) {
        $(`#item_row_${row}`).remove();
    }

    function initItem() {
        $('.item_code').select2({
            placeholder: 'Select Item',
            allowClear: true,
            theme: 'bootstrap',
            ajax: {
                delay: 750,
                url: `{{ url('master/barang/data') }}`,
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
                                text: `${item.kode_barang} | ${item.nama_barang}`,
                                id: item.kode_barang
                            }
                        })
                    }
                }
            }
        });
    }

    function save() {
        var form = document.getElementById('formAddRequest')
        var formData = new FormData(form)

        $.ajax({
            url: `{{ url('production/request-order/store') }}`,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                showLoading();
            },
            success: (data) => {
                Swal.fire({
                    title: "Berhasil!",
                    type: "success",
                    icon: "success",
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
