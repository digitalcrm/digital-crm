@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Mails</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
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
                </div>
                <div class="col-md-2">
                    <a href="{{url('mailers/create')}}" class="btn btn-primary btn-block mb-3">Compose</a>

                    <div class="card shadow card-primary card-outline">
                        <!-- <div class="card-header card-header-sm">
                        <h3 class="card-title">Folders</h3>
                    </div> -->
                        <div class="card-body p-0">
                            <ul class="nav nav-pills flex-column">

                                <li class="nav-item">
                                    <a href="{{url('mailers')}}" class="nav-link">
                                        <i class="far fa-envelope"></i> Sent
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('mailers/trash/deletedmails')}}" class="nav-link">
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
                    <div class="card shadow card-primary card-outline card-secondary card-outline">

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
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
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

<script>
    var deleteAllUrl = "{{url('mailers/deleteAll/{id}')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#limail").addClass('active');

        $("#selectAll").click(function() {
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
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