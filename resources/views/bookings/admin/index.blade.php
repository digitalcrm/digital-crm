@extends('layouts.adminlte-boot-4.admin')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10 mt-0">
                    <h1 class="m-0 text-dark">Appointments <small class="badge badge-secondary">{{ count($appointments) }}</small></h1>
                </div>
                <div class="col-sm-2" id="filtertable">
                    {{-- Here datatable select field appears --}}
                </div>
            </div>
        </div>
    </div>
    @include('taskmanagement.includes.message')
    <section class="content">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <a
                                    href="{{ route('appointments.index') }}"
                                    class="btn btn-sm btn-outline-secondary mr-1 {{ request('events') == '' ? 'active' : '' }}">
                                    All
                                </a>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group dropdown keep-open">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            id="login" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"><i class="fa fa-filter" aria-hidden="true"></i></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                    <div class="dropdown-menu">
                                        <form action="#" id="popForm" method="get" class="p-2">
                                            <div id="filtertickettable">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table id="exampletable" class="table">
                        <thead>
                        <tr>
                          <th>Event Name</th>
                          <th>Services</th>
                          <th>User Name</th>
                          <th>Event Session</th>
                          <th>Activity Type</th>
                          <th>Price</th>
                          <th>Customers Registered</th>
                          <th>Date Posted</th>
                          <th>Event Start</th>
                          <th>Event End</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $event)
                                <tr>
                                    <td>
                                        <a href="{{ route('appointments.show', $event->id) }}">
                                            {{ $event->event_name ?? '' }}
                                        </a>
                                    </td>

                                    <td>{{ $event->bookingService->service_name ?? '' }}</td>

                                    <td>{{ $event->user->name ?? '' }}</td>

                                    <td>{{ $event->duration ?? '' }}</td>

                                    <td align="center">{{ $event->bookingActivity->title ?? '' }}</td>

                                    <td>{{ $event->price ?? '' }}</td>

                                    <td align="center">{{ $event->bookingCustomers()->count() ?? ''}}</td>

                                    <td>{{ $event->created_at->toDateString() ?? ''}}</td>

                                    <td>{{ $event->event_start->toDateString() ?? ''}}</td>

                                    <td>{{ $event->event_end->toDateString() ?? ''}}</td>
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
                    <!-- /.card-body -->
                  </div>

            </div>
        </div>
</div>
    </section>




</div>
<script>
    $(document).ready(function() {
        $('#exampletable').DataTable( {
            "ordering": false,
            initComplete: function () {
                this.api().columns([2,3,4,5]).every( function (d) {//THis is used for specific column
                    var column = this;
                    var theadname = $('#exampletable th').eq([d]).text();
                    var select = $('<select class="form-control my-1"><option value="">'+theadname+': All</option></select>')
                    .appendTo( '#filtertickettable' )
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

@endsection
