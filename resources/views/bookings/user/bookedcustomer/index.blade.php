@extends('layouts.adminlte-boot-4.user')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">All Bookings <small class="badge badge-secondary">{{ count($customers) }}</small></h1>
                </div>
                <div class="col-sm-8 mt-0">
                    <a
                    class="btn btn-sm float-right btn-primary mr-1 px-3"
                    href="{{ route('bookevents.create') }}"><i class="far fa-plus-square mr-1"></i>New
                </a>
                <a class="btn btn-outline-secondary float-right mr-1" href="{{  route('bookevents.index',['events'=>'upcoming']) }}">Appointments</a>
                <a class="btn btn-outline-secondary float-right mx-1" href="{{url('/calendar')}}">Calendar</a>
                </div>
            </div>
        </div>
    </div>
    @include('taskmanagement.includes.message')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Event Joined</th>
                                        <th>Date</th>
                                        <th>Customer Name</th>
                                        <th>Consultant</th>
                                        <th>From</th>
                                        <th>To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $bookedCustomer)
                                    <tr>
                                        <td>
                                            <a href="{{ route('bookevents.show', $bookedCustomer->bookingEvent->id) }}">
                                                {{ $bookedCustomer->bookingEvent->event_name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $bookedCustomer->bookingEvent->created_at->isoFormat('Do MMMM YYYY hh:mm a') }}
                                        </td>
                                        <td>
                                            {{ $bookedCustomer->customer_name }}
                                        </td>
                                        <td>
                                            {{ $bookedCustomer->user->name }}
                                        </td>
                                        <td>
                                            {{ $bookedCustomer->start_from->toDayDateTimeString() }}
                                        </td>
                                        <td>
                                            {{ $bookedCustomer->to_end->toDayDateTimeString() }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            No data available in table
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
@include('ticketing.message')
<script>
    $(document).ready(function() {
        $('#exampletable').DataTable( {
            "ordering": false,
        } );
    } );
</script>
@endsection
