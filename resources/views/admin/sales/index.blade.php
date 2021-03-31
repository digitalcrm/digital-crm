 @extends('layouts.adminlte-boot-4.admin')
 @section('content')

 <!--Style-->
 <style type="text/css">

 </style>
 <!--/Style-->

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <div class="content-header">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-sm-10">
                     <h1 class="m-0 text-dark">Sales <small class="badge badge-secondary" id="total">{{$data['total']}}</small></h1>
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
                     <div class="card shadow card-primary card-outline">
                         <div class="card-header">
                             <div class="row">
                                 <div class="col-4">
                                     <div class="form-group">
                                         <label>User</label>
                                         <select class="form-control" id="selectUser" name="selectUser">
                                             {!!$data['useroptions']!!}
                                         </select>
                                     </div>
                                 </div>
                                 <div class="col-4">
                                     <div class="form-group">
                                         <label>Payment Status</label>
                                         <select id="paymentStatusFilter" class="form-control" name="paymentStatusFilter">
                                             {!!$data['paymentstatusOptions']!!}
                                         </select>
                                     </div>
                                 </div>

                                 <div class="col-4">
                                     <div class="form-group">
                                         <label>Closing Date</label>
                                         <div class="input-group">
                                             <div class="input-group-prepend">
                                                 <span class="input-group-text">
                                                     <i class="far fa-calendar-alt"></i>
                                                 </span>
                                             </div>
                                             <input type="text" class="form-control float-right" id="reservation">
                                             <div class="input-group-append">
                                                 <span class="input-group-text" id="close_date"><i class="fa fa-window-close"></i></span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <!-- /.card-header -->
                         <div class="card-body p-0" id="table">
                             {!!$data['table']!!}
                         </div>
                         <!-- /.card-body -->
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

 <script>
    //  var url = "{{url('admin/ajax/getUserSales')}}";
     var filterUrl = "{{url('admin/sales/filter/{start}/{end}/{status}/{user}')}}";
     var paystatusChange = "{{url('admin/sales/paystatus/{id}/{status}')}}";
     $(function() {
         $(".sidebar-menu li").removeClass("active");
         $("#lisales").addClass("active");

         $('#reservation').daterangepicker({
             locale: {
                 format: 'DD-MM-YYYY'
             },
             startDate: moment().subtract(30, 'days'),
             endDate: moment(),
             function(startDate, endDate) {
                 $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
             }
         });

         $('#reservation').change(function() {
             var uid = $("#selectUser").val();
             var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
             var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
             var status = $("#paymentStatusFilter").val();
             // alert(startDate + ' ' + endDate);
             // return false;
            //  alert(uid + ' ' + status + ' ' + startDate + ' ' + endDate);
             getSalesData(uid, startDate, endDate, status);

         });

         $("#paymentStatusFilter").change(function() {
             var uid = $("#selectUser").val();
             var status = $(this).val();
             var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
             var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');
            //  alert(uid + ' ' + status + ' ' + startDate + ' ' + endDate);
             getSalesData(uid, startDate, endDate, status);
         });

         $("#close_date").click(function() {
             var uid = $("#selectUser").val();
             var status = $("#paymentStatusFilter").val();
             var startDate = "";
             var endDate = "";
            //  alert(uid + ' ' + status + ' ' + startDate + ' ' + endDate);
             getSalesData(uid, startDate, endDate, status);
         });

         $("#selectUser").change(function() {
             var uid = $(this).val();
             var status = $("#paymentStatusFilter").val();
             var startDate = $('#reservation').data('daterangepicker').startDate.format('DD-MM-YYYY');
             var endDate = $('#reservation').data('daterangepicker').endDate.format('DD-MM-YYYY');

            //  alert(uid + ' ' + status + ' ' + startDate + ' ' + endDate);

             getSalesData(uid, startDate, endDate, status);
         });
     });


     function getSalesData(uid, startDate, endDate, status) {

        //  alert(uid + ' ' + status + ' ' + startDate + ' ' + endDate);

         $.get(filterUrl, {
             'start': startDate,
             'end': endDate,
             'status': status,
             'user': uid,
         }, function(result, status) {

            //  alert(result);

              var res = eval("(" + result + ")");
              $("#total").text(res.total);
              $("#table").html(res.table);
              $('#salesTable').DataTable({
                  'paging': true,
                  'lengthChange': true,
                  'searching': true,
                  'ordering': false,
                  'info': true,
                  'autoWidth': false,
              });
         });
     }
     
    function changePayStatus(id, deal) {
        // alert(id + ' ' + deal);

        var status = $("#" + id).val();
        // alert(status);


        $.get(paystatusChange, {
            'id': deal,
            'status': status,
        }, function(result, status) {
            // alert(result);
            if (result) {
                alert('Updated Successfully...!');
            } else {
                alert('Updated Failed. Try again later...!');
            }
        });

    }
 </script>
 @endsection