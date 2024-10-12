<x-app-layout>

    <x-slot name="header">
        <h4 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Barang') }}
        </h4>
    </x-slot>

    <div class="mt-2">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#barang"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Data Barang</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#kategori"
                            type="button" role="tab" aria-controls="profile"
                            aria-selected="false">Kategori</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="barang" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card mt-3">
                            <div class="card-header">
                                @can('create user')
                                    <div class="d-flex flex-column align-items-end">
                                        <button onclick="addBarang()" id="btnAddBarang" class="btn btn-primary btn-sm"><i
                                                class="fas fa-plus"></i> Tambah Data</a>
                                    </div>
                                @endcan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0" id="barangTable">
                                        <thead class="bg-primary text-white text-uppercase">
                                            <tr>
                                                <th>No</th>
                                                <th>Code</th>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Category</th>
                                                <th>Unit</th>
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
                    <div class="tab-pane fade" id="kategori" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card mt-3">
                            <div class="card-header">
                                @can('create user')
                                    <div class="d-flex flex-column align-items-end">
                                        <button data-toggle="modal" data-target="#addCategoryModal"
                                            class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Data</a>
                                    </div>
                                @endcan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0"
                                        id="kategoriTable">
                                        <thead class="bg-primary text-white text-uppercase">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Kategori</th>
                                                <th>Deksripsi</th>
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
        </div>
    </div>

</x-app-layout>

<script>
    $(function() {
        loadData();
    })

    var barangTable;
    var kategoriTable;

    loadData = function() {
        if (undefined !== barangTable) {
            barangTable.destroy()
            barangTable.clear.draw()
        }

        barangTable = $('#barangTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('master/barang/data') }}",
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
                $('table#barangTable tr').on('click', '#btnEditUser', function(e) {
                    e.preventDefault();

                    let data = barangTable.row($(this).parents('tr')).data()

                    editBarang(data)
                });
                $('table#barangTable tr').on('click', '#btnDeleteUser', function(e) {
                    e.preventDefault();

                    let data = barangTable.row($(this).parents('tr')).data()

                    deleteBarang(data.id)
                });
                $('table#barangTable tr').on('click', '#uploadPicture', function(e) {
                    e.preventDefault();

                    let data = barangTable.row($(this).parents('tr')).data()

                    uploadPhoto(data)
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
                    data: 'kode_barang',
                    name: 'kode_barang'
                },
                {
                    data: 'nama_barang',
                    name: 'nama_barang'
                },
                {
                    data: 'harga',
                    name: 'harga',
                    render: function(data) {
                        return formatRupiah(data, 'Rp. ')
                    }
                },
                {
                    data: 'category.nama_kategori',
                    name: 'nama_kategori'
                },
                {
                    data: 'satuan',
                    name: 'satuan'
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
                            <button class="btn btn-sm btn-success" id="uploadPicture"><i class="fas fa-image"></i></button>
                            <button class="btn btn-sm btn-warning" id="btnEditUser"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" id="btnDeleteUser"><i class="fas fa-trash"></i></button>
                            </div>`
                    }
                },
            ],
        });

        if (undefined !== kategoriTable) {
            kategoriTable.destroy()
            kategoriTable.clear.draw()
        }

        kategoriTable = $('#kategoriTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('master/kategori/data') }}",
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
                $('table#kategoriTable tr').on('click', '#btnEditCategory', function(e) {
                    e.preventDefault();

                    let data = kategoriTable.row($(this).parents('tr')).data()

                    editCategory(data)
                });
                $('table#kategoriTable tr').on('click', '#btnDeleteCategory', function(e) {
                    e.preventDefault();

                    let data = kategoriTable.row($(this).parents('tr')).data()

                    deleteCategory(data.id)
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
                    data: 'kode_kategori',
                    name: 'kode_kategori'
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori'
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
                            <button class="btn btn-sm btn-warning" id="btnEditCategory"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" id="btnDeleteCategory"><i class="fas fa-trash"></i></button>
                            </div>`
                    }
                },
            ],
        });
    }

    function deleteBarang(id) {
        Swal.fire({
            title: "Yakin untuk menghapus data barang?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: `{{ url('master/barang/delete') }}/${id}`,
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
                            barangTable.ajax.reload()
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

    function deleteCategory(id) {
        Swal.fire({
            title: "Yakin untuk menghapus data kategori?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: `{{ url('master/categories/delete') }}/${id}`,
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
                            kategoriTable.ajax.reload()
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
@include('master.barang.create')
@include('master.barang.edit')
@include('master.barang.picture')
@include('master.barang.create_category')
@include('master.barang.edit_category')
