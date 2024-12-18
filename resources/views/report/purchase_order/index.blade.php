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
                        <div class="d-flex justify-content-between">
                            <b>Data Request Purchase</b>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
