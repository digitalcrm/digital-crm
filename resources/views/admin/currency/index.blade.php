 @extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Currencies
                        <small class="badge badge-secondary" id="total">{{$data['total']}}</small>
                    </h1>
                </div>
				<div class="col-sm-6 text-right pull-right">
					<a class="btn btn-primary px-3" href="{{url('admin/currency/create')}}"><i class="far fa-plus-square mr-1"></i> New Currency</a>
				</div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!--    
        <section class="content-header">
            <h1>
                Account Types
                <small id="total">{{$data['total']}}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Web to lead</li>
            </ol>
        </section>
    -->
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
        $("#liaccounttypes").addClass("active");
        $("#liaccounttypes").parent('#ulaccounttypes ul').css('display', 'block').addClass("menu-open");
        $("#ulaccounttypes").parent('#ulsettings ul').css('display', 'block').addClass("menu-open");

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