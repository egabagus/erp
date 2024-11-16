<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Purchase Order') }}
        </h5>
    </x-slot>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Purchasing</a></li>
            <li class="breadcrumb-item active" aria-current="page">Purchase Order</li>
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
                        <div class="d-flex justify-content-between">
                            <b>Data Request Purchase</b>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="requestTable">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Req Number</th>
                                        <th>Req by</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <b>Data Purchase Order</b>
                            <a href="{{ url('purchasing/purchase-order/create/0') }}" target="_blank"
                                class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat PO</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="poTable">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>PO Number</th>
                                        <th>Req Number</th>
                                        <th>Vendor</th>
                                        <th>Payment Terms</th>
                                        <th>Remarks</th>
                                        <th>Amount</th>
                                        <th>Approval</th>
                                        <th>Action</th>
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

<script>
    $(function() {
        loadData();
    })

    var requestTable;
    var poTable;

    loadData = function() {
        if (undefined !== requestTable) {
            requestTable.destroy()
            requestTable.clear.draw()
        }

        requestTable = $('#requestTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('production/request-order/data') }}",
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                },
                error: function() {
                    hideLoading();
                }
            },
            order: [
                [1, 'asc']
            ],
            drawCallback: function(settings) {
                $('table#requestTable tr').on('click', '#btnEditVendor', function(e) {
                    e.preventDefault();

                    let data = requestTable.row($(this).parents('tr')).data()

                    editSupplier(data)
                });
                $('table#requestTable tr').on('click', '#btnDeleteVendor', function(e) {
                    e.preventDefault();

                    let data = requestTable.row($(this).parents('tr')).data()

                    deleteSupplier(data.id)
                });
            },
            columns: [{
                    orderable: false,
                    searchable: false,
                    width: '5%',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'req_number',
                    name: 'req_number'
                },
                {
                    data: 'req_by',
                    name: 'req_by'
                },
                {
                    data: 'date',
                    name: 'date',
                    render: function(data, type, row, meta) {
                        return moment(data).format('DD-MM-YYYY');
                    },
                },
                {
                    data: 'due_date',
                    name: 'due_date',
                    render: function(data, type, row, meta) {
                        return moment(data).format('DD-MM-YYYY');
                    },
                },
                {
                    data: 'app_manager',
                    name: 'app_manager',
                    render: function(data, type, row, meta) {
                        if (data == 1) {
                            if (row.proses === 1) {
                                return `<div class="badge bg-success-light p-2" style="margin-right:5px;">Approved</div><div class="badge bg-primary-light p-2">${row.po.po_number}</div>`
                            } else {
                                return `<div class="badge bg-success-light p-2">Approved</div>`
                            }
                        } else {
                            return `<div class="badge bg-warning-light p-2">Menunggu Aproval</div>`
                        }
                    }
                },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        var role = {{ auth()->user()->role_id }}
                        // console.log(role)
                        if (row.app_manager == 0) {
                            return ``
                        } else if (row.app_manager == 1) {
                            if (row.proses === 1) {
                                return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <a class="btn btn-sm btn-primary" href="${`{{ url('purchasing/purchase-order/create') }}/${row.req_number}`}" target="_blank"><i class="fas fa-eye"></i></a>
                                    </div>`
                            } else {
                                if (role === 5) {
                                    return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                        <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/create') }}/${row.req_number}`}" target="_blank">Proses</a>
                                        </div>`
                                } else {
                                    return ''
                                }
                            }
                        }
                    }
                },
            ],
        });


        if (undefined !== poTable) {
            poTable.destroy()
            poTable.clear.draw()
        }

        poTable = $('#poTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('purchasing/purchase-order/data') }}",
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                },
                error: function() {
                    hideLoading();
                }
            },
            order: [
                [1, 'asc']
            ],
            drawCallback: function(settings) {
                $('table#requestTable tr').on('click', '#btnEditVendor', function(e) {
                    e.preventDefault();

                    let data = requestTable.row($(this).parents('tr')).data()

                    editSupplier(data)
                });
                $('table#requestTable tr').on('click', '#btnDeleteVendor', function(e) {
                    e.preventDefault();

                    let data = requestTable.row($(this).parents('tr')).data()

                    deleteSupplier(data.id)
                });
            },
            columns: [{
                    orderable: false,
                    searchable: false,
                    width: '5%',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'po_date',
                    name: 'po_date',
                    render: function(data, type, row, meta) {
                        return moment(data).format('DD-MM-YYYY');
                    },
                },
                {
                    data: 'po_number',
                    name: 'po_number'
                },
                {
                    data: 'req_number',
                    name: 'req_number'
                },
                {
                    data: 'vendor',
                    name: 'vendor',
                    render: function(data, type, row, meta) {
                        return data.kode_supp + ' - ' + data.nama_supp;
                    },
                },
                {
                    data: 'payment_terms',
                    name: 'payment_terms'
                },
                {
                    data: 'incoterms',
                    name: 'incoterms'
                },
                {
                    data: 'total',
                    name: 'total',
                    render: function(data, type, row, meta) {
                        return formatRupiah(data, 'Rp.');
                    },
                },
                {
                    data: 'app_finance',
                    name: 'app_finance',
                    render: function(data, type, row, meta) {
                        if (data === 1 && row.app_operational === 1) {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <div class="badge bg-primary-light p-2">OPERATIONAL</div>
                                    <div class="badge bg-success-light p-2">FINANCE</div>
                                    </div>`;
                        } else if (data === 1 && row.app_operational === 0) {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <div class="badge bg-success-light p-2">FINANCE</div>
                                    </div>`;
                        } else if (data === 0 && row.app_operational === 1) {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <div class="badge bg-primary-light p-2">OPERATIONAL</div>
                                    </div>`;
                        } else {
                            return ``;
                        }
                    },
                },
                {
                    data: 'po_number',
                    name: 'po_number',
                    render: function(data, type, row, meta) {
                        var role = {{ auth()->user()->role_id }}
                        // console.log(data)
                        if (role == 6) {
                            if (row.app_operational === 0) {
                                return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <div class="btn btn-primary btn-sm" onclick="approvePO('operational', '${data}')"><i class="fas fa-check-circle"></i></div>
                                    <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/print-pdf') }}/${data}`}" target="_blank"><i class="fas fa-print"></i></a>
                                    </div>`;
                            } else {
                                return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <div class="btn btn-danger btn-sm" onclick="canclePO('operational', '${data}')"><i class="fas fa-times"></i></div>
                                    <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/print-pdf') }}/${data}`}" target="_blank"><i class="fas fa-print"></i></a>
                                    </div>`;
                            }
                        } else if (role == 5) {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/print-pdf') }}/${data}`}" target="_blank"><i class="fas fa-print"></i></a>
                                </div>`;
                        } else if (role == 7) {
                            if (row.app_finance === 0) {
                                return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <div class="btn btn-info btn-sm" onclick="approvePO('finance', '${data}')"><i class="fas fa-check-circle"></i></div>
                                    <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/print-pdf') }}/${data}`}" target="_blank"><i class="fas fa-print"></i></a>
                                    </div>`;
                            } else {
                                return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <div class="btn btn-danger btn-sm" onclick="canclePO('finance', '${data}')"><i class="fas fa-times"></i></div>
                                    <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/print-pdf') }}/${data}`}" target="_blank"><i class="fas fa-print"></i></a>
                                    </div>`;
                            }
                        } else {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                    <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/print-pdf') }}/${data}`}" target="_blank"><i class="fas fa-print"></i></a>
                                    </div>`
                        }
                    }
                },
            ],
        });
    }

    function canclePO(type, ponumber) {
        Swal.fire({
            title: "Yakin untuk membatalkan approve PO?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('purchasing/purchase-order/cancle-approve') }}/${type}/${ponumber}`,
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
                            poTable.ajax.reload()
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

    function approvePO(type, ponumber) {
        Swal.fire({
            title: "Yakin untuk approve PO?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('purchasing/purchase-order/approve') }}/${type}/${ponumber}`,
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
                            poTable.ajax.reload()
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
