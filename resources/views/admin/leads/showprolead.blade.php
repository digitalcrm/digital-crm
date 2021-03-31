@extends('layouts.adminlte-boot-4.admin')
<?php
// echo json_encode($metadata);
// exit();
//  $metadata['og_title']
$picUrl = ($data['product']->picture != '') ? url($data['product']->picture) : url('/uploads/default/products.jpg');
?>
@section('content')

<style id="compiled-css" type="text/css">
    #carousel-custom {
        /* margin: 20px auto; */
        width: 400px;
    }

    #carousel-custom .carousel-indicators {
        margin: 10px 0 0;
        overflow: auto;
        position: static;
        text-align: left;
        white-space: nowrap;
        width: 100%;
    }

    #carousel-custom .carousel-indicators li {
        background-color: transparent;
        -webkit-border-radius: 0;
        border-radius: 0;
        display: inline-block;
        height: auto;
        margin: 0 !important;
        width: auto;
    }

    #carousel-custom .carousel-indicators li img {
        display: block;
        opacity: 0.5;
    }

    #carousel-custom .carousel-indicators li.active img {
        opacity: 1;
    }

    #carousel-custom .carousel-indicators li:hover img {
        opacity: 0.75;
    }

    #carousel-custom .carousel-outer {
        position: relative;
    }

    /* EOS */
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="">
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

        @if(session('info'))
        <div class='alert alert-warning'>
            {{session('info')}}
        </div>
        @endif
    </section>
    <!-- /.container-fluid -->
    <!-- Main content -->
    <section class="content mt-5 mx-0">
        <div class="container-fluid">
            <div class="row">

                <section class="col-lg-6">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header no-border text-center">
                            <img class="border img-responsive img-circle" src="<?php echo ($data['leadarr']['picture'] != "") ? url($data['leadarr']['picture']) : url('/uploads/default/leads.png'); ?>" height="85" width="85" alt="User profile picture" />
                            <h3 class="profile-username text-center">{!!($data['leadarr']['tbl_salutations']!='')?$data['leadarr']['tbl_salutations']['salutation'].' ':''!!}&nbsp;{!!$data['leadarr']['first_name']!!}&nbsp;{!!$data['leadarr']['last_name']!!}</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>First Name</b>
                                    <a class="pull-right">{!!$data['leadarr']['first_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Last Name</b>
                                    <a class="pull-right">{!!$data['leadarr']['last_name']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b>
                                    <a class="pull-right">{!!$data['leadarr']['email']!!}</a>
                                </li>

                                <li class="list-group-item">
                                    <b>Mobile</b>
                                    <a class="pull-right"><?php echo ($data['leadarr']['mobile'] != null) ? $data['leadarr']['mobile'] : ''; ?></a>
                                </li>

                                <li class="list-group-item">
                                    <b>Product</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_products'] != null) ? $data['leadarr']['tbl_products']['name'] : ''!!}</a>
                                </li>

                                <li class="list-group-item">
                                    <b>Notes</b>
                                    <a class="pull-right">{!!($data['leadarr']['notes'] != null) ? $data['leadarr']['notes'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Website</b>
                                    <a class="pull-right">{!!($data['leadarr']['website'] != null) ? $data['leadarr']['website'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Designation</b>
                                    <a class="pull-right">{!!$data['leadarr']['designation']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Phone</b>
                                    <a class="pull-right">{!!$data['leadarr']['phone']!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Source</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_leadsource'] != null) ? $data['leadarr']['tbl_leadsource']['leadsource'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Lead Status</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_leadstatus'] != null) ? $data['leadarr']['tbl_leadstatus']['status'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Industry Type</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl_industrytypes'] != null) ? $data['leadarr']['tbl_industrytypes']['type'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Account</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl__accounts'] != null) ? $data['leadarr']['tbl__accounts']['name'] : ''!!}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Company</b>
                                    <a class="pull-right">{!!($data['leadarr']['tbl__accounts'] != null) ? $data['leadarr']['tbl__accounts']['company'] : ''!!}</a>
                                </li>
                            </ul>
                            <a href="{{url('admin/leads/product/edit/'.$data['leadarr']['ld_id'])}}" class="btn btn-primary btn-block"><b>Edit</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- About Me card -->
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Address</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                            <p class="text-muted">
                                <?php echo ($data['leadarr']['street'] != null) ? $data['leadarr']['street'] : ''; ?>
                                <?php echo ($data['leadarr']['city'] != null) ? ', ' . $data['leadarr']['city'] : ''; ?>
                                <?php echo ($data['leadarr']['tbl_states'] != null) ? ', ' . $data['leadarr']['tbl_states']['name'] : ''; ?>
                                <?php echo ($data['leadarr']['tbl_countries'] != null) ? ', ' . $data['leadarr']['tbl_countries']['name'] : ''; ?>
                                <?php echo ($data['leadarr']['zip'] != null) ? ', ' . $data['leadarr']['zip'] : ''; ?>
                            </p>

                        </div>
                        <!-- /.card-body -->
                    </div>
                </section>
                <section class="col-lg-6">
                    <div class="carde card-widgete">
                        <div class="card-header">
                            <h3>{{$data['product']->name}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <img class="img-fluid pad" src="{{url($picUrl)}}" alt="Photo" width="100" height="100">
                            <div class="card-body">

                                <h6>{{$data['product']->vendor}}</h6>
                                <h2><span>{!!$data['user']['currency']['html_code']!!}</span>{{$data['product']->price}}</h2>

                                <!-- <div class="badge badge-success mr-4">3.8 <i class="fa fa-star" aria-hidden="true"></i></div> -->

                                <span class="text-muted">Size</span>
                                <span class="font-weight-bold mr-4">
                                    {{$data['product']->size.' '.(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->sortname:'')}}
                                </span>

                                <span class="text-muted">Units</span>
                                <span class="font-weight-bold">
                                    {{(($data['product']['tbl_units']!='')?$data['product']['tbl_units']->name:'')}}
                                </span>

                                <h5 class="mt-4">Product Details</h5>
                                <p>{{strip_tags($data['product']->description)}}</span>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <!-- <div class="card-footer"></div> -->
                    </div>
                </section>
            </div>
        </div>
        <!-- /.row (main row) -->
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection