@extends('layouts.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12">
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
                <div class="box">
                    <div class="box-header">
                        <h1 class="box-title">File Manager &nbsp;<small class="badge badge-secondary">{{$data['total']}}</small></h1>
                        <!--                        <div class="btn-group btn-flat pull-right">
                                                    <a class="btn bg-blue" href="{{url('documents/create')}}"><i class="fa fa-plus"></i>&nbsp;Add New</a>
                                                </div>-->

                    </div> 
                    <!--/.box-header--> 
                    <div class="box-body">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-left">
                            <!--<input type="button" value="Delete" class="btn btn-danger btn-sm pull-right" onclick="return deleteAll();">-->
                            <!--<input type="checkbox" name="selectAll" id="selectAll">&nbsp;Select All | &nbsp;-->
                            <!--<a href="#" class="pull-right" onclick="return deleteAll();">Delete</a>-->
                        </div>                    
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    var deleteAllUrl = "{{url('documents/deleteAll/{id}')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lifiles").addClass("active");

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


</script>
@endsection