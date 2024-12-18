<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Create Purchase Order') }}
        </h5>
    </x-slot>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Purchasing</a></li>
            <li class="breadcrumb-item"><a href="#">Purchase Order</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add PO</li>
        </ol>
    </nav>

    {{-- @dd($data) --}}
    <div class="mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <form id="formAddPo">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            PO Date</label>
                                        <input type="date" name="po_date" class="form-control col-md-9"
                                            value="{{ \Carbon\Carbon::parse($data->po_date)->format('Y-m-d') }}" />
                                    </div>
                                    {{-- <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            Req Date</label>
                                        <input type="date" name="date" class="form-control col-md-9"
                                            id="req_date" readonly value="" />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Due Date</label>
                                        <input type="date" name="due_date" id="due_date"
                                            class="form-control col-md-9" readonly />
                                    </div> --}}
                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Req By</label>
                                        <input type="text" name="req_by" id="req_by"
                                            class="form-control col-md-9" readonly value="{{ $email }}" />
                                    </div> --}}
                                    {{-- <div class="mb-3 row" id="ApproveInput">
                                        <label for="" class="col-md-3 mandatory col-form-label">Approved
                                            By</label>
                                        <input type="text" name="approve_by" id="approve_by"
                                            class="form-control col-md-9" readonly />
                                    </div> --}}
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <p class="text-primary">VENDOR / SUPPLIER</p>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            Vendor</label>
                                        <select name="vendor" id="vendor" class="form-control col-md-9"
                                            onchange="selectedVendor()"></select>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Address</label>
                                        <input type="text" name="address" id="address"
                                            class="form-control col-md-9" readonly />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Phone</label>
                                        <input type="text" name="phone"id="phone" class="form-control col-md-9"
                                            readonly />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Email</label>
                                        <input type="text" name="email" id="email"
                                            class="form-control col-md-9" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-primary">NOTES</p>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Payment
                                            Terms</label>
                                        <input type="text" name="payment" class="form-control col-md-9" />
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 col-form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control col-md-9" cols="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3" style="display: block; overflow-x: scroll; white-space: nowrap;">
                                <table class="table table-bordered" width="100%" cellspacing="0" id="addRequestTable">
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
                                    <tbody id="itemRequestList">
                                    </tbody>
                                </table>
                            </div>
                            <div class="btn btn-success" onclick="addRow()"><i class="fas fa-plus"></i></div>
                            <div class="row">
                                <div class="col-7"></div>
                                <div class="col-5">
                                    <div class="card mt-3" style="background-color: rgb(221, 239, 255);">
                                        <div class="card-body">
                                            <div class="row font-weight-bold" style="color: black;">
                                                <div class="col-6">Subtotal</div>
                                                <div class="col-6 text-right" id="rSubtotal"></div>
                                            </div>
                                            <div class="row font-weight-bold" style="color: black;">
                                                <div class="col-6">PPN</div>
                                                <div class="col-6 text-right" id="rPPN"></div>
                                            </div>
                                            <div class="row font-weight-bold" style="color: black;">
                                                <div class="col-6">Diskon</div>
                                                <div class="col-6 text-right" id="rDisc"></div>
                                            </div>
                                            <div class="row font-weight-bold mt-3 h4" style="color: black;">
                                                <div class="col-6">Total</div>
                                                <div class="col-6 text-right" id="rTotal"></div>
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
    var po_number = `{{ $ponumber }}`;

    // console.log(req_number)

    $(document).ready(function() {
        // if (req_number != 0) {
        //     getRequestOrder()
        // } else {
        //     $('#due_date').prop('readonly', false)
        //     $('#req_date').prop('readonly', false)
        //     $('#ApproveInput').addClass('d-none')
        // }
        initVendor()
    })

    function getRequestOrder() {
        $.ajax({
            url: `{{ url('production/request-order/show') }}/${req_number}`,
            method: 'GET',
            beforeSend: function() {
                showLoading();
            },
            success: (data) => {
                $('#req_by').val(data.data.req_by)
                $('#approve_by').val(data.data.approve_by)
                $('#req_date').val(data.data.date.substring(0, 10))
                $('#due_date').val(data.data.due_date.substring(0, 10))
                $.each(data.data.detail, function(key, value) {
                    itemRow++
                    $('#itemRequestList').append(
                        `<tr id="item_row_${itemRow}">
                            <td></td>
                            <td>
                                <input type="text" name="item[]" id="item_${itemRow}" value="${value.product.nama_barang}" readonly class="form-control">
                                <input type="hidden" name="code[]" id="code_${itemRow}" value="${value.product.kode_barang}" readonly class="form-control">
                            </td>
                            <td><input type="text" name="unit[]" id="unit_${itemRow}" value="${value.product.satuan}" readonly class="form-control"></td>
                            <td><input type="number" name="qty[]" id="qty_${itemRow}" value="${value.qty}" class="form-control" onkeyup="totaline(${itemRow})"></td>
                            <td><input type="text" name="price[]" id="price_${itemRow}" value="${value.product.harga}" readonly class="form-control"></td>
                            <td><input type="text" name="jumlah[]" id="jumlah_${itemRow}" readonly class="form-control"></td>
                            <td><input style="margin-left:0px;transform: scale(1.5);" type="checkbox" name="ppn[]" id="ppn_${itemRow}" class="form-check-input" onclick="totaline(${itemRow})"></td>
                            <td><input type="text" name="ppnrp[]" id="ppnrp_${itemRow}" class="form-control" onchange="totalppn(${itemRow})" value="0" readonly></td>
                            <td><input type="number" name="disc[]" id="disc_${itemRow}" class="form-control" onkeyup="totaline(${itemRow})" value="0"></td>
                            <td><input type="text" name="total[]" id="total_${itemRow}" class="form-control" readonly></td>
                        </tr>`
                    )
                    totaline(itemRow)
                });
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

    function initVendor() {
        $(`#vendor`).select2({
            placeholder: 'Select Vendor',
            theme: 'bootstrap',
            ajax: {
                delay: 750,
                url: `{{ url('master/supplier/data') }}`,
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
                                text: `${item.kode_supp} | ${item.nama_supp}`,
                                id: item.kode_supp
                            }
                        })
                    }
                }
            }
        })
    }

    function selectedVendor() {
        var kodesupp = $('#vendor').val();
        $.ajax({
            url: `{{ url('master/supplier/data/show') }}/${kodesupp}`,
            method: 'GET',
            beforeSend: function() {
                showLoading();
            },
            success: (data) => {
                $('#address').val(data.data.alamat)
                $('#phone').val(data.data.handphone)
                $('#email').val(data.data.email)
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

    function addRow() {
        itemRow++
        $('#itemRequestList').append(
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

    function save() {
        var form = document.getElementById('formAddPo')
        var formData = new FormData(form)
        console.log(formData)
        var subtotal = parseInt($('#rSubtotal').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        var ppn = parseInt($('#rPPN').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        var disc = parseInt($('#rDisc').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        var total = parseInt($('#rTotal').text().replace('Rp.', '').replace(/\./g, '').trim(), 10);
        formData.append('total_all', total)
        formData.append('subtotal_all', subtotal)
        formData.append('ppn_all', ppn)
        formData.append('disc_all', disc)

        $.ajax({
            url: `{{ url('purchasing/purchase-order/store') }}`,
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
