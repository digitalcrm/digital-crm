@extends('layouts.user')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Web to Lead
            <small>{{$data['total']}}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Web to lead</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                <div class="box">
                    <!--             <div class="box-header">
                                  <h3 class="box-title">Data Table With Full Features</h3>
                                </div> -->
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
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

<!--Preview Modal -->
<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Preview</h4>
            </div>

            <div class="modal-body">
                <div id="tab_1">
                    This is preview
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!-- modal -->

<!--Embed Code Modal -->
<div class="modal right fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Embed Code</h4>
            </div>

            <div class="modal-body">
                <p id="tab_2">         
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" data-clipboard-target="#tab_2"  id="copy-btn" class="btn btn-primary copy-text">Copy</button>
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!-- modal -->

<script src="{{asset('assets/js/clipboard.1.6.1.min.js')}}"></script>
<script>
    var url = "{{url('ajaxwebtolead/previewForm')}}";
    $(function () {

        var clipboard = new Clipboard('.copy-text');

        // alert('active');

        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulwebtolead").addClass('menu-open');
        $("#ulwebtolead ul").css('display', 'block');
        $("#liwebtolead").addClass("active");
    });



    function previewForm(id) {
        $('#myModal3').modal('hide');
        var ajaxUrl = url;
        $.get(ajaxUrl, {'form_id': id, 'type': 'preview'}, function (result) {
            // $("#resulttt").html(result);
            $("#tab_1").html(result);
            $('#myModal2').modal('show');
        });
    }

    function embedCode(id) {
        $('#myModal2').modal('hide');
        var ajaxUrl = url;
        $.get(ajaxUrl, {'form_id': id, 'type': 'embed code'}, function (result) {
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