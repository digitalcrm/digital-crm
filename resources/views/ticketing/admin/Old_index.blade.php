@extends('layouts.adminlte-boot-4.admin')

@section('content')

<style>
    .addlinethrough {
        text-decoration: line-through;
    }
</style>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10 mt-0">
                    <h1 class="m-0 text-dark">Tickets <small class="badge badge-secondary">{{ count($tickets) }}</small></h1>
                </div>
                <div class="col-sm-2" id="filtertable">
                    {{-- Here datatable select field appears --}}
                </div>
            </div>
        </div>
    </div>
    @include('taskmanagement.includes.message')
    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <a
                                    href="{{ route('admin.ticket') }}"
                                    class="btn btn-sm btn-outline-secondary mr-1">
                                    All
                                </a>
                                <a
                                    href="{{ route('admin.ticket',['UnassignedTickets'=>'true']) }}"
                                    class="btn btn-sm btn-outline-secondary mr-1 {{ request('UnassignedTickets') == 'true' ? 'active' : ''}}">
                                    Unassigned
                                </a>
                                @foreach ($status as $status_item)
                                    <a
                                        {{-- href="/tickets?filterBy={{ $status_item->name }}" instead of this use below one much better--}}
                                        href="{{ route('admin.ticket',['filterBy' => $status_item->name]) }}"
                                        class="btn btn-sm btn-outline-secondary mr-1 {{ request('filterBy') == $status_item->name ? 'active' : ''}}"
                                        id="mystatusfilter">
                                        {{$status_item->name}}
                                    </a>
                                @endforeach
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group mr-1">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"><i class="fa fa-outdent" aria-hidden="true"></i></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        @foreach ($priorities as $priority)
                                        <a
                                        href="{{ route('admin.ticket',['filterByPriority' => $priority->name]) }}"
                                            class="dropdown-item">
                                            {{ $priority->name }}
                                        </a>
                                        @endforeach
                                    </div>
                                </div>

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
                    <div class="card-body">
                      <table id="exampletable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>Ticket Number</th>
                          <th>Ticket Title</th>
                          <th style="display:none">User</th>
                          <th>Contact name</th>
                          <th>Contact email</th>
                          <th>Product name</th>
                          <th>Priority</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>Assign</th>
                          <th>Last updated</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td>
                                        <img class="avatar" src="{{$ticket->ticket_image}}" alt="ticket_image">
                                        {{ $ticket->ticket_number }}
                                    </td>
                                    <td><a href="{{ route('admin.ticket.show',$ticket->ticket_number) }}">
                                        {{ $ticket->name ?? 'Not selected' }}
                                    </a>
                                    </td>
                                    <td style="display: none">{{ $ticket->users->name ?? 'Not assigned' }}</td>
                                    <td>{{ $ticket->tbl_contacts->fullname() }}</td>
                                    <td>{{ $ticket->tbl_contacts->email }}</td>
                                    <td>
                                        {{ $ticket->tbl_products->name ?? 'Not selected' }}
                                    </td>
                                    <td>
                                        @if ($ticket->priority_id == 1)
                                        <span class='dot dot-sm dot-success'></span>{{ $ticket->priority->name ?? 'null' }}
                                        @elseif($ticket->priority_id == 2)
                                        <small class='dot dot-sm dot-warning'></small>{{ $ticket->priority->name ?? 'null' }}
                                        @elseif($ticket->priority_id == 3)
                                        <span class='dot dot-sm dot-danger'></span>{{ $ticket->priority->name ?? 'null' }}
                                        @endif
                                    </td>
                                    <td>{{ $ticket->ticketType->name ?? 'Null' }}</td>
                                    <td>{{ $ticket->ticketStatus->name ?? 'Null' }}</td>
                                    <td>
                                        <select
                                        class="btn btn-sm btn-default dropdown-toggle"
                                        name="userid"
                                        id="userid{{ $ticket->id }}"
                                        onchange="return assginticket('userid{{$ticket->id}}',{{$ticket->id}});">
                                        {!! (request('UnassignedTickets') == 'true') ? '<option value="Null">Unassigned</option>' : ''  !!}
                                            {{-- <option value="Null">Unassigned</option> --}}
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ $user->id == old('user_id',$ticket->user_id) ? 'selected' : ''}}>
                                                    {{ $user->name ?? ''}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ $ticket->start_date }}</td>
                                </tr>

                            @empty

                            @endforelse
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
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
                this.api().columns([2,6,7,8]).every( function (d) {//THis is used for specific column
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
<script>
    var ticketassignurl = "{{url('ticket/assign/{ticketid}/{userid}')}}";
        function assginticket(id, ticketid) {

        var userid = $("#" + id).val();

        // alert(userid);

        $.get(ticketassignurl, {
            'ticketid': ticketid,
            'userid': userid,
        }, function(result, status) {
            // alert(result);
            if (result) {
                alert('Updated Successfully...!');
                location.reload();
            } else {
                alert('Updated Failed. Try again later...!');
            }
        });

        }
</script>
@endsection
