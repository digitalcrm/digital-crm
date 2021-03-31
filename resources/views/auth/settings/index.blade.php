@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Settings</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Content Header (Page header) -->

            <!-- Small cardes (Stat card) -->
            <div class="row text-center">


                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header-->
                        <div class="card-body">
                            <a href="<?php echo url('/tax'); ?>" class="">
                                <span class=""><i class="fa fa-money-bill fa-2x"></i></span>
                                <div class="mt-2">
                                    Tax
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header-->
                        <div class="card-body">
                            <a href="<?php echo url('/mailtemplates'); ?>" class="">
                                <span class=""><i class="fa fa-mail-bulk fa-2x"></i></span>
                                <div class="mt-2">
                                    Email Templates
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header-->
                        <div class="card-body">
                            <a href="<?php echo url('/trash'); ?>" class="">
                                <span class=""><i class="fa fa-mail-bulk fa-2x"></i></span>
                                <div class="mt-2">
                                    Trash
                                </div>
                            </a>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-body">
                            <a href="<?php echo url('/user/currency/' . Auth::user()->id); ?>" class="trash-link">
                                <span class=""><i class="fas fa-money-bill-alt fa-2x"></i></span>
                                <div class="mt-2">
                                    Currency
                                </div>
                            </a>
                        </div>
                    </div>
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
        $("#lisettings").addClass("active");

    });
</script>
@endsection