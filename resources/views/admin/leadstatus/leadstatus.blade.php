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
            <div class="col-md-10">                
                <h1>
                    Lead Status
                <small class="badge badge-secondary" id="total">{{$data['total']}}</small>
                </h1>
            </div>
            <div class="col-md-2 text-right pull-right">
                <a class="btn btn-primary px-3" href="{{url('admin/leadstatus/create')}}"><i class="far fa-plus-square mr-1"></i> New Lead Status</a>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-6">
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
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <div class="card-body p-0" id="table">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                        <a href="<?php echo url('admin/settings'); ?>" class="btn btn-outline-secondary">Back</a>
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

<script>
    var url = "{{url('ajax/getUserleads')}}";
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#lileadstatus").addClass("active");
        $("#lileadstatus").parent('#ulleadstatus ul').css('display', 'block').addClass("menu-open");
        $("#ulleadstatus").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");

        $("#selectUser").change(function() {
            var uid = $(this).val();
            // alert(uid);
            var ajaxUrl = url;
            // alert(ajaxUrl);
            $.get(ajaxUrl, {'uid': uid}, function(result) {
                // alert(result);

                var res = eval("(" + result + ")");
                // alert(res.total);
                $("#total").text(res.total);
                // alert(res.table);
                $("#table").html(res.table);
                $('#example1').DataTable()
                $('#example2').DataTable({
                    'paging': true,
                    'lengthChange': false,
                    'searching': false,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false
                });
            });
        });


    });



// function previewForm(id){
//   $('#myModal3').modal('hide'); 
//   var ajaxUrl = url;
//   $.get( ajaxUrl, {'form_id':id,'type':'preview'},function(result) {
//     // $("#resulttt").html(result);
//     $("#tab_1").html(result);
//     $('#myModal2').modal('show'); 
//   });
// }

</script>
@endsection