@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
	        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10 mt-0">
                    <h1>
            Invoice
            <small class="badge badge-secondary" id="total"><?php echo $data['total']; ?></small>
        </h1>
                </div>
				<div class="col-sm-2">
                   <select class="form-control" id="selectUser" name="selectUser">
                                {!!$data['useroptions']!!}
                            </select>
                </div>
            </div>
            <!-- /.row -->
        </div>

	
	
        
    </section>
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-12 p-0">
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
                    <!--/.card-header--> 
                    <div class="card-body p-0" id="table">
                        <?php echo $data['table']; ?>
                    </div>
                    <!-- /.card -->
<!--                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat pull-right">
                            <input type="button" value="Delete" class="btn btn-danger btn-sm pull-right" onclick="return deleteAll();">
                        </div>                    
                    </div>-->
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
    var deleteAllUrl = "{{url('admin/invoice/deleteAll/{id}')}}";
    var inurl = "{{url('admin/ajax/getUserInvoices')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liinvoice").addClass("active");

        $("#selectAll").click(function() {
            $(".checkAll").prop('checked', $(this).prop('checked'));
        });

        $("#selectUser").change(function() {
            var uid = $(this).val();
            var ajaxUrl = url;
            $.get(ajaxUrl, {'uid': uid}, function(result) {

//                alert(result);

                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#example1').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false,
                    'columnDefs': [
                        {type: 'date-uk', targets: 4}
                    ]
                });
                $('#example2').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false
                });
            });
        });

    });



</script>
@endsection