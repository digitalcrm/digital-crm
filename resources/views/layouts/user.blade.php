<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Digital CRM| Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/Ionicons/css/ionicons.min.css')}}">
        <!-- fullCalendar -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('assets/dist/css/AdminLTE.min.css')}}">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{{asset('assets/dist/css/skins/_all-skins.min.css')}}">
        <!-- Morris chart -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/morris.js/morris.css')}}">
        <!-- jvectormap -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/jvectormap/jquery-jvectormap.css')}}">
        <!-- Date Picker -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
        <!--Custom Css file--->
        <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
        <!--Font awesome css file-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <!--JS file-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/select2/dist/css/select2.min.css')}}">

        <!-- Latest compiled and minified CSS -->
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">-->

        <!-- Latest compiled and minified JavaScript -->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>-->

        <!-- (Optional) Latest compiled and minified JavaScript translation files -->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-*.min.js"></script>-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">

            <!-- header -->
            @include('includes.user.header')
            <!-- /.header -->

            <!-- Left side column. contains the logo and sidebar -->
            <!-- sidebar: style can be found in sidebar.less -->
            @include('includes.user.main-sidebar')
            <!-- /.sidebar -->

            <!-- Content yield function -->
            @yield('content')
            <!-- Content yield function -->

            <!-- footer -->
            @include('includes.user.footer')
            <!-- /.footer -->


        </div>
        <!-- ./wrapper -->

        <!-- jQuery 3 -->
        <script src="{{asset('assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{asset('assets/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
$.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <!-- Morris.js charts -->
        <script src="{{asset('assets/bower_components/raphael/raphael.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/morris.js/morris.min.js')}}"></script>
        <!-- Sparkline -->
        <script src="{{asset('assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
        <!-- jvectormap -->
        <script src="{{asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{asset('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{asset('assets/bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
        <!-- daterangepicker -->
        <script src="{{asset('assets/bower_components/moment/min/moment.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
        <!-- datepicker -->
        <script src="{{asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="{{asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
        <!-- Slimscroll -->
        <script src="{{asset('assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
        <!-- FastClick -->
        <script src="{{asset('assets/bower_components/fastclick/lib/fastclick.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('assets/dist/js/adminlte.min.js')}}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{asset('assets/dist/js/pages/dashboard.js')}}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{asset('assets/dist/js/demo.js')}}"></script>
        <!-- page script -->

        <!-- fullCalendar -->
        <script src="{{asset('assets/bower_components/moment/moment.js')}}"></script>
        <script src="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
        <!-- bootstrap time picker -->
        <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
        <!-- Select2 -->
        <script src="{{asset('assets/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
        <script>
$(function() {
//    $('.select2').select2();
    //Timepicker
    $('.timepicker').timepicker({
        showInputs: false
    })
});
        </script>
        <!-- DataTables -->
        <script src="{{asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
        <!---Datatables date sorting plugin------------->
        <script src="{{asset('assets/bower_components/datatables.date/js/date-uk.js')}}"></script>
        <script>
var ajaxUrl = '<?php echo url('ajax/getlatestnotifications'); ?>';
var markallasread = '<?php echo url('ajax/markallasread'); ?>';
var markasread = '<?php echo url('ajax/markasread'); ?>';
$(function() {

    callNotification();

    $('#accountsTable').DataTable({
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

    $('#contactsTable').DataTable({
//        "aaSorting": [],
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

    $('#leadsTable').DataTable({
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

    $('#dealsTable').DataTable({
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

    $('#customersTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 3}
        ]
    });

    $('#salesTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
//        'columnDefs': [
//            {type: 'date-uk', targets: 3}
//        ]
    });

    $('#formsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 5}
        ]
    });

    $('#formleadsTable').DataTable({
        "aaSorting": [],
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 5}
        ]
    });
    $('#latestformleadsTable').DataTable({
        "aaSorting": [],
////        'paging': true,
////        'lengthChange': true,
////        'searching': true,
////        'ordering': true,
//        'info': true,
//        'autoWidth': false,
////        'columnDefs': [
////            {type: 'date-uk', targets: 7}
////        ]
    });

    $('#productsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 3}
        ]
    });

    $('#subusersTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 2}
        ]
    });

    $('#example1').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
    });

    $('#example2').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
    });
    $('#latestdealsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': false,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 4}
        ],
        "lengthMenu": [7],
    });


//                alert(ajaxUrl);
//
//    $.get(ajaxUrl, {'type': 'getLatestnotifications'}, function(result, status) {
////                    alert(result);
//        var res = eval("(" + result + ")");
//        $("#not_menu").html(res.formstable);
//        $("#not_count").html(res.not_count);
//        $("#limsg").html(res.limsg);
//    });
});

function markAllasread() {
    $.get(markallasread, {'type': 'Markallasread'}, function(result, status) {
//                    alert(result);
//                    var res = eval("(" + result + ")");
//                    $("#not_menu").html(res.formstable);
//                    $("#not_count").html(res.not_count);
//                    $("#limsg").html(res.limsg);
        $.get(ajaxUrl, {'type': 'getLatestnotifications'}, function(result, status) {
//                    alert(result);
            var res = eval("(" + result + ")");
            $("#not_menu").html(res.formstable);
            $("#not_count").html(res.not_count);
            $("#limsg").html(res.limsg);
        });
    });
}

function markAsRead(id) {
//    alert(id);
    $.get(markasread, {'id': id}, function(result, status) {
//        alert(result);
    });

}

var myVar;

function callNotification() {
    myVar = setInterval(getNotifications, 1 * 60 * 1000);
}

function getNotifications() {
//    alert("Hello! - 1minute");


    $.get(ajaxUrl, {'type': 'getLatestnotifications'}, function(result, status) {
//                    alert(result);
        var res = eval("(" + result + ")");
        $("#not_menu").html(res.formstable);
        $("#not_count").html(res.not_count);
        $("#limsg").html(res.limsg);
    });

}
        </script>

    </body>
</html>
