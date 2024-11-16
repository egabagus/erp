<x-app-layout>

    <x-slot name="header">
        <h4 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Master Data User') }}
        </h4>
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
                            <button onclick="addUser()" id="btnAddUser" class="btn btn-primary btn-sm float-end"><i
                                    class="fas fa-plus"></i> Add
                                User</a>
                            @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="userTable">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th>
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

    var userTable;

    loadData = function() {
        if (undefined !== userTable) {
            userTable.destroy()
            userTable.clear.draw()
        }

        userTable = $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('master/users/data') }}",
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
                $('table#userTable tr').on('click', '#btnEditUser', function(e) {
                    e.preventDefault();

                    let data = userTable.row($(this).parents('tr')).data()

                    editUser(data)
                });
                $('table#userTable tr').on('click', '#btnDeleteUser', function(e) {
                    e.preventDefault();

                    let data = userTable.row($(this).parents('tr')).data()

                    deleteUser(data.id)
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
                    data: 'name',
                    name: 'name'
                },
                {
                    width: '15%',
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'roles',
                    name: 'roles',
                    render: function(data) {
                        let userRole = '';
                        data.forEach(function(role) {
                            userRole += '<span class="badge badge-success mr-1">' + role
                                .name +
                                '</span>';
                        });
                        return userRole;
                    },
                },
                {
                    data: 'id',
                    name: 'id',
                    render: function(data) {
                        return `<div class="d-flex justify-content-center" style="gap: 5px;">
                            <button class="btn btn-sm btn-success" id="btnSignature" onclick="uploadSignature(${data})"><i class="fas fa-signature"></i></button>
                            <button class="btn btn-sm btn-warning" id="btnEditUser"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" id="btnDeleteUser"><i class="fas fa-trash"></i></button>
                            </div>`
                    }
                },
            ],
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: "Do you want delete user?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function() {
            $.ajax({
                url: `{{ url('master/users/delete') }}/${id}`,
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
                        userTable.ajax.reload()
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
        });
    }
</script>
@include('role-permission.user.create')
@include('role-permission.user.edit')
