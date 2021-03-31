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
                                Permissions<br>
                                <label><input type="checkbox" id="select_all" /> Select all</label>
                            </h3>
                        </div>
                        {{Form::open(['action'=>['Admin\AdminController@storePermission',$data['userdata']['id']],'method'=>'Post','enctype'=>'multipart/form-data','class'=>'form-horizontal'])}}
                        @csrf
                        <div class="card-body">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <a href="{{url('admin/admins')}}" class="btn btn-info float-left">Back</a>
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

        $('#select_all').on('click', function() {
            if (this.checked) {
                $('.permissions').each(function() {
                    this.checked = true;
                });
            } else {
                $('.permissions').each(function() {
                    this.checked = false;
                });
            }
        });

        $('.permissions').on('click', function() {
            if ($('.permissions:checked').length == $('.permissions').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });
    });
</script>
@endsection