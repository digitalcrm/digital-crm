@extends('layouts.adminlte-boot-4.user')
@section('content')
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Forecast</h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">@can('isUser')
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('forecast/create')}}"><i class="far fa-plus-square mr-1"></i> New Forecast</a>
                            @endcan
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
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                    <div class="card shadow card-primary card-outline">
                        <!--/.card-header-->
					<div class="card-header">
					        <div class="row">
                                <div class="col-lg-2">
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control pull-right monthYearPicker" name="month" id="month" required value="<?php echo date('F-Y'); ?>">
                                    </div>
                                </div>
                            </div>

					</div>
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-12" id="userTable">
                                    <?php echo $data['userTable']; ?>
                                </div>
                            </div>
                            <br>
                            @can('isUser')
                            <div class="row">
                                <div class="col-lg-12" id="subuserTable">
                                    <?php echo $data['subuserTable']; ?>
                                </div>
                            </div>
                            @endcan
                        </div>
                        <!-- /.card-body -->
                        <!-- /.card -->
                    </div>
                    <!-- /.card -->
                    <!-- <div id="resulttt">
                        <input type="text" class="form-control pull-right yearPicker" name="yearPicker" id="yearPicker" required value="<?php echo date('Y'); ?>">
                    </div> -->
                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    //    var currency_html = '<?php echo $data['currency_html']; ?>';
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liforecast").addClass("active");

        $('.monthYearPicker').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy'
        }).focus(function() {
            var thisCalendar = $(this);
            $('.ui-datepicker-calendar').detach();
            $('.ui-datepicker-close').click(function() {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                thisCalendar.datepicker('setDate', new Date(year, month, 1));
                getData();
            });
        });

        $('#yearPicker').datepicker({
            changeMonth: false,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy'
        }).focus(function() {
            var thisCalendar = $(this);
            $('.ui-datepicker-calendar').detach();
            $('.ui-datepicker-close').click(function() {
                // var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                thisCalendar.datepicker('setDate', new Date(year, 1, 1));
                // getData();
            });
        });

        $("#month").change(function() {
            //            alert('monthChange ' + $(this).val());
            var getDate = $(this).val();
            var url = '{{url("ajax/getUserForecast")}}'
            $.get(url, {
                'date': getDate
            }, function(result, status) {
                //                alert(result + " " + status);
                var data = eval("(" + result + ")");
                //                alert(data.userTable);
                //                alert(data.subuserTable);

                $("#userTable").html(data.userTable);
                $("#subuserTable").html(data.subuserTable);

                $('#example1').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false,
                });
            });
        });

    });

    function getData() {
        //    alert($("#month").val());

        var getDate = $("#month").val();
        var url = '{{url("ajax/getUserForecast")}}'
        $.get(url, {
            'date': getDate
        }, function(result, status) {
            //                alert(result + " " + status);
            var data = eval("(" + result + ")");
            //                alert(data.userTable);
            //                alert(data.subuserTable);

            $("#userTable").html(data.userTable);
            $("#subuserTable").html(data.subuserTable);

            $('#example1').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
            });
        });

    }
</script>
@endsection