 @extends('layouts.adminlte-boot-4.admin')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Facebook Leads from Zapier <span class="badge badge-secondary" id="total">{{$data['total']}}</span></h1>
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
                <div class="card shadow">
                    <!-- /.card-header -->
                        <div class="card-header">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="date">Closing Date</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <div class="input-group date">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control float-right" name="date" id="datepicker" required value="<?php echo date('d-m-Y'); ?>">
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    <div class="card-body p-0" id="table">
                            {!!$data['table']!!}
                        </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <div class="btn-group btn-flat float-right">
                            <button type="button" value="Delete" class="btn text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
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
    var getleadszapierajax = "{{url('admin/zpleads/ajax/{date}')}}";
    $(function() {

        //Date picker
        $('#datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        });

        $(".sidebar-menu li").removeClass("active");
        $("#lileads").parent('ul').css('display', 'block');
        $("#lizpleads").addClass("active");

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

        $("#datepicker").change(function() {
            var date = $(this).val();
//            alert(date);
            $.get(getleadszapierajax, {'date': date}, function(result) {
//                alert(result);
                var res = eval("(" + result + ")");
                $("#total").text(res.total);
                $("#table").html(res.table);
                $('#leadsTable').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false
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


</script>
@endsection