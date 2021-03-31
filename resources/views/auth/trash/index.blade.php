@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Trash</h1>
                </div>
                        <!--<a class="btn btn-danger" href="{{url('mails/create')}}"><i class="fas fa-plus"></i> Create</a>-->
      <!--                            <a class="btn btn-danger" href="#"><i class="fas fa-upload"></i> Import</a>
                                  <a class="btn btn-danger" href="#"><i class="fas fa-download"></i> Export</a>-->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->

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

            <div class="row text-center">
                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/forms'); ?>" class="trash-link">
                                <i class="fa fa-file fa-2x"></i>
                                <div class="text-bold mt-2">
                                    Web to Lead - Forms
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>        

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/formleads'); ?>" class="trash-link">
                                <span class=""><i class="fa fa-file-alt fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Web to Lead
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>              

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/leads'); ?>" class="trash-link">
                                <span class=""><i class="fa fa-paper-plane fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Leads
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                
                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/productleads'); ?>" class="trash-link">
                                <span class=""><i class="fa fa-paper-plane fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Product Leads
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/accounts'); ?>" class="trash-link">
                                <span class=""><i class="fa fa-user fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Accounts
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>  

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/contacts'); ?>" class="trash-link">
                                <span class=""><i class="fa fa-phone fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Contacts
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>  

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/deals'); ?>" class="trash-link">
                                <span class=""><i class="fa fa-briefcase fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Deals
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>  

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/products'); ?>" class="trash-link">
                                <span class=""><i class="fab fa-product-hunt fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Products
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>  

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/documents'); ?>" class="trash-link">
                                <span class=""><i class="fa fa-file-word fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Documents
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>  

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/invoices'); ?>" class="trash-link">
                                <span class=""><i class="fas fa-money-bill fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Invoices
                                </div>
                            </a>

                            @can('isUser')
                        </div>
                        <!-- /.card -->
                    </div>
                </div>  

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/territory'); ?>" class="trash-link">
                                <span class=""><i class="fas fa-map-marked fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Territory
                                </div>
                            </a>

                            @endcan
                        </div>
                        <!-- /.card -->
                    </div>
                </div>  

                <div class="col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header--> 
                        <div class="card-body">
                            <a href="<?php echo url('trash/events'); ?>" class="trash-link">
                                <span class=""><i class="fas fa-calendar-alt fa-2x"></i></span>
                                <div class="text-bold mt-2">
                                    Events</a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>  

            <!-- /.card -->
            <div id="resulttt">

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
        $("#litrash").addClass("active");
    });



</script>
@endsection