@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Customer Profile</h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('customers/editcustomer/'.$data['deals']['deal_id'])}}"><i class="fas fa-pencil"></i> Edit</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content mt-2 mx-0">
        <div class="container-fluid">
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
                <div class="col-4">
                    <!-- Profile Image -->
                    <div class="card shadow">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Lead
                            </h3>
                        </div>
                        <div class="card-body p-0 card-profile text-center pt-2">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo ($data['leadarr']['picture'] != "") ? url($data['leadarr']['picture']) : url('/uploads/default/leads.png'); ?>" alt="User profile picture">

                            <h3 class="profile-username text-center">{!!($data['leadarr']['tbl_salutations']!='')?$data['leadarr']['tbl_salutations']['salutation'].' ':''!!}&nbsp;{!!$data['leadarr']['first_name']!!}&nbsp;{!!$data['leadarr']['last_name']!!}</h3>

                            <!--<p class="text-muted text-center">Software Engineer</p>-->

                            <ul class="list-group list-group-unbordered text-left">
                                <li class="list-group-item">
                                    <b>First Name</b> <a class="pull-right">{!!$data['leadarr']['first_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Last Name</b> <a class="pull-right">{!!$data['leadarr']['last_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="pull-right">{!!$data['leadarr']['email']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile</b> <a class="pull-right">{!!$data['leadarr']['mobile']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="pull-right">{!!$data['leadarr']['phone']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b> <a class="pull-right">{!!($data['leadarr']['tbl_leadsource'] != null) ? $data['leadarr']['tbl_leadsource']['leadsource'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Status</b> <a class="pull-right">{!!($data['leadarr']['tbl_leadstatus'] != null) ? $data['leadarr']['tbl_leadstatus']['status'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Industry Type</b> <a class="pull-right">{!!($data['leadarr']['tbl_industrytypes'] != null) ? $data['leadarr']['tbl_industrytypes']['type'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Account</b> <a class="pull-right">{!!($data['leadarr']['tbl__accounts'] != null) ? $data['leadarr']['tbl__accounts']['name'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Website</b> <a class="pull-right">{!!($data['leadarr']['website'] != null) ? $data['leadarr']['website'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Notes</b> <a class="pull-right">{!!($data['leadarr']['notes'] != null) ? $data['leadarr']['notes'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Location</b> <a class="pull-right">
                                        <p class="text-muted"><?php echo ($data['leadarr']['street'] != null) ? $data['leadarr']['street'] : ''; ?>
                                            <?php echo ($data['leadarr']['city'] != null) ? ', ' . $data['leadarr']['city'] : ''; ?>
                                            <?php echo ($data['leadarr']['tbl_states'] != null) ? ', ' . $data['leadarr']['tbl_states']['name'] : ''; ?>
                                            <?php echo ($data['leadarr']['tbl_countries'] != null) ? ', ' . $data['leadarr']['tbl_countries']['name'] : ''; ?>
                                            <?php echo ($data['leadarr']['zip'] != null) ? ', ' . $data['leadarr']['zip'] : ''; ?></p>
                                    </a>
                                </li>
                            </ul>

                            <!--<a href="{!!$data['editLink']!!}" class="btn btn-primary btn-block"><b>Edit</b></a>-->
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-4">
                    <div class="card shadow">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                Deal
                            </h3>
                        </div>

                        <div class="card-body p-0 card-profile">

                            <h3 class="profile-username text-center"><?php echo $data['deals']['name']; ?></h3>

                            <!--<p class="text-muted text-center">Software Engineer</p>-->

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Deal Name</b> <a class="pull-right"><?php echo $data['deals']['name']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Deal Stage</b> <a class="pull-right"><?php echo $data['deals']['tbl_salesfunnel']['salesfunnel']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b> <a class="pull-right"><?php echo (isset($data['deals']['tbl_leadsource'])) ? $data['deals']['tbl_leadsource']['leadsource'] : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Amount</b> <a class="pull-right"><?php echo $data['deals']['value']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Closing Date</b> <a class="pull-right"><?php echo date('d-m-Y', strtotime($data['deals']['closing_date'])); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Probability</b> <a class="pull-right"><?php echo ($data['deals']['probability'] > 0) ? $data['deals']['probability'] . ' %' : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Notes</b> <a class="pull-right"><?php echo $data['deals']['notes']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Loss Reason</b> <a class="pull-right"><?php echo ($data['deals']['tbl_lossreasons'] != '') ? $data['deals']['tbl_lossreasons']['reason'] : ''; ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="col-4">
                    <div class="card shadow">
                        <div class="card-header with-border">
                            <h3 class="card-title">Product</h3>
                        </div>

                        <div class="card-body p-0 card-profile text-center">
                            <img class="profile-user-img img-responsive img-circle" src="<?php echo (($data['product'] != '') && ($data['product']->picture != '')) ? url($data['product']->picture) : url('/uploads/default/products.jpg'); ?>" alt="product" height="25" width="25">
                            <h3 class="profile-username text-center"><?php echo ($data['product'] != null) ? $data['product']->name : ''; ?></h3>
                            <ul class="list-group list-group-unbordered text-left pt-2">
                                <li class="list-group-item">
                                    <b>Product Name</b> <a class="pull-right"><?php echo ($data['product'] != null) ? $data['product']->name : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Price</b> <a class="pull-right">({!!$data['user']['currency']['html_code']!!})<?php echo ($data['product'] != null) ? $data['product']->price : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Units</b> <a class="pull-right"><?php echo ($data['unit'] != null) ? $data['unit']->name : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Description</b> <a class="pull-right"><?php echo ($data['product'] != null) ? $data['product']->description : ''; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Size</b> <a class="pull-right"><?php echo ($data['product'] != null) ? $data['product']->size : ''; ?><?php echo ($data['unit'] != null) ? $data['unit']->sortname : ''; ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#licustomers").addClass("active");
    });
</script>
@endsection