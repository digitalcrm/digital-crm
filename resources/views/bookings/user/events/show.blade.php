@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>{{ $bookevent->event_name ?? '' }}</h1>
                </div>
                <div class="col-md-8">
                    <a class="btn btn-outline-secondary float-right" href="{{ route('confirmed_users.index') }}">
                        Bookings
                    </a>
                    <a class="btn btn-outline-secondary float-right mx-1" href="{{ url('/calendar') }}">
                        Calendar
                    </a>
                    <a class="btn btn-outline-secondary float-right mx-1" href="{{ route('bookevents.index',['events'=>'upcoming']) }}">
                        Appointments
                    </a>
                    <a class="btn btn-sm float-right btn-primary mr-1" href="{{ route('bookevents.create') }}">
                        <i class="far fa-plus-square mr-1"></i>New
                    </a>
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
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class='alert alert-success'>
                        {{ session('error') }}
                    </div>
                @endif
                <!-- general form elements -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0">Price: <span
                                    class="text-muted">{{ $bookevent->price ?? '' }}</span>
                            </p>
                            <p class="">Appointment: <span
                                    class="text-uppercase text-muted">{{ $bookevent->booking_service->name ?? '' }}</span>
                            </p>
                            <p class="m-0">Details:</p>
                            <p class="text-monospace text-justify m-0">
                                {{ $bookevent->event_description ?? '' }}</p>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookevent->bookingCustomers as $customers)
                                        <tr>
                                            <td>{{ $loop->iteration ?? '' }}</td>
                                            <td>{{ $customers->customer_name ?? '' }}
                                            </td>
                                            <td>{{ $customers->start_from->toDayDateTimeString() ?? '' }}
                                            </td>
                                            <td>{{ $customers->to_end->toDayDateTimeString() ?? ''}}
                                            </td>
                                        </tr>
                                    @empty
                                        <div class="tab-pane fade" id="author" role="tabpanel"
                                            aria-labelledby="author-tab">
                                            Booking Empty!
                                        </div>

                                    @endforelse
                                </tbody>
                            </table>
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
