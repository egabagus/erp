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
                        @can('create user')
                            <div class="d-flex justify-content-between">
                                <b>Data Request Purchase</b>
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
                        @can('create user')
                            <div class="d-flex justify-content-between">
                                <b>Data Purchase Order</b>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="POTable">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Vendor</th>
                                        <th>Nama Vendor</th>
                                        <th>PIC</th>
                                        <th>Handphone</th>
                                        <th>Email</th>
                                        <th>Alamat</th>
                                        <th>Deskripsi</th>
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
                    name: 'date'
                },
                {
                    data: 'due_date',
                    name: 'due_date'
                },
                {
                    data: 'app_manager',
                    name: 'app_manager',
                    render: function(data) {
                        if (data == 1) {
                            return `<div class="badge bg-success text-white">Approved</div>`
                        } else {
                            return `<div class="badge bg-warning text-white">Menunggu Aproval</div>`
                        }
                    }
                },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row, meta) {
                        if (row.app_manager == 0) {
                            return ``
                        } else if (row.app_manager == 1) {
                            return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                <a class="btn btn-sm btn-success" href="${`{{ url('purchasing/purchase-order/create') }}/${row.req_number}`}" target="_blank">Proses</a>
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
</script>
@include('master.supplier.create')
@include('master.supplier.edit')
