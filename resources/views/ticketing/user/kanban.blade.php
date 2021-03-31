@extends('layouts.adminlte-boot-4.user')

@section('content')


<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-4">
                    <h1>Tickets <small class="badge badge-secondary" id="total"></small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                            class="btn btn-sm  btn-primary mr-1 px-3"
                            href="{{ route('taskmanagement.create') }}"><i class="far fa-plus-square mr-1"></i>New Ticket
                            </a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('tickets.index') }}">TableView</a>
                        </li>
                    </ol>
                </div>
            </div>

        </div>

    </section>

    <section class="content mt-2 mx-0">
        <div class="conatiner-fluid">
            <div class="row">
                @include('taskmanagement.includes.message')
                <div class="col-lg-12">

                    <div class="card shadow card-primary card-outline">
                        <div class="card-body" id="table">

                            <div id="kanban" class="row">
                                {{-- card loop start here--}}
                                @foreach($taskstatus as $status)
                                <div class="col-3">

                                    <div class="card card-default"">

                                        <div class="card-header kanban-card">

                                            <h3 class="card-title">{{ $status->name }}</h3>

                                        </div>

                                        <div class="card-body">

                                            {{-- dynamic id will call --}}
                                            <div id="{{ Str::replaceFirst(' ','_',$status->name) }}_{{$status->id}}" class="dlstage">&nbsp;

                                                {{-- Loop start outcomes has task--}}
                                                @foreach($status->tickets as $ticket)
                                                <div id="card_{{ $ticket->id }}" class="kanban-card" role="alert">

                                                    <div class="callout callout-success">
                                                        <div class="info-box-content">
                                                            <span>
                                                                <a
                                                                    style="color: blue"
                                                                    class="card-link"
                                                                    href="{{ route('tickets.show',$ticket->ticket_number) }}">
                                                                    {{$ticket->name}}
                                                                </a>
                                                            </span><br>

                                                            <span>
                                                                {{ (!empty($ticket->contact_id)) ? $ticket->tbl_contacts->fullname() : 'Null' }}
                                                            </span><br>

                                                            <span>{{ $ticket->ticketType->name ?? 'Type Null'}}</span><br>

                                                            @if ($ticket->priority_id == 1)
                                                            <span class='dot dot-sm dot-success'></span> {{ $ticket->priority->name ?? 'Null'}}
                                                            @elseif($ticket->priority_id == 2)
                                                            <small class='dot dot-sm dot-warning'></small> {{ $ticket->priority->name ?? 'Null'}}
                                                            @elseif($ticket->priority_id == 3)
                                                            <span class='dot dot-sm dot-danger'></span> {{ $ticket->priority->name ?? 'Null'}}
                                                            @endif
                                                            <br>


                                                            <a class="btn btn-info" href="{{  route('tickets.edit',$ticket->id) }}">
                                                                <i class="far fa-edit"></i>
                                                            </a>
                                                            <a
                                                            class="btn btn-danger"
                                                            href=""
                                                            onclick="event.preventDefault();
                                                            if(confirm('Are you sure!')){
                                                                $('#form-delete-{{$ticket->id}}').submit();
                                                            }
                                                            ">
                                                            <i class="fas fa-trash-alt"></i>
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


                                                        </div>

                                                    </div>

                                                </div>
                                                @endforeach
                                                {{-- loop end --}}
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                @endforeach
                                {{-- loop end card --}}


                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
<script type="text/javascript">

    window.onload = function() {

        var stageIdsArr = [];

        $(".dlstage").each(function() {

            // alert($(this).attr('id'));

            var stId = "#" + $(this).attr('id');

            stageIdsArr.push(document.querySelector(stId));

        });

        var dragulaCards = dragula(stageIdsArr);

        dragulaCards.on('drop', function(el, target, source, sibling) {

            changeDealStage(el.id, target.id, source.id);

        });

        var dragulaKanban = dragula([

        document.querySelector('#kanban')

        ], {

            moves: function(el, container, handle) {

                return handle.classList.contains('card-title');

            }

        });

    }
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    var changedealstagedragndrop = "{{url('kanban/ticket/changedstatus/{ticket_id}/{status_id}/{from_id}')}}";
    //Drag and drop feature
    function changeDealStage(ticket_id, status_id, from_id) {

        $.get(changedealstagedragndrop, {

            'ticket_id': ticket_id,

            'status_id': status_id,

            'from_id': from_id,

        },function(result, status) {

            // alert(result);
            swal({
                title: "Updated!",
                text: "Ticket status updated!",
                icon: "success",
            });
            var res = eval("(" + result + ")");

        });

    }

</script>

@endsection
