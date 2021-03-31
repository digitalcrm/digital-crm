@extends('layouts.bootstrap4.user')
@section('content')
<!--Page Content--> 
<div class="container-fluid">
    <div class="row py-4">
        <div class="col-lg-3 text-center">
            <div class="card">

                <div class="card-body">

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
                            <b>Country</b> <a class="pull-right"><?php echo ($data['userdata']['tbl_countries']['name'] != '') ? $data['userdata']['tbl_countries']['name'] : '' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>State</b> <a class="pull-right"><?php echo ($data['userdata']['tbl_states']['name'] != '') ? $data['userdata']['tbl_states']['name'] : '' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>City</b> <a class="pull-right"><?php echo ($data['userdata']['city'] != '') ? $data['userdata']['city'] : '' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Zip</b> <a class="pull-right"><?php echo ($data['userdata']['zip'] != '') ? $data['userdata']['zip'] : '' ?></a>
                        </li>
                    </ul>

                    <a href="{{url('/profile/update')}}" class="btn btn-primary btn-block"><b>Edit</b></a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 text-center">
            <div class="card">

                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Reports</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Sub Users</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">SMTP Settings</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <table id="example" class="table table-striped">
                                <tbody>
                                    <?php
                                    $global_reports = $data['global_reports'];
                                    ?>

                                    <tr><td>Sub Users</td><td><?php echo $global_reports['subusers'] ?></td></tr>
                                    <tr><td>Accounts</td><td><?php echo $global_reports['accounts'] ?></td></tr>
                                    <tr><td>Contacts</td><td><?php echo $global_reports['contacts'] ?></td></tr>
                                    <tr><td>Leads</td><td><?php echo $global_reports['leads'] ?></td></tr>
                                    <tr><td>Deals</td><td><?php echo $global_reports['deals'] ?></td></tr>
                                    <tr><td>Won</td><td><?php echo $global_reports['won'] ?></td></tr>
                                    <tr><td>Lost</td><td><?php echo $global_reports['lost'] ?></td></tr>
                                    <tr><td>Customers</td><td><?php echo $global_reports['customers'] ?></td></tr>
                                    <tr><td>Sales</td><td><?php echo $global_reports['sales'] ?></td></tr>
                                    <tr><td>Forms</td><td><?php echo $global_reports['forms'] ?></td></tr>
                                    <tr><td>Form Leads</td><td><?php echo $global_reports['formleads'] ?></td></tr>
                                    <tr><td>Events</td><td><?php echo $global_reports['events'] ?></td></tr>
                                    <tr><td>Territory</td><td><?php echo $global_reports['territory'] ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <?php
                            echo $data['subusers']['table'];
                            ?>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <?php
                            $smtpsettings = $data['smtpsettings'];

                            if ($smtpsettings == null) {
                                ?>
                                Please add Smtp details &nbsp;<a href='{{url('smtp/create')}}' class="btn btn-danger">Add</a>
                                <?php
                            } else {
                                ?>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="username" class="col-sm-3 control-label">Username</label>
                                        <div class="col-sm-7">
                                            <label><?php echo $smtpsettings['username'] ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-7">
                                            <label><?php echo $smtpsettings['password'] ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="incomingserver" class="col-sm-3 control-label">Incoming Server</label>
                                        <div class="col-sm-7">
                                            <label><?php echo $smtpsettings['incomingserver'] ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="incomingport" class="col-sm-3 control-label">Incoming Server Port <small><b>(IMAP PORT)</b></small></label>
                                        <div class="col-sm-7">
                                            <label><?php echo $smtpsettings['incomingport'] ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="outgoingserver" class="col-sm-3 control-label">Outgoing Server</label>
                                        <div class="col-sm-7">
                                            <label><?php echo $smtpsettings['outgoingserver'] ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="outgoingport" class="col-sm-3 control-label">Outgoing Server Port <small><b>(SMTP PORT)</b></small></label>
                                        <div class="col-sm-7">
                                            <label><?php echo $smtpsettings['outgoingport'] ?></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <a href='{{url('smtp/'. $smtpsettings['ss_id'].'/edit/' )}}' class="btn btn-danger">Edit</a>
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection