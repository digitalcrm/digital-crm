@extends('layouts.adminlte-boot-4.user')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Notifications <small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif
                <div class="card card-primary">
                    <!--
                    <div class="card-header">
                        <div class="input-group col-lg-4">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="month" id="closingdate" name="closingdate" class="form-control" min="2018-01" max="<?php echo date('Y-m'); ?>" value="<?php echo date('Y-m'); ?>" required>
                        </div>
                    </div> 
                    -->
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat pull-left">
                            <button class="btn btn-sm btn-outline-secondary" id="selectAll"><i class="fas fa-check"></i> Select All</button>
                            <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
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
    var deleteAllUrl = "{{url('notifications/deleteAll/{id}')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        //        $("#lisales").addClass("active");

        $("#selectAll").click(function() {
            // $(".checkAll").prop('checked', $(this).prop('checked'));
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        $.get(ajaxUrl, {
            'type': 'getLatestnotifications'
        }, function(result, status) {
            //                    alert(result);
            var res = eval("(" + result + ")");
            $("#not_menu").html(res.formstable);
            $("#not_count").html(res.not_count);
            $("#limsg").html(res.limsg);
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

            // alert(itemIds);

            $.get(deleteAllUrl, {
                'id': itemIds
            }, function(result, status) {
                // alert(result);
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