@extends('layouts.adminlte-boot-4.user')
@section('content')
<link rel="stylesheet" href="{{asset('assets/toastr/toastr.min.css')}}">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Contacts <small class="badge badge-secondary">{{$data['total']}}</small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('contacts/create')}}"><i class="far fa-plus-square mr-1"></i> New Contact</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('contacts/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('contacts/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/contacts')}}"><i class="fa fa-chart-pie"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">
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
                        <!--/.card-header-->
                        <div class="card-body p-0">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group btn-flat pull-left">
                                <button id="selectAll" class="btn btn-sm btn-outline-secondary" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();" href="#"><i class="far fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>


            </div>
            <!-- /.row -->
        </div>

</div>
</section>
<!-- /.content -->

</div>
<!-- /.content-wrapper -->
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        // $(".sidebar-menu li").removeClass("menu-open");
        // $(".sidebar-menu li").removeClass("active");
        // //        $("#ulleads").addClass('menu-open');
        // //        $("#ulleads ul").css('display', 'block');
        // $("#licontacts").addClass("active");

        $("#account").change(function() {
            var acc = $(this).val();
            if (acc == "NewAccount") {
                // console.log("show the input");
                $("#addAccount").show();
            } else {
                $("#addAccount").hide();
            }
        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
{{-- Below delete all script --}}
<script>
    var deleteAllUrl = "{{url('contacts/deleteAll/{id}')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#licontacts").addClass("active");

        $("#selectAll").click(function() {
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
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
{{-- if error occur then modal will appear --}}
<script src="{{asset('assets/toastr/toastr.min.js')}}"></script>

@if (count($errors) > 0)

<script type="text/javascript">
    $(document).ready(function() {
        $('#exampleModal2').modal('show');

        toastr.options = {
                "positionClass": "toast-top-left",
            },
            toastr.error('Error in modal')
    });
</script>

@endif

@endsection