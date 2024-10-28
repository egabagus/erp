<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h5>
    </x-slot>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Marketing</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Invoice</li>
        </ol>
    </nav>

    <div class="mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <form id="formTransaction">
                            @csrf
                            <div class="row">
                                <div class="col-md-6" style="padding-right: 30px;">
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-4 mandatory col-form-label">
                                            Date</label>
                                        <input type="datetime-local" name="date" class="form-control col-md-8"
                                            value="<?= date('Y-m-d h:i:s') ?>" />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-4 col-form-label">
                                            PO</label>
                                        <select name="po" id="po" class="form-control col-md-8"></select>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-4 mandatory col-form-label">
                                            Customer</label>
                                        <select name="customer" id="customer" class="form-control col-md-8"></select>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-4 col-form-label mandatory">Payment
                                            Status</label>
                                        <select name="payment_status" id="payment_status" class="form-control col-md-8">
                                            <option value="" selected disabled>Select Payment Status</option>
                                            <option value="0">Unpaid</option>
                                            <option value="1">Paid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left: 30px;">
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-4 mandatory col-form-label">User</label>
                                        <input type="text" name="req_by" value="{{ Auth::user()->email }}"
                                            class="form-control col-md-8" readonly />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-4 col-form-label">Note</label>
                                        <textarea name="note" id="note" rows="2" class="form-control col-md-8"></textarea>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-4 col-form-label">Payment Note</label>
                                        <textarea name="payment_note" id="payment_note" rows="3" class="form-control col-md-8"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="freight-information mt-3">
                                <h5>FREIGHT INFORMATION</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6" style="padding-right: 30px;">
                                        <div class="mb-3 row">
                                            <label for="" class="col-md-4 col-form-label mandatory">Fregiht
                                                Type</label>
                                            <select name="freight_type" id="freight_type" class="form-control col-md-8">
                                                <option value="" selected disabled>Select Fregiht Type</option>
                                                <option value="0">Air Freight</option>
                                                <option value="1">Sea Freight</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="" class="col-md-4 mandatory col-form-label">
                                                Ship Date</label>
                                            <input type="date" name="ship_date" class="form-control col-md-8"
                                                value="<?= date('Y-m-d') ?>" />
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="" class="col-md-4 mandatory col-form-label">
                                                Origin Country</label>
                                            <input type="text" name="origin_country" id="origin_country"
                                                class="form-control col-md-8" value="Indonesia" />
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="padding-left: 30px;">
                                        <div class="mb-3 row">
                                            <label for="" class="col-md-4 mandatory col-form-label">
                                                Port of Embarkation</label>
                                            <input type="text" name="port_embarkation" id="port_embarkation"
                                                class="form-control col-md-8" />
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="" class="col-md-4 mandatory col-form-label">
                                                Port of Discharge</label>
                                            <input type="text" name="port_discharge" id="port_discharge"
                                                class="form-control col-md-8" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3" style="display: block; overflow-x: scroll; white-space: nowrap;">
                                <table class="table table-bordered" width="100%" cellspacing="0"
                                    id="addRequestTable">
                                    <thead class="bg-primary text-white text-uppercase text-center">
                                        <tr>
                                            <th width="30px"></th>
                                            <th width="250px">Item</th>
                                            <th width="100px">Unit</th>
                                            <th width="100px">Qty</th>
                                            <th width="150px">Price</th>
                                            <th width="150px">Jumlah</th>
                                            <th width="50px">PPN</th>
                                            <th width="100px">PPN (Rp.)</th>
                                            <th width="100px">Discount (Rp.)</th>
                                            <th width="150px">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemList">
                                    </tbody>
                                </table>
                            </div>

                            <button class="btn btn-md btn-success" type="button" onclick="addRow()"><i
                                    class="fas fa-plus"></i></button>
                            <div class="row">
                                <div class="col-7"></div>
                                <div class="col-5">
                                    <div class="card mt-3" style="background-color: rgb(221, 239, 255);">
                                        <div class="card-body">
                                            <div class="row font-weight-bold mb-2" style="color: black;">
                                                <div class="col-4">Freight Cost</div>
                                                <div class="col-8">
                                                    <input type="number" class="form-control" name="freight_rp"
                                                        id="freight_rp" value="0">
                                                </div>
                                            </div>
                                            <div class="row font-weight-bold mb-2" style="color: black;">
                                                <div class="col-4">Insurance</div>
                                                <div class="col-8">
                                                    <input type="number" class="form-control" name="insurance"
                                                        id="insurance" value="0">
                                                </div>
                                            </div>
                                            <div class="row font-weight-bold mb-2" style="color: black;">
                                                <div class="col-4">Others</div>
                                                <div class="col-8">
                                                    <input type="number" class="form-control" name="others"
                                                        id="others" value="0">
                                                </div>
                                            </div>
                                            <div class="row font-weight-bold" style="color: black;">
                                                <div class="col-4">Subtotal</div>
                                                <div class="col-8 text-right" id="rSubtotal"></div>
                                            </div>
                                            <div class="row font-weight-bold" style="color: black;">
                                                <div class="col-4">PPN</div>
                                                <div class="col-8 text-right" id="rPPN"></div>
                                            </div>
                                            <div class="row font-weight-bold" style="color: black;">
                                                <div class="col-4">Diskon</div>
                                                <div class="col-8 text-right" id="rDisc"></div>
                                            </div>
                                            <div class="row font-weight-bold mt-3 h4" style="color: black;">
                                                <div class="col-4">Total</div>
                                                <div class="col-8 text-right" id="rTotal"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        itemRow++
        $('#itemList').append(
            `<tr id="item_row_${itemRow}">
                <td>
                    <div class="btn btn-danger" onclick="deleteRow(${itemRow})"><i class="fas fa-minus"></i></div></td>
                <td>
                    <select class="form-control item_select" name="item[]" id="item_${itemRow}" style="width:100%" onchange="handleProduct(${itemRow})">
                    </select>
                    <input type="hidden" name="code[]" id="code_${itemRow}" readonly class="form-control">
                </td>
                <td><input type="text" name="unit[]" id="unit_${itemRow}" readonly class="form-control"></td>
                <td><input type="number" name="qty[]" id="qty_${itemRow}" class="form-control" onkeyup="totaline(${itemRow})"></td>
                <td><input type="text" name="price[]" id="price_${itemRow}" readonly class="form-control"></td>
                <td><input type="text" name="jumlah[]" id="jumlah_${itemRow}" readonly class="form-control"></td>
                <td><input style="margin-left:0px;transform: scale(1.5);" type="checkbox" name="ppn[]" id="ppn_${itemRow}" class="form-check-input" onclick="totaline(${itemRow})"></td>
                <td><input type="text" name="ppnrp[]" id="ppnrp_${itemRow}" class="form-control" onchange="totalppn(${itemRow})" value="0" readonly></td>
                <td><input type="number" name="disc[]" id="disc_${itemRow}" class="form-control" onkeyup="totaline(${itemRow})" value="0"></td>
                <td><input type="text" name="total[]" id="total_${itemRow}" class="form-control" readonly></td>
            </tr>`
        )

        initItem()
        sumTotal()
    }

    function deleteRow(row) {
        $(`#item_row_${row}`).remove();
        sumTotal()
    }

    function deleteRow(row) {
        $(`#item_row_${row}`).remove();
    }

    function initItem() {
        $('.item_select').select2({
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

    function handleProduct(row) {
        var codeItem = $(`#item_${row}`).val()
        $.ajax({
            url: `{{ url('purchasing/master/barang') }}/${codeItem}`,
            method: 'GET',
            dataType: 'json',
            beforeSend: function() {
                showLoading();
            },
            success: (data) => {
                var value = data.data
                if (value) {
                    $(`#unit_${row}`).val(value.satuan)
                    $(`#price_${row}`).val(value.harga)
                    $(`#code_${row}`).val(value.kode_barang)
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

    $(`#po`).select2({
        placeholder: 'Select PO',
        theme: 'bootstrap',
        allowClear: true,
        ajax: {
            delay: 750,
            url: `{{ url('purchasing/purchase-order/data') }}`,
            data: function(params) {
                var query = {
                    look: params.term
                }
                return query;
            },
            processResults: function(data) {
                return {
                    results: $.map(data.data, function(item) {
                        return {
                            text: `${item.po_number} | ${moment(item.po_date).format('D-M-Y')}`,
                            id: item.po_number
                        }
                    })
                }
            }
        }
    })

    $(`#customer`).select2({
        placeholder: 'Select Customer',
        theme: 'bootstrap',
        allowClear: true,
        ajax: {
            delay: 750,
            url: `{{ url('master/customer/data') }}`,
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
                            text: `${item.kode_cust} | ${item.nama_cust}`,
                            id: item.kode_cust
                        }
                    })
                }
            }
        }
    })

    function save() {
        var form = document.getElementById('formTransaction')
        var formData = new FormData(form)
        var subtotal = parseInt($('#rSubtotal').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        var ppn = parseInt($('#rPPN').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        var disc = parseInt($('#rDisc').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        var total = parseInt($('#rTotal').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        formData.append('total_all', total)
        formData.append('subtotal_all', subtotal)
        formData.append('ppn_all', ppn)
        formData.append('disc_all', disc)

        $.ajax({
            url: `{{ url('marketing/transaction') }}`,
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
                    html: data.message
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

    function totaline(itemRow) {
        sumTotal();
        var priceunit = $(`#price_${itemRow}`).val()
        var qty = $(`#qty_${itemRow}`).val()
        var disc = $(`#disc_${itemRow}`).val()
        var jumlah = priceunit * qty

        $(`#jumlah_${itemRow}`).val(jumlah)

        var total = (priceunit * qty) - disc

        if ($(`#ppn_${itemRow}`).is(":checked")) {
            var ppnrp = total * 12 / 100
            $(`#ppnrp_${itemRow}`).val(ppnrp)
            sumTotal();
        } else {
            var ppnrp = 0;
            $(`#ppnrp_${itemRow}`).val(0)
            sumTotal();
        }


        totalline = total + ppnrp
        $(`#total_${itemRow}`).val(totalline)
        sumTotal();
    }

    function sumTotal() {
        var table = document.getElementById('addRequestTable');
        var rowCount = table.rows.length;
        tsubtotal = 0;
        tjumlah = 0;
        tdiskon = 0;
        tppn = 0;

        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            subtotal = Number((row.cells[5].children[0].value).replace(/[^0-9\.]+/g, ""));
            diskon = Number((row.cells[8].children[0].value).replace(/[^0-9\.]+/g, ""));
            ppn = Number((row.cells[7].children[0].value).replace(/[^0-9\.]+/g, ""));
            jumlah = Number((row.cells[9].children[0].value).replace(/[^0-9\.]+/g, ""));
            tsubtotal += subtotal;
            tdiskon += diskon;
            tppn += ppn;
            tjumlah += jumlah;
        }

        $('#rSubtotal').text(formatRupiah(tsubtotal, 'Rp.'))
        $('#rPPN').text(formatRupiah(tppn, 'Rp.'))
        $('#rDisc').text(formatRupiah(tdiskon, 'Rp.'))
        $('#rTotal').text(formatRupiah(tjumlah, 'Rp.'))
    }
</script>
