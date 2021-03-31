@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Accounts <small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('accounts/create')}}"><i class="far fa-plus-square mr-1"></i> New Account</a>
                            <!-- <a class="btn btn-sm btn-outline-secondary" href="{{url('accounts/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('accounts/export/csv')}}"><i class="fas fa-download"></i></a> -->
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/accounts')}}"><i class="fa fa-chart-pie"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12">
                    @if(session('success'))
                    <div id="alertSuccess" class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div id="alertError" class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif

                    @if(session('info'))
                    <div id="alertWarning" class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif

                    <div class="card shadow card-primary card-outline">
                        <div class="card-body p-0">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group btn-flat pull-left">
                                <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <!-- /.card-footer -->
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

    var deleteAllUrl = "{{url('accounts/deleteAll/{id}')}}";
    $(function() {

        // alert('active');
        $(".sidebar-menu li").removeClass("active");
        $("#liaccounts").addClass("active");

        $("#selectAll").click(function() {
            // $(".checkAll").prop('checked', $(this).prop('checked'));
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