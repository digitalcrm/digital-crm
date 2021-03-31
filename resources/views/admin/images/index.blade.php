@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Images
            <small id="total">0</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Content Header (Page header) -->

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
                <div class="card shadow card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            Images
                        </h3>

                    </div> 
                    <!--/.card-header--> 
                    <div class="card-body" id="table">
                        
                    </div>
                    <!-- /.card-body -->
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
    var url = "{{url('admin/ajax/getUserAccounts')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#ulsettings").addClass("active");


        $("#selectUser").change(function() {
            var uid = $(this).val();
//            alert(uid);
            var ajaxUrl = url;
//            alert(ajaxUrl);
            $.get(ajaxUrl, {'uid': uid}, function(result) {
//                alert(result);
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
                    'columnDefs': [
                        {type: 'date-uk', targets: 4}
                    ]
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



    function previewForm(id) {
        $('#myModal3').modal('hide');
        var ajaxUrl = url;
        $.get(ajaxUrl, {'form_id': id, 'type': 'preview'}, function(result) {
            // $("#resulttt").html(result);
            $("#tab_1").html(result);
            $('#myModal2').modal('show');
        });
    }

    function embedCode(id) {
        $('#myModal2').modal('hide');
        var ajaxUrl = url;
        $.get(ajaxUrl, {'form_id': id, 'type': 'embed code'}, function(result) {
            // $("#resulttt").html(result);
            $("#tab_2").html(result);
            $('#myModal3').modal('show');
        });
    }

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        // alert($("#tab_2").text());
        $temp.val($("#tab_2").text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
</script>
@endsection