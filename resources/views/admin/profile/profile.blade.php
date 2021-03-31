@extends('layouts.adminlte-boot-4.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Admin Profile</h1>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content mt-2 mx-0">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary">
                            <div class="card-body card-profile">
                                <img class="profile-user-img img-responsive img-circle"
                                    src="{{ $data['userdata']->profileUrl() }}"
                                    alt="{{ $data['userdata']['name']}}">

                                <h3 class="profile-username text-center">{!! $data['userdata']['name'] !!}</h3>

                                <p class="text-muted text-center">Administrator</p>

                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>Email</b> <a class="pull-right">{!! $data['userdata']['email'] !!}</a>
                                    </li>
                                </ul>

                                <a href="{{ url('admin/updateprofile') }}" class="btn btn-primary btn-block"><b>Edit</b></a>
                            </div>
                        </div>

                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#activity" data-toggle="tab">Reports</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="timeline">

                                </div>
                                <div class="tab-pane" id="settings">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary">
                            <div class="card-body card-profile">
                                <img class="profile-user-img img-responsive img-circle"
                                    src="<?php echo $data['app_details']['app_picture'] != null ? url($data['app_details']['app_picture']) : '#'; ?>"
                                    alt="User profile picture">

                                <h3 class="profile-username text-center">{!! $data['app_details']['app_name'] !!}</h3>

                                <!-- <p class="text-muted text-center">Administrator</p> -->

                                <a href="{{ url('admin/app/edit/' . $data['app_details']['app_id']) }}"
                                    class="btn btn-primary btn-block"><b>Edit</b></a>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                </div>
                <!-- /.row -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script>
        $(function() {
            $(".sidebar-menu li").removeClass("active");
        });

    </script>
@endsection
