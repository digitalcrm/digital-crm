@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Task Show</h1>
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
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header no-border text-center">
                        <h3 class="card-title">Task List</h3>
                        </div>
                        <div class="card-body card-profile p-0">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Title</b> <a class="pull-right">{{ $taskmanagement->title }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Start Date</b> <a class="pull-right">{{ $taskmanagement->started_at->format('d-m-Y') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Due Date</b> <a class="pull-right">{{ $taskmanagement->due_time->format('d-m-Y') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Completed Date</b> <a class="pull-right">{{ Carbon\Carbon::parse($taskmanagement->completed_at)->format('d-m-Y') ?? 'Not Completed Yet' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Description</b> <a class="pull-right">{{ $taskmanagement->description }}</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-9">


                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Related To &nbsp;<small class="badge badge-secondary"></small>
                                <a class="float-right">
                                    @if( !empty($taskmanagement->todoable->picture) )

                                        <img
                                        class="rounded-circle img-responsive" width="50px" height="50px"
                                        src="{{asset($taskmanagement->todoable->picture)}}"
                                        alt="message user image"
                                        />

                                        @else
                                        <img
                                        class="rounded-circle img-responsive" width="50px" height="50px"
                                        src="{{asset('defaultavatar.jpeg')}}"
                                        alt="message user image"
                                        />
                                    @endif
                                </a>
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Lead Name</b> <a class="pull-right">{{ (!empty($taskmanagement->todoable)) ? $taskmanagement->todoable->first_name : 'Not assigned' }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white pull-right text-right">
                            <a href="{{ route('taskmanagement.index') }}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        </div>
                    </div>

                    <!-- /.card -->

                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
