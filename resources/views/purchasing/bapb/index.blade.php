<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Tanda Terima Barang') }}
        </h5>
    </x-slot>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Purchasing</a></li>
            <li class="breadcrumb-item active" aria-current="page">TTB</li>
        </ol>
    </nav>

    <div class="mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <b>Data Tanda Terima Barang</b>
                            <a href="{{ url('purchasing/bapb/create') }}" target="_blank"
                                class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat TTB</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="table">
                                <thead class="bg-primary text-white text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>TTB Number</th>
                                        <th>PO Number</th>
                                        <th>Date</th>
                                        <th>Vendor</th>
                                        <th>Invoice Number</th>
                                        <th>Receive By</th>
                                        <th>Status</th>
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
                url: "{{ url('purchasing/bapb/data') }}",
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
            columns: [{
                    orderable: false,
                    searchable: false,
                    width: '5%',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'bapb_no',
                    name: 'bapb_no'
                },
                {
                    data: 'po_number',
                    name: 'po_number'
                },
                {
                    data: 'date',
                    name: 'date',
                    render: function(data, type, row, meta) {
                        return moment(data).format('DD-MM-YYYY');
                    },
                },
                {
                    data: 'vendor',
                    name: 'nama_supp',
                    render: function(data, type, row, meta) {
                        return data.nama_supp;
                    },
                },
                {
                    data: 'inv_no',
                    name: 'inv_no',
                },
                {
                    data: 'receive',
                    name: 'receive',
                    render: function(data, type, row, meta) {
                        return data ?? '';
                    },
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row, meta) {
                        if (data == 1) {
                            return `<div class="badge bg-success-light p-2">Aktif</div>`
                        } else {
                            return `<div class="badge bg-danger-light p-2">Non Aktif</div>`
                        }
                    }
                },
                {
                    data: 'bapb_no',
                    name: 'bapb_no',
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-center" style="gap: 5px;">
                                <button class="btn btn-sm btn-success" onclick="print('${data}')"><i class="fas fa-print"></i></button>
                                <a class="btn btn-sm btn-warning" href="{{ env('APP_URL') }}/purchasing/bapb/edit/${data}" target="_blank"><i class="fas fa-pen"></i></a>
                                <button class="btn btn-sm btn-danger" id="btnDelete"><i class="fas fa-trash"></i></button>
                                </div>`
                    }
                },
            ],
        });
    }
</script>
@include('master.supplier.create')
@include('master.supplier.edit')
