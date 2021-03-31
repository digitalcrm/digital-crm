@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Profile </h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Main content -->
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="card shadow card-primary card-outline">
                                <div class="card-body card-profile">
                                    <img class="profile-user-img img-responsive img-circle" src="<?php echo (($data['userdata'] != null) && ($data['userdata']['picture'] != null)) ? url($data['userdata']['picture']) :  url('/uploads/default/user.png'); ?>" alt="User profile picture">

                                    <h3 class="profile-username text-center">{!!$data['userdata']['name']!!}</h3>

                                    <p class="text-muted text-center">{!!$data['userdata']['jobtitle']!!}</p>

                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Email</b> <a class="pull-right">{!!$data['userdata']['email']!!}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Mobile</b> <a class="pull-right">{!!$data['userdata']['mobile']!!}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Currenccy</b> <a class="pull-right">{!!$data['user_currency']!!}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Daily Reports</b> <a class="pull-right"><?php echo ($data['userdata']['daily_reports'] == 1) ? 'Yes' : 'No' ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Newsletter</b> <a class="pull-right"><?php echo ($data['userdata']['newsletter'] == 1) ? 'Yes' : 'No' ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Country</b> <a class="pull-right"><?php echo ($data['userdata']['country'] > 0) ? $data['userdata']['tbl_countries']['name'] : ''  ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>State</b> <a class="pull-right"><?php echo ($data['userdata']['state'] > 0) ? $data['userdata']['tbl_states']['name'] : ''  ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>City</b> <a class="pull-right"><?php echo ($data['userdata']['city'] != '') ? $data['userdata']['city'] : '' ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Zip</b> <a class="pull-right"><?php echo ($data['userdata']['zip'] != '') ? $data['userdata']['zip'] : '' ?></a>
                                        </li>
                                    </ul>
                                    <?php // echo ($data['userdata']['tbl_countries']['name'] != '') ? $data['userdata']['tbl_countries']['name'] : '' 
                                    ?>
                                    <?php // echo ($data['userdata']['tbl_states']['name'] != '') ? $data['userdata']['tbl_states']['name'] : '' 
                                    ?>
                                    <a href="{{url('/profile/update')}}" class="btn btn-primary btn-block"><b>Edit</b></a>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->


                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">

                            <div class="card shadow card-primary card-outline">
                                <div class="card-body p-0">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Reports</a>
                                            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-products" role="tab" aria-controls="nav-home" aria-selected="true">Products</a>
                                            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-companies" role="tab" aria-controls="nav-home" aria-selected="true">Companies</a>
                                            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Sub Users</a>
                                            <!-- <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">SMTP Settings</a> -->
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <div class="card-body p-0">
                                                <?php
                                                echo $data['subusers']['table'];
                                                ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                            <div class="card-body p-0">
                                                <table id="globalreports" class="table table-striped">
                                                    <tbody>
                                                        <?php
                                                        $global_reports = $data['global_reports'];
                                                        ?>

                                                        <tr>
                                                            <td>Sub Users</td>
                                                            <td><?php echo $global_reports['subusers'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Accounts</td>
                                                            <td><?php echo $global_reports['accounts'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Contacts</td>
                                                            <td><?php echo $global_reports['contacts'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Leads</td>
                                                            <td><?php echo $global_reports['leads'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Deals</td>
                                                            <td><?php echo $global_reports['deals'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Won</td>
                                                            <td><?php echo $global_reports['won'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Lost</td>
                                                            <td><?php echo $global_reports['lost'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Customers</td>
                                                            <td><?php echo $global_reports['customers'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sales</td>
                                                            <td><?php echo $global_reports['sales'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Forms</td>
                                                            <td><?php echo $global_reports['forms'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Form Leads</td>
                                                            <td><?php echo $global_reports['formleads'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Events</td>
                                                            <td><?php echo $global_reports['events'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Territory</td>
                                                            <td><?php echo $global_reports['territory'] ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <!--Products--->
                                        <div class="tab-pane fade" id="nav-products" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <div class="card-body p-0">
                                                <?php
                                                echo $data['products']['table'];
                                                ?>
                                            </div>
                                        </div>
                                        <!--Companies--->
                                        <div class="tab-pane fade" id="nav-companies" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <div class="card-body p-0">
                                                <livewire:company.all-lists />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.content -->
            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        // $("#ulwebtolead").addClass('menu-open');
        // $("#ulwebtolead ul").css('display', 'block');
        // $("#licreateform").addClass("active");
    });
</script>
@endsection