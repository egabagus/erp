<x-app-layout>

    <x-slot name="header">
        <h4 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Master Roles') }}
        </h4>
    </x-slot>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
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
                        @can('create role')
                            <div class="d-flex flex-column align-items-end">
                                <button onclick="addRole()" id="btnAddBarang" class="btn btn-primary btn-sm"><i
                                        class="fas fa-plus"></i> Tambah Role</a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="roleTable">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
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
<div class="modal fade" id="showPermission" tabindex="-1" aria-labelledby="addBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formAddBarang">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addBarangModalLabel">Tambah Data Barang</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control col-md-9" readonly
                            placeholder="Otomatis" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Harga</label>
                        <input type="number" name="harga" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Kategori</label>
                        <select name="kategori" class="form-control" id="kategoriSelect" style="width:75%">
                        </select>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Satuan</label>
                        <input type="text" name="satuan" class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3">Deksripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control col-md-9" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3 row">
                        <label for="" class="col-md-3 mandatory">Status</label>
                        <select name="status" class="form-control" id="status" style="width:75%">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function() {
        loadData();
    })

    var roleTable;

    loadData = function() {
        if (undefined !== roleTable) {
            roleTable.destroy()
            roleTable.clear.draw()
        }

        roleTable = $('#roleTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('master/roles/data') }}",
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
                [0, 'asc']
            ],
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data) {
                        return `<div class="d-flex justify-content-center" style="gap: 5px;">
                            <button class="btn btn-sm btn-success" onclick="showPermission(${data})"><i class="fas fa-list"></i></button>
                            <button class="btn btn-sm btn-warning" id="btnEditUser"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" id="btnDeleteUser"><i class="fas fa-trash"></i></button>
                            </div>`
                    }
                },
            ]
        });
    }

    function showPermission(id) {
        $('#showPermission').modal('show')
    }
</script>
