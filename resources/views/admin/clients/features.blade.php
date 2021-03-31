@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-10">
                <h1>
                    Edit Profile
                    <!-- <small>Control panel</small> -->
                </h1>
            </div>
            <!-- <div class="col-md-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="breadcrumb-item active">Create Web to Lead</li>
                </ol>
            </div> -->
        </div>
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12">
                    @if(session('success'))
                    <div class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Features
                            </h3>
                        </div>
                        {{Form::open(['action'=>['Admin\UserController@updateFeatures',$rdata['uid']],'method'=>'Post','enctype'=>'multipart/form-data','class'=>'form-horizontal'])}}
                        @csrf
                        <div class="card-body">
                            <?php
                            $data = $rdata['features'];
                            $webtolead = (($data != '') && ($data->webtolead == 1)) ? 'checked' : '';
                            $accounts = (($data != '') && ($data->accounts == 1)) ? 'checked' : '';
                            $contacts = (($data != '') && ($data->contacts == 1)) ? 'checked' : '';
                            $leads = (($data != '') && ($data->leads == 1)) ? 'checked' : '';
                            $deals = (($data != '') && ($data->deals == 1)) ? 'checked' : '';
                            $customers = (($data != '') && ($data->customers == 1)) ? 'checked' : '';
                            $sales = (($data != '') && ($data->sales == 1)) ? 'checked' : '';
                            $orders = (($data != '') && ($data->orders == 1)) ? 'checked' : '';
                            $forecasts = (($data != '') && ($data->forecasts == 1)) ? 'checked' : '';
                            $territory = (($data != '') && ($data->territory == 1)) ? 'checked' : '';
                            $products = (($data != '') && ($data->products == 1)) ? 'checked' : '';
                            $documents = (($data != '') && ($data->documents == 1)) ? 'checked' : '';
                            $invoices = (($data != '') && ($data->invoices == 1)) ? 'checked' : '';
                            $projects = (($data != '') && ($data->projects == 1)) ? 'checked' : '';
                            $rds = (($data != '') && ($data->rds == 1)) ? 'checked' : '';
                            $webmails = (($data != '') && ($data->webmails == 1)) ? 'checked' : '';
                            $mails = (($data != '') && ($data->mails == 1)) ? 'checked' : '';
                            $tasks = (($data != '') && ($data->tasks == 1)) ? 'checked' : '';
                            $settings = (($data != '') && ($data->settings == 1)) ? 'checked' : '';
                            $reports = (($data != '') && ($data->reports == 1)) ? 'checked' : '';

                            ?>
                            <table class='table table-striped table-bordered'>
                                <tr>
                                    <td><label><input type='checkbox' name='webtolead' id='webtolead' <?php echo $webtolead; ?>>&nbsp;Web to Lead</label></td>
                                    <td><label><input type='checkbox' name='accounts' id='accounts' <?php echo $accounts; ?>>&nbsp;Accounts</label></td>
                                    <td><label><input type='checkbox' name='contacts' id='contacts' <?php echo $contacts; ?>>&nbsp;Contacts</label></td>
                                    <td><label><input type='checkbox' name='leads' id='leads' <?php echo $leads; ?>>&nbsp;Leads</label></td>
                                </tr>
                                <tr>
                                    <td><label><input type='checkbox' name='deals' id='deals' <?php echo $deals; ?>>&nbsp;Deals</label></td>
                                    <td><label><input type='checkbox' name='customers' id='customers' <?php echo $customers; ?>>&nbsp;Customers</label></td>
                                    <td><label><input type='checkbox' name='sales' id='sales' <?php echo $sales; ?>>&nbsp;Sales</label></td>
                                    <td><label><input type='checkbox' name='orders' id='orders' <?php echo $orders; ?>>&nbsp;Orders</label></td>
                                </tr>
                                <tr>
                                    <td><label><input type='checkbox' name='forecasts' id='forecasts' <?php echo $forecasts; ?>>&nbsp;Forecasts</label></td>
                                    <td><label><input type='checkbox' name='territory' id='territory' <?php echo $territory; ?>>&nbsp;Territory</label></td>
                                    <td><label><input type='checkbox' name='products' id='products' <?php echo $products; ?>>&nbsp;Products</label></td>
                                    <td><label><input type='checkbox' name='documents' id='documents' <?php echo $documents; ?>>&nbsp;Documents</label></td>
                                </tr>
                                <tr>
                                    <td><label><input type='checkbox' name='invoices' id='invoices' <?php echo $invoices; ?>>&nbsp;Invoices</label></td>
                                    <td><label><input type='checkbox' name='projects' id='projects' <?php echo $projects; ?>>&nbsp;Projects</label></td>
                                    <td><label><input type='checkbox' name='rds' id='rds' <?php echo $rds; ?>>&nbsp;RD's</label></td>
                                    <td><label><input type='checkbox' name='webmails' id='webmails' <?php echo $webmails; ?>>&nbsp;Web Mails</label></td>
                                </tr>

                                <tr>
                                    <td><label><input type='checkbox' name='mails' id='mails' <?php echo $mails; ?>>&nbsp;Mails</label></td>
                                    <td><label><input type='checkbox' name='tasks' id='tasks' <?php echo $tasks; ?>>&nbsp;Tasks</label></td>
                                    <td><label><input type='checkbox' name='settings' id='settings' <?php echo $settings; ?>>&nbsp;Settings</label></td>
                                    <td><label><input type='checkbox' name='reports' id='reports' <?php echo $reports; ?>>&nbsp;Reports</label></td>
                                </tr>

                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <a href="{{url('admin/users')}}" class="btn btn-info float-left">Back</a>
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('Save',['class'=>"btn btn-primary float-right"])}}
                        </div>
                        <!-- /.card-footer -->
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">


                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">


                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".active").removeClass("active");
        // $("#ulwebtolead").addClass('menu-open');
        // $("#ulwebtolead ul").css('display', 'block');
        // $("#licreateform").addClass("active");
    });
</script>
@endsection