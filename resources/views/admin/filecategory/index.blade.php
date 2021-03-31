@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-md-7">                
                <h1>
                    File Category
                    <small class="badge badge-secondary" id="total">{{$data['total']}}</small>
                </h1>
            </div>
            <div class="col-md-5 text-right pull-right">
                <a class="btn btn-primary px-3" href="{{url('admin/filecategory/create')}}"><i class="far fa-plus-square mr-1"></i> New File Category</a>
            </div>
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
                <div class="col-lg-12">
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        <div class="card-body p-0" id="table">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <a href="<?php echo url('admin/settings'); ?>" class="btn btn-outline-secondary float-right">Back</a>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
                <div id="resulttt">

                </div>
            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--Preview Modal -->
<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Preview</h4>
            </div>

            <div class="modal-body">
                <div id="tab_1">
                    This is preview
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!-- modal -->

<!--Embed Code Modal -->
<div class="modal right fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Embed Code</h4>
            </div>

            <div class="modal-body">
                <p id="tab_2">         
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                <button type="button" data-clipboard-target="#tab_2"  id="copy-btn" class="btn btn-primary copy-text">Copy</button>
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!-- modal -->

<script>

    $(function() {
//        alert('hi');
        $(".sidebar-menu li").removeClass("active");
        $("#lileadsource").addClass("active");
        $("#lileadsource").parent('#ulleadsource ul').css('display', 'block').addClass("menu-open");
        $("#ulleadsource").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");



    });



</script>
@endsection