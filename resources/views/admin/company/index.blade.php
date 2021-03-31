@extends('layouts.adminlte-boot-4.admin')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Companies <small id="total" class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
                <!-- <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/accounts/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/accounts/export/csv')}}"><i class="fas fa-download"></i></a>
                        </li>
                    </ol>
                </div>
            </div> -->
            </div>
        </div>
        <!-- Main content -->
        <section class="content mt-2 mx-0">
            <div class="container-fluid">
                <!-- Content Header (Page header) -->

                <!-- Small cardes (Stat card) -->
                <div class="row">
                    <div class="col-lg-12 p-0">
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
                        <div class="card shadow card-primary card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <!-- <div class="col-4">
                                        User
                                        <select class="form-control" id="selectUser" name="selectUser">
                                            {!!$data['useroptions']!!}
                                        </select>
                                    </div> -->
                                </div>
                            </div>
                            <!--/.card-header-->
                            <div class="card-body p-0" id="table">
                                @livewire('company.admin.company-list')
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer bg-white border-top">
                                <div class="btn-group btn-flat">
                                    <label class="btn btn-danger btn-outline-secondary"><input type="checkbox" id="selectAll">&nbsp;Select All</label> &nbsp;
                                    <button type="button" value="Delete" class="btn text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
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
        var accountsurl = "{{url('admin/ajax/getUserCompanies')}}";
        var deleteAllUrl = "{{url('admin/companies/deleteAll/{id}')}}";
        $(function() {
            $(".sidebar-menu li").removeClass("active");
            $("#liaccounts").addClass("active");

            $("#selectUser").change(function() {
                var uid = $(this).val();
                //            alert(uid);

                getAccounts(uid);

            });


            $("#selectAll").click(function() {
                $(".checkAll").prop('checked', $(this).prop('checked'));
            });

        });

        function getAccounts(uid) {

            $.get(accountsurl, {
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
                    'ordering': true,
                    'info': true,
                    'autoWidth': false,
                });
                $('#example2').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false
                });
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
                $.get(deleteAllUrl, {
                    'id': itemIds
                }, function(result, status) {
                    if (result > 0) {
                        alert('Deleted successfully...');
                        $("#selectUser").prop('checked', 'false');
                        var uid = $("#selectUser").val();
                        getAccounts(uid);
                    } else {
                        alert('Failed. Try again...');
                    }
                    // location.reload();
                });
            } else {
                alert('Please select...');
            }
        }
    </script>
</div>
@endsection