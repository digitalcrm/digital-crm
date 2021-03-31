@extends('layouts.adminlte-boot-4.user')

@section('content')
<style>
    .active-button{
        background-color: darkgray;
    }
</style>
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Booking Forms <small class="badge badge-secondary">{{ count($bookevents) }}</small></h1>

                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                            class="btn btn-sm  btn-primary mr-1 px-3"
                            href="{{ route('bookevents.create') }}"><i class="far fa-plus-square mr-1"></i>New
                        </a>
                        <a class="btn btn-outline-secondary float-right" href="{{ route('confirmed_users.index') }}">Bookings</a>
                        <a class="btn btn-outline-secondary float-right mx-1" href="{{url('/calendar')}}">Calendar</a>
                    </li>
                </ol>
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
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <a
                                    href="{{ route('bookevents.index') }}"
                                    class="btn btn-sm btn-outline-secondary mr-1 {{ request('events') == '' ? 'active' : '' }}">
                                All
                                </a>
                                {{-- <a
                                href="{{ route('bookevents.index',['events'=>'today']) }}"
                                class="btn btn-sm btn-outline-secondary mr-1 {{ request('events') == 'today' ? 'active' : '' }}">
                                Today
                                 </a> --}}
                                <a
                                href="{{ route('bookevents.index',['events'=>'upcoming']) }}"
                                class="btn btn-sm btn-outline-secondary mr-1 {{ request('events') == 'upcoming' ? 'active' : '' }}">
                                Upcoming
                                </a>
                                <a
                                href="{{ route('bookevents.index',['events'=>'completed']) }}"
                                class="btn btn-sm btn-outline-secondary mr-1 {{ request('events') == 'completed' ? 'active' : '' }}">
                                Completed
                                </a>
                            </div>
                        </div>
                    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th style="display: none">Consultant</th>
                    <th>Services</th>
                    <th>Customer Registered</th>
                    <th>Event Session</th>
                    <th>Activity Type</th>
                    <th>Price</th>
                    <th>Active</th>
                    <th>Event Start</th>
                    <th>Event End</th>
                    <th>Event Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookevents as $event)
                <tr>
                    <td>
                        <a href="{{ route('bookevents.show', $event->id) }}">
                            {{ $event->event_name ?? '' }}
                        </a>
                    </td>
                    <td>
                        {{ $event->created_at->isoFormat('Do MMMM YYYY hh:mm a') ?? '' }}
                    </td>
                    <td style="display: none">{{ $event->user->name ?? '' }}</td>
                    <td>{{ $event->bookingService->service_name ?? '' }}</td>

                    <td>
                        <a href="{{ ($event->bookingCustomers()->count() > 0) ? route('bookevents.show', $event->id) : '#' }}">
                            {{  $event->bookingCustomers->count() }}
                        </a>
                    </td>

                    <td>{{ $event->duration ?? '' }}</td>

                    <td>{{ $event->bookingActivity->title ?? '' }}</td>

                    <td>{{ $event->price ?? '' }}</td>

                    @can('update', $event)
                    <td>
                        <input data-id="{{ $event->id }}" class="toggle-class" type="checkbox"
                        data-onstyle="info" data-style="ios" data-offstyle="warning"
                        data-toggle="toggle" data-on="Active" data-size="small" data-off="Block"
                        {{ $event->isActive ? 'checked' : '' }}>
                    </td>
                    @endcan

                    <td>
                        {{ $event->event_start->isoFormat('Do MMMM YYYY hh:mm a') ?? ''}}
                    </td>

                    <td>{{ $event->event_end->isoFormat('Do MMMM YYYY hh:mm a') ?? ''}}</td>

                    <td>{{ $event->eventStatus() }}</td>

                    <td>
                        <div class="btn-group">
                            <button
                            type="button"
                            class="btn btn-outline-primary dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                            </button>
                            <div class="dropdown-menu">
                                <small>
                                    <a class="dropdown-item" href="{{ route('bookevents.edit', $event->id) }}" class="btn btn-primary btn-sm">
                                        Edit
                                    </a>
                                </small>
                                <small>
                                    <a
                                    class="dropdown-item"
                                    href=""
                                    onclick="event.preventDefault();
                                    if(confirm('Are you sure!')){
                                        $('#form-delete-{{$event->id}}').submit();
                                    }
                                    ">
                                        Delete
                                    </a>
                                    <form id="form-delete-{{ $event->id }}"
                                        action="{{ route('bookevents.destroy', $event->id) }}"
                                        method="post" style="display: none">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </small>
                            </div>
                        </div>
                     </td>
                </tr>
            @empty
            <tr>
                <td class="text-center" colspan="12">
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
            initComplete: function () {
                this.api().columns([7,8,9]).every( function (d) {//THis is used for specific column
                    var column = this;
                    var theadname = $('#exampletable th').eq([d]).text();
                    var select = $('<select class="form-control my-1"><option value="">'+theadname+': All</option></select>')
                    .appendTo( '#filtertable' )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                        );

                        column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                    } );
                    column.data().unique().sort().each( function ( d, j ) {
                        var val = $('<div/>').html(d).text();
                        select.append( '<option value="'+val+'">'+val+'</option>' )
                    } );

                } );
            }
        } );
    } );
</script>
<script>
    $(function() {
        $('.toggle-class').change(function() {
            var eventStatus = $(this).prop('checked') == true ? 1 : 0;
            var eventId = $(this).data('id');
            //    console.log(eventStatus);
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('eventStatus') }}",
                data: {'eventStatus': eventStatus, 'eventId': eventId},
                success: function(data){
                    // console.log(data.success)
                    //success message toaster
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    Toast.fire({
                        icon: 'success',
                        title: data.success
                    })
                }
            });
        })
    });

</script>
@endsection
