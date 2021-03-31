@extends('layouts.adminlte-boot-4.consumer')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Consumer Profile</h1>
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
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo ($data['userdata']['picture'] != null) ? url($data['userdata']['picture']) : '#'; ?>" alt="User profile picture">

                            <h3 class="profile-username text-center">{!!$data['userdata']['name']!!}</h3>

                            <p class="text-muted text-center">Consumer</p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Email</b> <a class="pull-right">{!!$data['userdata']['email']!!}</a>
                                </li>
                                
                                <!--                            <li class="list-group-item">
                                                            <b>Following</b> <a class="pull-right">543</a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Friends</b> <a class="pull-right">13,287</a>
                                                        </li>-->
                            </ul>
                            <!--consumers-->
                            <a href="{{url('Shop/updateprofile')}}" class="btn btn-primary btn-block"><b>Edit</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#activity" data-toggle="tab">Reports</a></li>
                            <!--                        <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
                                                <li><a href="#settings" data-toggle="tab">Settings</a></li>-->
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">



                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="timeline">

                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="settings">

                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {});
</script>
@endsection