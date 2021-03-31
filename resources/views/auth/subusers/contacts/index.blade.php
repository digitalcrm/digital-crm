@extends('layouts.adminlte-boot-4.user')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Contacts <small class="badge badge-secondary">{{$data['total']}}</small></h1>
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

                @if(session('info'))
                <div class='alert alert-warning'>
                    {{session('info')}}
                </div>
                @endif
                <div class="card shadow card-primary card-outline">

                    <!--/.card-header--> 
                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top pull-right text-right">
                        <a href="<?php echo url('subusers/view/' . $data['user']); ?>" class="btn btn-outline-secondary">Back</a>
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


<script src="{{asset('assets/js/clipboard.1.6.1.min.js')}}"></script>
<script>
var url = "{{url('ajaxwebtolead/previewForm')}}";
$(function() {

    var clipboard = new Clipboard('.copy-text');

    // alert('active');
//    $(".sidebar-menu li").removeClass("menu-open");
//    $(".sidebar-menu li").removeClass("active");
//    $("#licontacts").addClass("active");
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