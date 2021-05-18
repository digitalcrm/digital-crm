@extends('layouts.adminlte-boot-4.user')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>{{ $service->title }}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">

            <div class="row">
                <section class="col-lg-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="card-title">
                                <ol class="breadcrumb float-sm-left">
                                    <li class="breadcrumb-item">
                                        {{ $service->serviceCategory->name }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="carousel-custom" class="" data-ride="carousel">
                                <div class="carousel-outer">
                                    <div class="carousel-inner">
                                        <img class="d-block w-100" src="{{ $service->service_img }}"
                                            alt="{{ $service->slug }}" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="col-lg-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Service Details</h3>
                        </div>
                        <div class="card-body">
                            <span class="text-uppercase">
                                {{ $service->brand }}
                            </span>

                            <h6>{{ $service->title }}</h6>
                            <h2><span>{!! auth()->user()->currency->html_code !!}</span>{{ $service->price }}
                            </h2>
                            <h5 class="mt-4">Product Details</h5>
                            <p>{{ $service->description }}</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
</div>
</section>
</div>

@endsection
