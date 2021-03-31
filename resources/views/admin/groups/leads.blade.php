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
            <div class="col-lg-8">
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
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            User Leads &nbsp;<span class="badge" id="total">{{$data['total']}}</span>
                        </h3>                            
                    </div> 
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="users">Users</label>
                                    <input type="hidden" value="{{$data['group']->gid}}" name="gid" id="gid">
                                    <select required class="form-control" name="users" id="users">
                                        {!!$data['user_options']!!}
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="card-body" id="table">
                                {!!$data['table']!!}
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <a href="{{url('admin/groups')}}" class="btn btn-default float-left">Back</a>
                        </div>                    
                    </div>
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


<script>
    var userId = "<?php echo $data['userId']; ?>";
    var url = "{{url('admin/groups/user/leads/{gid}/{id}')}}";
//    var deleteAllUrl = "{{url('admin/leads/deleteAll/{id}')}}";
//    var getfacebookleads = "{{url('admin/facebookleads/{id}/{type}')}}";
//    var assignleadtouser = "{{url('admin/assignleadtouser/{user}/{lead}')}}";
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#ligroups").addClass("active");

        $("#users").val(userId);


        $("#users").change(function() {
            var uid = $(this).val();
            var gid = $("#gid").val();
            // alert(uid);
            $.get(url, {'gid': gid, 'id': uid}, function(result) {
//                alert(result);
                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#leadsTable').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false,
                    'responsive': true
                });
            });
        });



    });

    function deleteAll() {
        var deleteIdlength = $('.checkAll:checked').length;
        if (deleteIdlength > 0) {
            var checkList = $('.checkAll:checked');
            var itemIds = [];
            $(checkList).each(function(index) {
                itemIds[index] = $(checkList).get(index).id;
            });

//            alert(itemIds);

            $.get(deleteAllUrl, {'id': itemIds}, function(result, status) {
                if (result > 0) {
                    alert('Deleted successfully...');
                } else {
                    alert('Failed. Try again...');
                }
                location.reload();
            });
        } else {
            alert('Please select...');
        }
    }


    function assignUser(user, lead) {
        alert(user + " " + lead);
        alert($("#" + user).val());

        var userid = $("#" + user).val();
//        var userid = $("#" + user).val();

        $.get(assignleadtouser, {'userid': userid, 'lead': lead}, function(result, status) {
            alert(result);
//            if (result > 0) {
//                alert('Deleted successfully...');
//            } else {
//                alert('Failed. Try again...');
//            }
//            location.reload();
        });

    }

    function calculateTotal() {
//        alert($(".leadQuota").length);
        var total = 0;
        $(".leadQuota").each(function() {
//            alert($(this).val());

            total += Number($(this).val());

        });
        $("#total_quota").val(total);
//        if (total > 100) {
//            alert('Total should be 100');
//            return false;
//        }
//        if (total < 100) {
//            alert('Total should be 100');
//            return false;
//        }

    }


</script>
@endsection