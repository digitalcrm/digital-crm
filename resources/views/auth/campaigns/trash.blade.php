@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Trash</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0"><div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
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


            <div class="col-md-2">
                <a href="{{url('campaigns/mails/create/'.$data['camp_id'])}}" class="btn btn-sm btn-primary btn-block mb-3">Compose</a>

                <div class="card ">
                    <div class="card-header card-header-sm">
                        <h3 class="card-title">Folders</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">

                            <li class="nav-item">
                                <a href="{{url('campaigns/mails/sent/'.$data['camp_id'])}}" class="nav-link">
                                    <i class="far fa-envelope"></i> Sent
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('campaigns/mails/trash/'.$data['camp_id'])}}" class="nav-link">
                                    <i class="far fa-trash-alt"></i> Trash
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
            <!-- /.col -->
            <div class="col-md-10">
                <div class="card card-primary card-outline">
                    <div class="card-header with-border">
                        <h1 class="card-title">{{$data['name']}} &nbsp;<small class="badge badge-secondary"></small></h1>
                        <div class="btn-group btn-flat pull-right">
                            <div class="btn-group">
                                <a class="btn bg-blue" href="{{url('campaigns/create')}}">Action</a>
                                <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="{{url('mailtemplates/create')}}">Create Email Template</a>
                                    <a class="dropdown-item" href="{{url('mailtemplates')}}">View Email Templates</a>
                                    <a class="dropdown-item" href="#">Set Up Email</a>
                                    <a class="dropdown-item" href="{{url('webtolead')}}">Web to Lead</a>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mailbox-messages">
                            {!!$data['table']!!}
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat pull-left">
                            <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                            <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return restoreAll();">Restore All</button>
                            <a href="{{url('campaigns/')}}" class="btn btn-default"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
    var deleteAllUrl = "{{url('campaigns/mails/restoreAll/{id}')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        //                                    $(".sidebar-menu li").removeClass("active");
        //                                    $("#ulaccounts").addClass('menu-open');
        //                                    $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");


        $("#selectAll").click(function() {
            // alert('Select All');
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        CKEDITOR.replace('message');

    });


    function restoreAll() {
        var deleteIdlength = $('.checkAll:checked').length;
        if (deleteIdlength > 0) {
            var checkList = $('.checkAll:checked');
            var itemIds = [];
            $(checkList).each(function(index) {
                itemIds[index] = $(checkList).get(index).id;
            });

            //                                        alert(itemIds);

            $.get(deleteAllUrl, {
                'id': itemIds
            }, function(result, status) {
                //                                            alert(result);
                if (result > 0) {
                    alert('Restored successfully...');
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