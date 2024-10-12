<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Customer') }}
        </h5>
    </x-slot>

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
                                <button onclick="addCustomer()" id="btnAddCustomer" class="btn btn-primary btn-sm"><i
                                        class="fas fa-plus"></i> Tambah Data</a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="customerTable">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Customer</th>
                                        <th>Nama</th>
                                        <th>PIC</th>
                                        <th>Handphone</th>
                                        <th>Email</th>
                                        <th>Alamat</th>
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

    var customerTable;

    loadData = function() {
        if (undefined !== customerTable) {
            customerTable.destroy()
            customerTable.clear.draw()
        }

        customerTable = $('#customerTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('master/customer/data') }}",
                type: 'GET',
                dataSrc: function(json) {
                    return json.data; // Menyesuaikan sumber data dari respons API
                },
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
                $('table#customerTable tr').on('click', '#btnEditCustomer', function(e) {
                    e.preventDefault();

                    let data = customerTable.row($(this).parents('tr')).data()

                    editCustomer(data)
                });
                $('table#customerTable tr').on('click', '#btnDeleteCustomer', function(e) {
                    e.preventDefault();

                    let data = customerTable.row($(this).parents('tr')).data()

                    deleteCustomer(data.id)
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
                    data: 'kode_cust',
                    name: 'kode_cust'
                },
                {
                    data: 'nama_cust',
                    name: 'nama_cust'
                },
                {
                    data: 'pic',
                    name: 'pic'
                },
                {
                    data: 'handphone',
                    name: 'handphone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data) {
                        if (data == 1) {
                            return `<div class="badge bg-success text-white">Aktif</div>`
                        } else {
                            return `<div class="badge bg-danger text-white">NonAktif</div>`
                        }
                    }
                },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data) {
                        return `<div class="d-flex justify-content-center" style="gap: 5px;">
                            <button class="btn btn-sm btn-warning" id="btnEditCustomer"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" id="btnDeleteCustomer"><i class="fas fa-trash"></i></button>
                            </div>`
                    }
                },
            ],
        });
    }

    function deleteCustomer(id) {
        Swal.fire({
            title: "Yakin untuk menghapus data Customer?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: `{{ url('master/customer/delete') }}/${id}`,
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
                            customerTable.ajax.reload()
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
@include('master.client.create')
@include('master.client.edit')
