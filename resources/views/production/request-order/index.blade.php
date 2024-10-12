<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Request Order') }}
        </h5>
    </x-slot>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Production</a></li>
            <li class="breadcrumb-item active" aria-current="page">Request Order</li>
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
                        @can('create user')
                            <div class="d-flex flex-column align-items-end">
                                <a href="{{ url('production/request-order/create') }}" class="btn btn-primary btn-sm"><i
                                        class="fas fa-plus"></i> Tambah Data</a>
                            </div>
                        @endcan
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
                                        <th>Approval</th>
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

            </div>
        </div>
    </div>

</x-app-layout>

<script>
    $(function() {
        loadData();
    })

    var requestTable;

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
                        return moment(data).format('DD-MM-YYYY')
                    }
                },
                {
                    data: 'due_date',
                    name: 'due_date',
                    render: function(data, type, row, meta) {
                        return moment(data).format('DD-MM-YYYY')
                    }
                },
                {
                    data: 'app_manager',
                    name: 'app_manager',
                    render: function(data, type, row, meta) {
                        if (data == 1) {
                            if (row.proses === 1) {
                                return `<div class="badge bg-success-light p-2">Approved</div><div class="badge bg-primary-light p-2" style="margin-left:5px;">${row.po.po_number}</div>`
                            } else {
                                return `<div class="badge bg-success-light p-2">Approved</div>`
                            }
                        } else {
                            return `<div class="badge bg-warning-light p-2">Menunggu</div>`
                        }
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
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        if (row.app_manager == 0) {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                <button class="btn btn-sm btn-primary" onclick="approve('${row.req_number}')"><i class="fas fa-check-circle"></i></button>
                                <button class="btn btn-sm btn-success" onclick="print('${row.req_number}')"><i class="fas fa-print"></i></button>
                                <button class="btn btn-sm btn-warning" id="btnEditVendor"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-sm btn-danger" id="btnDeleteVendor"><i class="fas fa-trash"></i></button>
                                </div>`
                        } else if (row.app_manager == 1) {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                <button class="btn btn-sm btn-danger" onclick="cancel('${row.req_number}')"><i class="fas fa-ban"></i></button>
                                <button class="btn btn-sm btn-success" onclick="print('${row.req_number}')"><i class="fas fa-print"></i></button>
                                </div>`
                        }
                    }
                },
            ],
        });
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
