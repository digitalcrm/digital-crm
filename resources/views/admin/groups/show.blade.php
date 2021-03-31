@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">Groups</h1>
                </div>                
                <div class="col-sm-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="breadcrumb-item active">Groups</li>
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
            <div class="col-lg-10">
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
                <!-- general form elements -->

                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            <?php echo $data['group']->name; ?>
                        </h3>
                        <a href="<?php echo url('admin/groups/' . $data['group']->gid . '/edit'); ?>" class="btn btn-primary btn-flat float-right">Edit</a>
                    </div>
                    <div class="card-body">
                        <section class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="title">Name</label>
                                <p>{!!$data['group']->name!!}</p>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <p>{!!$data['group']->description!!}</p>
                            </div>
                            <div class="col-lg-12">
                                <div class="card-header with-border">
                                    <h3 class="card-title">Users</h3>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <?php echo $data['users_table']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card-header with-border">
                                    <h3 class="card-title">Products</h3>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <?php echo $data['products_table']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        </section>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <a href="{{url('admin/groups')}}" class="btn btn-default">Back</a>
                            <a href="{{url('admin/groups/delete/'.$data['group']->gid)}}" class="btn btn-danger">Delete</a>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var url = "{{url('admin/groups/assignusers/{gid}/{users}')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#ligroups").addClass("active");


        $("#assignUsers").click(function() {
//            alert('Assign Users');
            var users = $("#groupusers").val();
            var groupid = $("#groupid").val();
//            alert(users);

            if (users != '') {
//                alert(users);
//                alert(url);
//                alert(groupid);
                $.get(url, {'gid': groupid, 'users': users}, function(result) {
//                    alert(result);

                    if (result == 'success') {
                        alert('Assigned Successfully');
                        location.reload();
                    } else {
                        alert('Failed. Please try again later...!');
                    }
                });

            } else {
                alert("Please select users");
            }

        });
    });


</script>
@endsection