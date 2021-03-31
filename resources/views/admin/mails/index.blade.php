@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Mails</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat card) -->
        <div class="row">
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
            <div class="col-md-2">
                <!--<a href="{{url('mails/create')}}" class="btn btn-primary btn-block margin-bottom">Compose</a>-->

                <div class="card card-solid">
                    <div class="card-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active"><a href="{{url('admin/mails')}}"><i class="fa fa-envelope"></i> Sent</a></li>
                            <li><a href="{{url('admin/mails/trash/deletedmails')}}"><i class="fa fa-trash"></i> Trash</a></li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /. card -->

            </div>
            <div class="col-md-10">

                <!-- /.card -->

                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">Sent</h3>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body no-padding">
                        <div class="table-responsive mailbox-messages">
                            {!!$data['table']!!}
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="mailbox-controls">
                            <div class="btn-group btn-flat float-right">
                                <input type="button" value="Delete" class="btn btn-danger btn-sm float-right" onclick="return deleteAll();">
                            </div>
                        </div>
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
    var deleteAllUrl = "{{url('admin/mails/deleteAll/{id}')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#limail").addClass('active');

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
//            alert(deleteAllUrl);

            $.get(deleteAllUrl, {'id': itemIds}, function(result, status) {

//                alert(result + " " + status);

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



</script>
@endsection