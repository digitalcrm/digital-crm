@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>View Appointment Meet Page</h1>
                </div>
            </div>
        </div>
    </section>


    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                @if(session('success'))
                <div class='alert alert-success'>
                    {{session('success')}}
                </div>
                @endif

                @if(session('error'))
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <nav class="w-100">

                                <div class="nav nav-tabs" id="product-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">
                                        Event
                                    </a>
                                    <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">
                                        Customers
                                    </a>
                                    {{-- <a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">
                                        Other details
                                    </a> --}}
                                </div>
                            </nav>
                            <div class="tab-content p-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                    <h4>{{ $bookevent->event_name ?? '' }}</h4>
                                    <p class="m-0">Price: <span class="text-muted">{{ $bookevent->price ?? '' }}</span></p>
                                    <p class="">Appointment: <span class="text-uppercase text-muted">{{ $bookevent->booking_service->name ?? '' }}</span></p>
                                    <p class="m-0">Details:</p>
                                    <p class="text-monospace text-justify m-0">{{ $bookevent->event_description ?? '' }}</p>
                                </div>
                                <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead class="">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Booking Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($bookevent->bookingCustomers as $customers)
                                            <tr>
                                                <td>{{ $loop->iteration ?? '' }}</td>
                                                <td>{{ $customers->customer_name ?? '' }}</td>
                                                <td>{{ $customers->start_from ?? '' }}</td>
                                            </tr>
                                            @empty
                                            <div class="tab-pane fade" id="author" role="tabpanel" aria-labelledby="author-tab">
                                                Booking Empty!
                                            </div>

                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{-- <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">

                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
