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
                    <h1 class="m-0 text-dark">Tickets <small class="badge badge-secondary">{{ count($tickets) }}</small></h1>

                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                            class="btn btn-sm  btn-primary mr-1 px-3"
                            href="{{ route('tickets.create') }}"><i class="far fa-plus-square mr-1"></i>New Ticket
                        </a>
                        {{-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
                            <i class="fa fa-filter"></i>
                        </button> --}}
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('tickets.kanban') }}">Kanban</a>
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

            <div class="card shadow card-primary card-outline">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <a
                                href="{{ route('tickets.index',['all'=>'true']) }}"
                                class="btn btn-sm btn-outline-secondary mr-1 {{ request('all') == 'true' ? 'active' : '' }}">
                                All
                            </a>
                            @foreach ($status as $status_item)
                                <a
                                    {{-- href="/tickets?filterBy={{ $status_item->name }}" instead of this use below one much better--}}
                                    href="{{ route('tickets.index',['filterBy' => $status_item->name]) }}"
                                    class="btn btn-sm btn-outline-secondary mr-1 {{ request('filterBy') == $status_item->name ? 'active' : ''}}">
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
                                    href="{{ route('tickets.index',['filterByPriority' => $priority->name]) }}"
                                        class="dropdown-item">
                                        {{ $priority->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Below commented for sorting purpose --}}
                            {{-- <div class="btn-group mr-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"><i class="fa fa-outdent" aria-hidden="true"></i></span>
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a
                                    href="{{ route('tickets.index',['filterBy' => request('filterBy'),'filterByPriority' => 'Low']) }}"
                                        class="dropdown-item">
                                        Low
                                    </a>
                                    <a
                                    href="{{ route('tickets.index',['filterBy' => request('filterBy'),'filterByPriority' => 'Medium']) }}"
                                        class="dropdown-item">
                                        Medium
                                    </a>
                                    <a
                                    href="{{ route('tickets.index',['filterBy' => request('filterBy'),'filterByPriority' => 'High']) }}"
                                        class="dropdown-item">
                                        High
                                    </a>
                                </div>
                            </div> --}}

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
                                        {{-- @csrf --}}
                                        <div id="filtertable">
                                            {{-- <label for="email">Ticket Status:</label>
                                            <select name="ticketstatusfilter" id="ticketstatusfilter" class="form-control input-md">
                                                @foreach($status as $ticketstatus)
                                                <option value="{{$ticketstatus->id}}">{{$ticketstatus->name}}</option>
                                                @endforeach
                                            </select>
                                            <label for="name">Ticket Priority</label>
                                            <select name="priorityfilter" id="priorityfilter" class="form-control input-md">
                                                @foreach($priorities as $priority)
                                                <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                @endforeach
                                            </select>
                                            <label for="denger">Ticket Type:</label>
                                            <select name="tickettypefilter" id="tickettypefilter" class="form-control input-md">
                                                @foreach($tickettype as $type)
                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                                @endforeach
                                            </select>
                                            <br/>
                                            <button type="submit" class="btn btn-default" ><em class="icon-ok"></em> Apply</button> --}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                <!-- /.card-header -->
                {{-- <div class="card-body p-0 table-responsive"> --}}
                    <table id="exampletable" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input class="checkbox custom-control-input" type="checkbox" id="check_all">
                                        <label for="check_all" class="custom-control-label ml-2"></label>
                                    </div>
                                </th>
                                <th>Ticket Number</th>
                                <th>Ticket</th>
                                <th>Owner</th>
                                <th>Contact Name</th>
                                <th>Contact Email</th>
                                <th>Product Name</th>
                                <th>Priority</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input
                                        id="{{$ticket->id}}" data-id="{{$ticket->id}}"
                                        class="checkbox custom-control-input" type="checkbox" >
                                        <label class="custom-control-label ml-2" for="{{$ticket->id}}"></label>
                                    </div>
                                </td>
                                <td>
                                    {{-- <img class="avatar" src="{{$ticket->ticket_image}}" alt="ticket_image"> --}}
                                    <img
                                        class="avatar" src="{{ $ticket->ticketfileimage() }}" alt="ticket_image">
                                    {{ $ticket->ticket_number }}
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show',$ticket->ticket_number) }}">
                                        {{ $ticket->name ?? '' }}
                                    </a>
                                </td>
                                <td>
                                    {{ $ticket->users->name }}
                                </td>
                                <td>
                                    {{ $ticket->tbl_contacts->fullname() ?? '' }}
                                </td>
                                <td>
                                    {{ $ticket->tbl_contacts->email ?? ''}}
                                </td>
                                <td>
                                    {{ $ticket->tbl_products->name ?? 'Undefined' }}
                                </td>
                                <td style="width: 85px">
                                    {{-- {!!  $task->priority !!} --}}
                                    @if ($ticket->priority_id == 1)
                                    <span class='dot dot-sm dot-success'></span> {{ $ticket->priority->name ?? 'Null'}}
                                    @elseif($ticket->priority_id == 2)
                                    <small class='dot dot-sm dot-warning'></small> {{ $ticket->priority->name ?? 'Null'}}
                                    @elseif($ticket->priority_id == 3)
                                    <span class='dot dot-sm dot-danger'></span> {{ $ticket->priority->name ?? 'Null'}}
                                    @endif

                                </td>
                                <td>
                                    {{ $ticket->ticketType->name ?? 'Null' }}
                                </td>
                                <td>
                                    {{ $ticket->ticketStatus->name ?? 'Null' }}
                                </td>
                                <td>
                                    {{-- {{ Carbon\Carbon::parse($ticket->start_date)->format('d-m-Y') }} --}}
                                    {{ $ticket->updated_at->format('d-m-Y') }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                        type="button"
                                        class="btn btn-outline-secondary dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu">
                                        <small>
                                            <a class="dropdown-item" href="{{  route('tickets.edit',$ticket->ticket_number) }}">
                                                Edit
                                            </a>
                                        </small>
                                        <small>
                                            <a
                                            class="dropdown-item"
                                            href=""
                                            onclick="event.preventDefault();
                                            if(confirm('Are you sure!')){
                                                $('#form-delete-{{$ticket->id}}').submit();
                                            }
                                            ">
                                            Delete
                                        </a>
                                        <form
                                        style="display:none"
                                        method="post"
                                        id="form-delete-{{$ticket->id}}"
                                        action="{{route('tickets.destroy',$ticket->id)}}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </small>
                                @if(!($ticket->status_id == 3))
                                    <small>
                                        <form
                                            style="display:none"
                                            method="post"
                                            id="form-closed-{{$ticket->id}}"
                                            action="{{route('tickets.closed',$ticket->ticket_number)}}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                        </form>
                                        <a
                                            class="dropdown-item"
                                            href=""
                                            onclick="event.preventDefault();
                                            if(confirm('Are you sure want to close the Ticket!')){
                                                $('#form-closed-{{$ticket->id}}').submit();
                                            }
                                            ">
                                            Close Ticket
                                        </a>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>

                    @empty
                    <td colspan="15" class="text-center">No Tickets Available yet!</td>
                    @endforelse
                </tr>
            </tbody>
        </table>
    {{-- </div> --}}
    <!-- /.card-body -->
    @if(count($tickets) > 0)
    <div class="border-top bg-white card-footer text-muted">
        <button
        class="btn btn-sm btn-outline-secondary delete-all"
        data-url=""><i class="fa fa-trash mr-1" aria-hidden="true"></i>Delete</button>
    </div>
    @endif
</div>
</div>
</div>
</div>
</section>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@include('ticketing.message')

{{-- delete all --}}
<script type="text/javascript">
    $(document).ready(function () {
        $('#check_all').on('click', function(e) {
            if($(this).is(':checked',true))
            {
                $(".checkbox").prop('checked', true);

            } else {
                $(".checkbox").prop('checked',false);

            }
        });

        $('.checkbox').on('click',function(){

            if($('.checkbox:checked').length == $('.checkbox').length){

                $('#check_all').prop('checked',true);

            }else{
                $('#check_all').prop('checked',false);

            }

        });
        $('.delete-all').on('click', function(e) {
            var idsArr = [];
            $(".checkbox:checked").each(function() {
                idsArr.push($(this).attr('data-id'));
            });
            if(idsArr.length <=0)
            {
                alert("Please select atleast one record to delete.");

            }else {
                if(confirm("Are you sure, you want to delete the selected record?")){

                    var strIds = idsArr.join(",");
                    $.ajax({
                        url: "{{ route('delete-all-tickets.tickets') }}",
                        type: 'GET',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+strIds,
                        success: function (data) {
                            if (data['status']==true) {
                                $(".checkbox:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });

                                alert(data['message']);

                            } else {

                                alert('Whoops Something went wrong!!');

                            }

                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                }
            }
        });
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.closest('form').submit();
            }
        });
    });
</script>
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

@endsection
