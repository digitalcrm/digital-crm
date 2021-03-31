@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4">
                    <h1 class="m-0 text-dark">Contacts</h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/contacts/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('admin/contacts/export/csv')}}"><i class="fas fa-download"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
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
                    <div class="card-header with-border">
                        <div class="col-lg-10 float-left">
                            <h3 class="card-title">
                                Contacts <span class="badge badge-secondary" id="total">{{$data['total']}}</span>
                            </h3>                            
                        </div>
                        <div class="col-lg-2 float-right">
                            <select class="form-control" id="selectUser" name="selectUser">
                                {!!$data['useroptions']!!}
                            </select>
                        </div>
                    </div> 
                    <!--/.card-header--> 
                    <div class="card-body p-0" id="table">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <button type="button" value="Delete" class="btn text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
                        </div>                    
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
                <div id="resulttt">

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
    var contactsUrl = "{{url('admin/ajax/getUserContacts')}}";
    var deleteAllUrl = "{{url('admin/contacts/deleteAll/{id}')}}";
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#licontacts").addClass("active");

        $("#selectUser").change(function() {
            var uid = $(this).val();
//            alert(uid);
            // var ajaxUrl = url;
        //    alert(ajaxUrl);
            $.get(contactsUrl, {'uid': uid}, function(result) {
            //    alert(result);
                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#contactsTable').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false
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


</script>
@endsection