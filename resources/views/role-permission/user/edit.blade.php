<div class="modal fade" id="editModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formUpdateUser">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idUsers">
                    <div class="mb-3 row">
                        <label class="col-md-3 pt-2" for="">Name</label>
                        <input type="text" name="name" id="name" class="form-control col-md-9" />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-3 pt-2" for="">Email</label>
                        <input type="text" name="email" id="email" readonly class="form-control col-md-9" />
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-3 pt-2" for="">Password</label>
                        <input type="password" name="password" class="form-control col-md-9" />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-3 pt-2" for="">Roles</label>
                        <select name="roles[]" class="form-control col-md-9" id="rolesEdit">
                        </select>
                        @error('roles')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="update()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="signatureModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="signatureForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Signature</h5>
                    <button type="button" class="btn-sm btn-light" data-dismiss="modal"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="idUsersSign">
                    <div class="mb-3 row">
                        <label class="col-md-3 pt-2" for="">Select File</label>
                        <input type="file" name="signature" id="signature" class="form-control col-md-9" />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="upload()">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editUser(data) {
        $('#editModal').modal('show')

        $('#name').val(data.name)
        $('#email').val(data.email)
        $('#idUsers').val(data.id)
        $('#rolesEdit').append(`
            <option value="${data.roles[0].name}">${data.roles[0].name}</option>
        `)

        $(`#rolesEdit`).select2({
            placeholder: 'Select Role',
            theme: 'bootstrap',
            width: '75%',
            dropdownParent: '#editModal',
            ajax: {
                delay: 750,
                url: `{{ url('roles/data') }}`,
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.name
                            }
                        })
                    }
                }
            }
        })
    }

    function update() {
        var form = document.getElementById('formUpdateUser')
        var formData = new FormData(form)
        var id = $('#idUsers').val();
        console.log(formData)
        Swal.fire({
            title: "Yakin untuk mengubah data user?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function() {
            $.ajax({
                url: `{{ url('master/users/update') }}/${id}`,
                data: formData,
                method: 'POST',
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
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
                        $('#editModal').modal('hide')
                    })
                },
                error: function() {
                    hideLoading();
                    handleErrorAjax(error)
                },
                complete: function() {
                    hideLoading();
                },
            })
        });
    }

    function uploadSignature(id) {
        $('#signatureModal').modal('show')
        $('#idUsersSign').val(id)
    }

    function upload() {
        var form = document.getElementById('signatureForm')
        var formData = new FormData(form)
        var id = $('#idUsersSign').val();
        $.ajax({
            url: `{{ url('master/users/sign') }}/${id}`,
            data: formData,
            method: 'POST',
            // headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // },
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
                    $('#signatureModal').modal('hide')
                })
            },
            error: function() {
                hideLoading();
                handleErrorAjax(error)
            },
            complete: function() {
                hideLoading();
            },
        })
    }
</script>
