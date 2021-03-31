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
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            Assign Leads to Group Users
                        </h3>                            
                    </div> 
                    <!-- /.card-header -->
                    {{Form::open(['action'=>['Admin\GroupController@assignLeadstoGroupUsersbyQuota'],'method'=>'Post','enctype'=>'multipart/form-data','class'=>'form-horizontal'])}}
                    @csrf
                    <div class="card-body">
                        <div class="col-lg-12">
                            <p>Total Users : {!!$data['total_users']!!}</p>
                            <p>Total unassigned leads : {!!$data['total_leads']!!}<input type="hidden" value="{{$data['total_leads']}}" name="total_leads" id="total_leads"/></p>
                        </div>
                        <div class="col-lg-6" id="table">
                            <input type="hidden" value="<?php echo $data['groups']->gid; ?>" id="gid" name="gid"/>
                            <input type="hidden" value="<?php echo $data['form_id']; ?>" id="form_id" name="form_id"/>
                            {!!$data['table']!!}
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <!--<input type="button" value="Delete" class="btn btn-danger btn-sm float-right" onclick="return deleteAll();">-->
                            <a href="{{url('admin/groups')}}" class="btn btn-default float-left">Back</a>
                            {{Form::submit('Assign',['class'=>"btn btn-primary float-right"])}}
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
    var url = "{{url('admin/ajax/getUserLeads')}}";
    var deleteAllUrl = "{{url('admin/leads/deleteAll/{id}')}}";
    var getfacebookleads = "{{url('admin/facebookleads/{id}/{type}')}}";
    var assignleadtouser = "{{url('admin/assignleadtouser/{user}/{lead}')}}";
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#ligroups").addClass("active");

        var previous;
        $("#selectCsv").on('focus', function() {
            // Store the current value on focus and on change
            previous = this.value;
        }).change(function() {

            var leadtype = $("#selectLeads").val();
            var csv = $(this).val();

            if (csv > 0) {
//            alert(csv);
                $.get(getfacebookleads, {'id': csv, 'type': leadtype}, function(result) {
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
                        'autoWidth': false
                    });
                });
            } else {
                $(this).val(previous);
                alert('Please select...');
                return false;
            }
        });


        $("#selectLeads").change(function() {
            var leadtype = $(this).val();
//            alert(leadtype);

            var csv = $("#selectCsv").val();

            $.get(getfacebookleads, {'id': csv, 'type': leadtype}, function(result) {
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
                    'autoWidth': false
                });
            });
        });


        $("#selectUser").change(function() {
            var uid = $(this).val();
            // alert(uid);
            var ajaxUrl = url;
            // alert(ajaxUrl);
            $.get(ajaxUrl, {'uid': uid}, function(result) {
                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#leadsTable').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false
                });
            });
        });

        $("#selectAll").click(function() {
            $(".checkAll").prop('checked', $(this).prop('checked'));
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


</script>
@endsection