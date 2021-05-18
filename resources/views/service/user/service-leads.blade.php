@extends('layouts.adminlte-boot-4.user')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Service Leads 
                        {{-- <small class="badge badge-secondary" id="totalBadge">{{ count($) }}</small> --}}
                    </h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            {{-- <a class="btn btn-sm btn-default mr-1" href="{{ url('/leads') }}"><i class=""></i> Leads</a> --}}
                            {{-- <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/productleads') }}"><i
                                class="fa fa-chart-pie"></i></a> --}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <x-alert />
                <div class="col-lg-12 p-0">
                    <livewire:service.service-leads />
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
