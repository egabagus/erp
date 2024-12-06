<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Payment Method') }}
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
                                <button onclick="addPayment()" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add
                                    Data</a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="table">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Value</th>
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
                url: "{{ url('master/payment-method/data') }}",
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
                $('table#table tr').on('click', '#btnDeleteCustomer', function(e) {
                    e.preventDefault();

                    let data = table.row($(this).parents('tr')).data()

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
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'value',
                    name: 'value'
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
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-center" style="gap: 5px;">
                            <button class="btn btn-sm btn-warning" onclick="editPayment('${data}', '${row.name}', '${row.value}', '${row.status}')"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deletePayment(${data})"><i class="fas fa-trash"></i></button>
                            </div>`
                    }
                },
            ],
        });
    }

    function deletePayment(id) {
        Swal.fire({
            title: "Yakin untuk menghapus data?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            icon: "question"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: `{{ url('master/payment-method/data') }}/${id}`,
                    method: 'DELETE',
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
                            table.ajax.reload()
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
@include('master.payment_method.create')
@include('master.payment_method.edit')
