<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Vendor') }}
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
                                <button onclick="addSupplier()" id="btnAddSupplier" class="btn btn-primary btn-sm"><i
                                        class="fas fa-plus"></i> Tambah Data</a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="supplierTable">
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

    var supplierTable;

    loadData = function() {
        if (undefined !== supplierTable) {
            supplierTable.destroy()
            supplierTable.clear.draw()
        }

        supplierTable = $('#supplierTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('master/supplier/data') }}",
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
                $('table#supplierTable tr').on('click', '#btnEditVendor', function(e) {
                    e.preventDefault();

                    let data = supplierTable.row($(this).parents('tr')).data()

                    editSupplier(data)
                });
                $('table#supplierTable tr').on('click', '#btnDeleteVendor', function(e) {
                    e.preventDefault();

                    let data = supplierTable.row($(this).parents('tr')).data()

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
                    data: 'kode_supp',
                    name: 'kode_supp'
                },
                {
                    data: 'nama_supp',
                    name: 'nama_supp'
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
                    data: 'deskripsi',
                    name: 'deskripsi'
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
                            <button class="btn btn-sm btn-warning" id="btnEditVendor"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" id="btnDeleteVendor"><i class="fas fa-trash"></i></button>
                            </div>`
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
