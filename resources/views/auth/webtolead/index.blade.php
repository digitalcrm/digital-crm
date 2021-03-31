@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Web to Lead</h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('webtolead/create')}}"><i class="far fa-plus-square mr-1"></i> New web to lead</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('webtolead/latestleads/'.date('d'))}}">Latest Leads</a>
                            <!-- <a class="btn btn-sm btn-outline-secondary" href="{{url('webtolead/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('webtolead/export/csv')}}"><i class="fas fa-download"></i></a> -->
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/webtolead')}}"><i class="fas fa-chart-pie"></i></a>

                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mx-0">
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

                    @if(session('info'))
                    <div class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group btn-flat pull-left">
                                <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm btn-outline-secondary text-danger" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>




<!--Preview Modal -->
<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2">Preview</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="tab_1">
                    This is preview
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary pull-right" data-dismiss="modal">Close</button>
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
                <h4 class="modal-title" id="myModalLabel2">Embed Code</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>

            <div class="modal-body">
                <p id="tab_2">
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary pull-left" data-dismiss="modal">Close</button>
                <button type="button" data-clipboard-target="#tab_2" id="copy-btn" class="btn btn-primary copy-text">Copy</button>
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!-- modal -->

<script src="{{asset('assets/js/clipboard.1.6.1.min.js')}}"></script>
<script>
    var url = "{{url('ajaxwebtolead/previewForm')}}";
    var deleteAllUrl = "{{url('webtolead/deleteAllforms/{id}')}}";
    $(function() {
        var clipboard = new Clipboard('.copy-text');

        //$(".active").removeClass("active");

        $("#liwebtolead").addClass("active");

        $("#selectAll").click(function() {
            // alert('select all ' + $(".checkAll").prop('checked'));
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        // (function() {
        //     var pre = document.getElementsByTagName('pre'),
        //         pl = pre.length;
        //     for (var i = 0; i < pl; i++) {
        //         pre[i].innerHTML = '<span class="line-number"></span>' + pre[i].innerHTML + '<span class="cl"></span>';
        //         var num = pre[i].innerHTML.split(/\n/).length;
        //         for (var j = 0; j < num; j++) {
        //             var line_num = pre[i].getElementsByTagName('span')[0];
        //             line_num.innerHTML += '<span>' + (j + 1) + '</span>';
        //         }
        //     }
        // })();

    });

    function previewForm(id) {
        $('#myModal3').modal('hide');
        var ajaxUrl = url;
        $.get(ajaxUrl, {
            'form_id': id,
            'type': 'preview'
        }, function(result) {
            // $("#resulttt").html(result);
            $("#tab_1").html(result);
            $('#myModal2').modal('show');
        });
    }

    function embedCode(id) {
        $('#myModal2').modal('hide');
        var ajaxUrl = url;
        $.get(ajaxUrl, {
            'form_id': id,
            'type': 'embed code'
        }, function(result) {
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

            // alert(itemIds);

            $.get(deleteAllUrl, {
                'id': itemIds
            }, function(result, status) {
                //                                            alert(result);
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