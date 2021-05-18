@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>{{ __('custom.services.heading') }} <small
                            class="badge badge-secondary"></small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1"
                                href="{{ route('services.create') }}"><i
                                    class="far fa-plus-square mr-1"></i>{{ __('custom.services.add') }}</a>
                            <a class="btn btn-sm btn-outline-secondary" href="#report"><i
                                    class="fa fa-chart-pie"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <livewire:service.service-lists />
    </section>
</div>
@endsection
