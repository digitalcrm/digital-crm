@extends('layouts.adminlte-boot-4.user')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4">
                        <h1><i class="far fa-edit"></i> Add Company</h1>
                    </div>
                </div>
            </div>
        </section>
        <!-- Main content -->
        <section class="content mx-0">
            <div class="container-fluid">
                <livewire:company.submit-company-form />
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
