@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Product Search Analytics</h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('admin/products')}}"><i class="fas mr-1"></i>Products</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-6 p-0">
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

                    @if(session('info'))
                    <div class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <div class="col-3">
                                <label></label>
                            </div>
                        </div>
                        <!--/.card-header-->
                        <div class="card-body p-0">
                            <div id="table">
                                {!!$data['table']!!}
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top pull-left">
                            <div class="btn-group btn-flat pull-left">
                                <!-- <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button> -->
                            </div>
                        </div>
                        <!-- /.card-footer -->
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
    var deleteAllUrl = "{{url('products/deleteAll/{id}')}}";
    var updateCurrentStockStatus = "{{url('admin/products/update/currentstock/status')}}";
    var prourl = "{{url('admin/products/getuserinventory/{uid}')}}";
    $(function() {

        // alert('active');
        $(".sidebar-menu li").removeClass("active");
        $("#liproducts").addClass("active");

        $("#selectAll").click(function() {
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        $("#selectUser").change(function() {
            var uid = $(this).val();
            // alert(uid);
            // alert(prourl);
            $.get(prourl, {
                'uid': uid
            }, function(result) {
                // alert(result);
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
                    'columnDefs': [{
                        type: 'date-uk',
                        targets: 4
                    }]
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

    function changeCurrentStock(id, proId) {
        // alert(id);
        // alert($("#" + id).val());

        var csVal = $("#" + id).val();

        $.get(updateCurrentStockStatus, {
            'id': proId,
            'status': csVal,
        }, function(result, status) {
            // alert(result);
            if (result > 0) {
                alert('Updated successfully...');
            } else {
                alert('Failed. Try again...');
            }
            // location.reload();
        });

    }

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