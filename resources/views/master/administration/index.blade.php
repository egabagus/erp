<x-app-layout>

    <x-slot name="header">
        <h5 class="font-weight-bold text-xl text-gray-800 leading-tight">
            {{ __('Data Administrasi') }}
        </h5>
    </x-slot>

    <div class="mt-2">
        <div class="alert alert-info" role="alert">
            Data ini digunakan untuk cetakan PDF.
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formAdministration" id="formAdm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            Company Name</label>
                                        <div class="col-md-9">
                                            <input type="text" name="company_name" id="company_name"
                                                class="form-control" />
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 col-form-label">Brand Name</label>
                                        <div class="col-md-9">
                                            <input type="text" name="brand_name" id="brand_name"
                                                class="form-control" />
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">Alamat</label>
                                        <div class="col-md-9">
                                            <textarea name="alamat" id="alamat" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 col-form-label">
                                            Whatsapp</label>
                                        <div class="col-md-9">
                                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 col-form-label">
                                            Website</label>
                                        <div class="col-md-9">
                                            <input type="text" name="website" id="website" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            Handphone</label>
                                        <div class="col-md-9">
                                            <input type="text" name="handphone" id="handphone"
                                                class="form-control" />
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            Email</label>
                                        <div class="col-md-9">
                                            <input type="email" name="email" id="email" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 col-form-label">
                                            Fax</label>
                                        <div class="col-md-9">
                                            <input type="text" name="fax" id="fax" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="" class="col-md-3 mandatory col-form-label">
                                            Logo</label>
                                        <div class="col-md-9">
                                            <input type="file" name="logo" class="form-control" />
                                            <img id="companyLogo" width="150px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer d-flex flex-row-reverse">
                        <button class="btn btn-md btn-primary" style="margin-left: 10px;"
                            onclick="save()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
<script>
    $(document).ready(function() {
        getAdministration()
    });

    function getAdministration() {
        $.ajax({
            url: `{{ url('master/administration/data') }}`,
            method: 'GET',
            processData: false,
            contentType: false,
            beforeSend: function() {
                showLoading();
            },
            success: (data) => {
                var value = data.data
                $('#company_name').val(value.company_name)
                $('#brand_name').val(value.brand_name)
                $('#alamat').val(value.alamat)
                $('#whatsapp').val(value.whatsapp)
                $('#website').val(value.website)
                $('#handphone').val(value.handphone)
                $('#email').val(value.email)
                $('#fax').val(value.fax)

                $('#companyLogo').attr('src', `{{ asset('storage/company-logo') }}/${value.logo}`)
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

    function save() {
        var form = document.getElementById('formAdministration')
        var formData = new FormData(form)

        $.ajax({
            url: `{{ url('master/administration/store') }}`,
            data: formData,
            method: 'POST',
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
                    getAdministration()
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
</script>
