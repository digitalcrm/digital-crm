@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Lead CSV</h1>
                </div>                
                <div class="col-sm-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="breadcrumb-item active">Lead CSV</li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
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
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Get Facebook Leads with Zapier
                        </h3>
                        <!--                        <div class="col-lg-10">
                                                    
                                                </div>-->
                        <!--                        <div class="col-lg-2">
                                                    <div class="btn-group btn-flat float-right">
                                                        <a class="btn bg-blue" href="{{url('admin/leadcsv/create')}}"><i class="fa fa-plus"></i>&nbsp; Add New</a>
                                                    </div>
                                                </div>-->
                    </div>
                    <!--/.card-header-->
                    <div class="card-body">
                        <p>Facebook Ad Manager</p><br>
                        <p>
                            To get leads from facebook to crm we have integrate facebook with zapier. Please following below instructions:
                            1. Login into your facebook account.
                            2. Go to <b>Manage Ads</b>.
                        </p>
                        <div class="card shadow card-primary card-outline">
                            <div class="card-header">
                                <div class="col-lg-10">
                                    <h3 class="card-title">
                                        Csv Files&nbsp;<span id="total" class="badge">{{$data['total']}}</span>
                                    </h3>
                                </div>
                            </div>
                            <!--/.card-header-->
                            <div class="card-body"  id="table">
                                {!!$data['table']!!}
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<form id="importleaddata-form" action="{{ url('admin/leadcsv/importleaddata') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" value="" name="file_id" id="file_id"/>
</form>

<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liextentions").addClass("active");


        $("#selectUser").change(function() {
            var uid = $(this).val();
            var ajaxUrl = "{{url('admin/ajax/getUserDocuments')}}";
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

    function submitLeadData(file_id) {
//        alert(file_id);

        $("#file_id").val(file_id);
        $("#importleaddata-form").submit();
    }



</script>
@endsection
