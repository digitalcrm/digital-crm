@extends('layouts.adminlte-boot-4.user')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Tickets <small class="badge badge-secondary"></small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                                class="btn btn-sm  btn-primary mr-1 px-3"
                                href="{{ route('tickets.create') }}">
                                <i class="far fa-plus-square mr-1"></i>New Ticket
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">

        <div class="row">
            <div class="col-md-7">
                <div class="card-shadow">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">

                            <ul class="navbar-nav mr-auto">

                                <li class="nav-item active">
                                    <a class="nav-link" href="{{ route('tickets.index') }}">
                                        All Ticket<span class="sr-only">(current)</span>
                                    </a>
                                </li>
                                {{-- @if($ticket->status_id != 3)
                                <li class="nav-item">
                                    <a
                                    class="nav-link"
                                    href=""
                                    name="closedtheticket"
                                    onclick="event.preventDefault();
                                    if(confirm('Are you sure!')){
                                        $('#form-closed-{{$ticket->id}}').submit();
                                    }
                                    ">
                                    Closed Ticket
                                </a>
                                <form
                                    style="display:none"
                                    method="post"
                                    id="form-closed-{{$ticket->id}}"
                                    action="{{route('comment.update',$ticket->id)}}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                </form>
                                </li>
                                @endif --}}
                            </ul>
                        </div>
                    </nav>
                </div>

                <div class="card-header">
                    <h3 class="font-weight-bold">{{ $ticket->name }}</h3>
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                    <div>Ticket Number:</div>
                                    <div>Ticket Type:</div>
                                    <div>Ticket Priority:</div>
                                    <div>Ticket Owner:</div>
                                </div>
                                <div class="col-6">
                                    <div><span class="">{{ $ticket->ticket_number }}</span></div>
                                    <div>{{ $ticket->ticketType->name ?? 'Null' }}</div>
                                    <div>
                                        @if ($ticket->priority_id == 1)
                                        <span class='dot dot-sm dot-success'></span>{{ $ticket->priority->name ?? 'null' }}
                                        @elseif($ticket->priority_id == 2)
                                        <span class='dot dot-sm dot-warning'></span>{{ $ticket->priority->name ?? 'null' }}
                                        @elseif($ticket->priority_id == 3)
                                        <span class='dot dot-sm dot-danger'></span>{{ $ticket->priority->name ?? 'null' }}
                                        @endif
                                    </div>
                                    <div>{{ $ticket->users->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="row">
                                <div class="col-6 msg-lft">
                                    <div>Product:</div>
                                    <div>Ticket Status:</div>
                                    <div>Check Date:</div>
                                    <div>Contact Name:</div>
                                </div>
                                <div class="col-6">
                                    <div>{{ $ticket->tbl_products->name ?? 'Not selected' }}</div>
                                    <div>{{ $ticket->ticketStatus->name ?? 'Null' }}</div>
                                    <div>{{ Carbon\Carbon::parse($ticket->start_date)->format('d-m-Y') }}</div>
                                    <div>{{ $ticket->tbl_contacts->fullname() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!--card-header-->

                <div class="card-body pb-0">
                    <!--conversation message box start -->

                    <div class="row mb-2">
                        @foreach($ticket->comments as $comment)
                        <div class="col-md-8 ml-auto">
                            <div
                                class="card"
                                style="margin-bottom: 1px !important;border-radius: 5px 20px 5px;background-color: #e5f5f8;">
                                <div class="card-body bg--info-light py-3">
                                    <div>{{$comment->message}}</div>
                                    <div>{{$comment->user->name ?? 'not found user'}}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!--conversation message box ENd -->
            {{-- Message type box start --}}
            <form action="{{ route('comment.update', $ticket->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
                        <div class="chat-body mt-3"> <!--chat body start -->
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    @error('message')
                                        <small class="text-danger">write some details</small>
                                    @enderror
                                    <div class="form-group">
                                    <textarea
                                    name="message"
                                    id="editor"
                                    rows="5"
                                    cols="60"
                                    class="@error('message') 'is-invalid' @enderror"></textarea>
                                    </div>
                                </div>
                        </div> <!--chat body End -->
                </div> <!-- card-body-end -->
                        <div class="card-footer border-top text-right">
                            <div class="btn-group">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                         <select id="action" name="action" class="form-control">
                                            <option value='send'>Send</option>
                                            <option value='close'>Closed</option>
                                        </select>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-primary px-3">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
            </form>
                    {{--Message Type box End  --}}
            </div> <!-- end col-md-7-->
        </div> <!--End row-->

    </section>

</div>

@endsection
