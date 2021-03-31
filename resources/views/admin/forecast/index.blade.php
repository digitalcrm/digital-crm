 @extends('layouts.adminlte-boot-4.admin')
 @section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <div class="content-header">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-sm-6">
                     <h1 class="m-0 text-dark">Forecast</h1>
                 </div>
                 <div class="col-sm-6">
                     <a class="btn btn-primary float-right" href="{{url('admin/forecast/create')}}"><i class="far fa-plus-square mr-2"></i>New Forecast</a>
                 </div>
             </div>
             <!-- /.row -->
         </div>
         <!-- /.container-fluid -->
     </div>
     {{-- <section class="content-header">
        <h1>
            Forecast
            <small id="total"></small>
        </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Web to lead</li>
                    </ol>
    </section> --}}
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
                     <div class='alert alert-danger'>
                         {{session('error')}}
                     </div>
                     @endif
                     <div class="card shadow card-primary card-outline">
                         <div class="card-header">
                             <h3 class="card-title float-left">
                                 <div class="input-group date">
                                     <div class="input-group-text">
                                         <i class="fa fa-calendar"></i>
                                     </div>
                                     <input type="text" class="form-control float-right monthYearPicker" name="month" id="month" required value="<?php echo date('F-Y'); ?>">
                                     <span class="text-danger">{{ $errors->first('month') }}</span>
                                 </div>
                             </h3>
                             <div class="btn-group float-right">
                                 <select class="form-control" id="selectUser" name="selectUser">
                                     {!!$data['useroptions']!!}
                                 </select>
                             </div>
                         </div>
                         <!--/.card-header-->
                         {{-- <div class="card-body">
                        <div class="card card-danger"> --}}
                         <!--                            <div class="card-header with-border">
                                                            <h3 class="card-title">Different Width</h3>
                                                        </div>-->
                         <div class="card-body p-0" id="userTable">
                             <?php echo $data['userTable']; ?>
                         </div>
                     </div>
                     <!-- /.card-body -->
                     {{-- </div>

                        <br>

                    </div> --}}
                     <!-- /.card -->
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
 <!--JQuery Datepicker-->
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>
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
                 getForecast();
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


         //  //Date picker
         //  $('#month').datepicker({
         //      format: 'MM-yyyy',
         //      autoclose: true,
         //      viewMode: "months",
         //      minViewMode: "months"
         //  });

         //  $("#month").change(function() {
         //      //            alert('monthChange ' + $(this).val());
         //      //            alert('selectUser ' + $(selectUser).val());
         //      var user = $("#selectUser").val();
         //      var getDate = $(this).val();
         //      getForecast(getDate, user);
         //  });


         $("#selectUser").change(function() {
             //            alert('selectUser ' + $(this).val());
             //            alert('monthChange ' + $(month).val());
             //  var user = $(this).val();
             //  var getDate = $("#month").val();
             getForecast();
         });



     });

     function getForecast(date, user) {

         var user = $("#selectUser").val();
         var date = $("#month").val();

        //  alert(date + ' ' + user);

         var url = '{{url("admin/ajax/getUserForecast")}}';
         $.get(url, {
             'date': date,
             'uid': user
         }, function(result, status) {
             //            alert(result + " " + status);
             //            var data = eval("(" + result + ")");
             ////                alert(data.userTable);
             ////                alert(data.subuserTable);
             //
             $("#userTable").html(result);
             //                $("#subuserTable").html(data.subuserTable);

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