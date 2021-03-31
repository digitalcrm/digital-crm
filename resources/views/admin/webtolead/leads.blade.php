 @extends('layouts.adminlte-boot-4.admin')
@section('content')

<!--Style-->
<style type="text/css">


</style>
<!--/Style-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-0">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{!!$data['forms']->title!!} - Form Leads <small class="badge badge-secondary">{{$data['total']}}</small></h1>
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
                <div class="card shadow card-primary card-outline">
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top text-right pull-right">
                            <button type="button" value="Delete" class="btn text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
							<a href="{{url('admin/webtolead')}}" class="btn btn-outline-secondary">Back</a>
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
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
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
                <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                <button type="button" data-clipboard-target="#tab_2"  id="copy-btn" class="btn btn-primary copy-text">Copy</button>
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!-- modal -->

<script src="{{asset('assets/js/clipboard.1.6.1.min.js')}}"></script>
<script>
                                var url = "{{url('ajaxwebtolead/previewForm')}}";
                                var deleteAllformleads = "{{url('admin/webtolead/deleteAllformleads/{id}')}}";
                                $(function() {

                                    var clipboard = new Clipboard('.copy-text');

                                    // alert('active');

                                    $(".active").removeClass("active");
                                    $("#liwebtolead").addClass("active");


                                    $("#selectAll").click(function() {
                                        $(".checkAll").prop('checked', $(this).prop('checked'));
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

                                function deleteAll() {
                                    var deleteIdlength = $('.checkAll:checked').length;
                                    if (deleteIdlength > 0) {
                                        var checkList = $('.checkAll:checked');
                                        var itemIds = [];
                                        $(checkList).each(function(index) {
                                            itemIds[index] = $(checkList).get(index).id;
                                        });

//            alert(itemIds);

                                        $.get(deleteAllformleads, {'id': itemIds}, function(result, status) {
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