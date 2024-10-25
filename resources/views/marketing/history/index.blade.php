<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('History Transaction') }}
        </h5>
    </x-slot>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Marketing</a></li>
            <li class="breadcrumb-item active" aria-current="page">History Transaction</li>
        </ol>
    </nav>

    <div class="mt-2">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card mt-3">
                    <div class="card-header">
                        <div class="d-flex flex-column align-items-end">
                            <a href="{{ url('production/request-order/create') }}" class="btn btn-primary btn-sm"><i
                                    class="fas fa-file-excel"></i> Export Excel</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display: block; overflow-x: scroll; white-space: nowrap;">
                            <table class="table table-bordered" id="table">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>File</th>
                                        <th>Transaction Number</th>
                                        <th>Date</th>
                                        <th>PO Number</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
<div class="modal fade" id="fileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card-header">
                <h4>File Invoice</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-success btn-block d-flex flex-column align-items-center px-4 pt-3">
                            <i class="fas fa-file-alt" style="font-size: 30px;"></i>
                            <span class="font-weight-bold mt-1">Proforma Invoice</span> <!-- Text -->
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-success btn-block d-flex flex-column align-items-center px-4 pt-3">
                            <i class="fas fa-file-alt" style="font-size: 30px;"></i>
                            <span class="font-weight-bold mt-1">Invoice</span> <!-- Text -->
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <button class="btn btn-success btn-block d-flex flex-column align-items-center px-4 pt-3">
                            <i class="fas fa-tag" style="font-size: 30px;"></i>
                            <span class="font-weight-bold mt-1">Shipping Marks</span> <!-- Text -->
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-success btn-block d-flex flex-column align-items-center px-4 pt-3">
                            <i class="fas fa-clipboard-list" style="font-size: 30px;"></i>
                            <span class="font-weight-bold mt-1">Packing List</span> <!-- Text -->
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex flex-column align-items-end">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        loadData();
    })

    var table;

    loadData = function() {
        if (undefined !== table) {
            table.destroy()
            table.clear.draw()
        }

        table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('marketing/history-transaction/data') }}",
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                },
                error: function(error) {
                    handleErrorAjax(error)
                    hideLoading();
                }
            },
            order: [
                [2, 'desc']
            ],
            columns: [{
                    orderable: false,
                    searchable: false,
                    width: '5%',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'no_transaksi',
                    render: function(data, type, row, meta) {
                        return `
                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                <button class="btn btn-sm btn-primary" onclick="fileShow('${data}')"><i class="fas fa-file-alt"></i></button>
                            </div>
                        `
                    }
                },
                {
                    data: 'no_transaksi',
                    name: 'no_transaksi'
                },
                {
                    data: 'date',
                    name: 'date',
                    render: function(data, type, row, meta) {
                        return moment(data).format('DD-MM-YYYY h:i:s')
                    }
                },
                {
                    data: 'po_number',
                    name: 'po_number',
                    render: function(data, type, row, meta) {
                        return data ?? '-'
                    }
                },
                {
                    data: 'customer',
                    name: 'customer',
                    render: function(data, type, row, meta) {
                        return data.kode_cust + ' - ' + data.nama_cust
                    }
                },
                {
                    data: 'total',
                    name: 'total',
                    render: function(data, type, row, meta) {
                        return formatRupiah(data, 'Rp. ')
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        if (data == 1) {
                            return `<div class="badge bg-success-light p-2">Aktif</div>`
                        } else {
                            return `<div class="badge bg-danger p-2">NonAktif</div>`
                        }
                    }
                },
                {
                    data: 'no_transaksi',
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-center" style="gap: 5px;">
                            <button class="btn btn-sm btn-danger" onclick="cancel('${data}')"><i class="fas fa-trash"></i></button>
                            <button class="btn btn-sm btn-warning" onclick="cancel('${data}')"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-success" onclick="print('${data}')"><i class="fas fa-print"></i></button>
                            </div>`
                    }
                },
            ],
        });
    }

    function fileShow(number) {
        $('#fileModal').modal('show')
    }

    function deleteSupplier(id) {
        Swal.fire({
            title: "Yakin untuk menghapus data vendor?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: `{{ url('master/supplier/delete') }}/${id}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
        });
    }

    function print(req_number) {
        window.open(`{{ url('production/request-order/pdf') }}/${req_number}`, '_blank');
    }

    function approve(req_number) {
        Swal.fire({
            title: "Approve Purchase Request?",
            text: "You will not be able to recover this imaginary file!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Save",
            denyButtonText: `Don't save`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('production/request-order/approve') }}/${req_number}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                            location.reload()
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
        });
    }

    function cancel(req_number) {
        Swal.fire({
            title: "Cancel Approve Purchase Request?",
            text: "You will not be able to recover this imaginary file!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Save",
            denyButtonText: `Don't save`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('production/request-order/cancel-approve') }}/${req_number}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                            location.reload()
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
        });
    }
</script>
@include('master.supplier.create')
@include('master.supplier.edit')
