@extends('layouts.adminlte-boot-4.admin')

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
                            {{-- <a
                                class="btn btn-sm  btn-primary mr-1 px-3"
                                href="#">
                                <i class="far fa-plus-square mr-1"></i>New Ticket
                            </a> --}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
<div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow card-primary card-outline">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">

                            <ul class="navbar-nav mr-auto">

                                <li class="nav-item active">

                                    <a class="nav-link" href="{{ route('admin.ticket') }}">All Ticket</a>

                                </li>

                                <li class="nav-item">
                                    {{-- <a class="nav-link" href="#">Reply</a> --}}
                                </li>
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
                                        <small class='dot dot-sm dot-warning'></small>{{ $ticket->priority->name ?? 'null' }}
                                        @elseif($ticket->priority_id == 3)
                                        <span class='dot dot-sm dot-danger'></span>{{ $ticket->priority->name ?? 'null' }}
                                        @endif
                                    </div>
                                    <div>{{ $ticket->users->name ?? 'Not assigned' }}</div>
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

            </div> <!-- end col-md-7-->
        </div> <!--End row-->
</div>
    </section>

</div>

@endsection
