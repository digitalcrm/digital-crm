<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('title',config('app.name'))</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/bigindiafav.png') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/fontawesome-free/css/all.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bbootstrap 4 -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/jqvmap/jqvmap.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/dist/css/adminlte.min.css')}}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/daterangepicker/daterangepicker.css')}}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/summernote/summernote-bs4.css')}}">

        <!-- datatables bootstrap4-->
        <!--<link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/datatables/dataTables.bootstrap4.css')}}">-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/r-2.2.1/datatables.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/dist/css/custom.css')}}">
        <!-- fullCalendar -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/select2/css/select2.min.css')}}">
        <!--JS file-->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.1/dist/alpine.min.js" defer></script>
        <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
        @stack('styles')
        @livewireStyles
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <!-- navbar -->
            @include('includes.adminlte-boot-4.user.navbar')
            <!-- /.navbar -->

            <!-- aside -->
            @include('includes.adminlte-boot-4.user.aside')
            <!-- /.aside -->

            <!-- Content yield function -->
            @yield('content')
            <!-- Content yield function -->

            <!-- footer -->
            @include('includes.adminlte-boot-4.user.aside')
            <!-- /.footer -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

@livewireScripts
        <!-- jQuery -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/jquery/jquery.min.js')}}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
$.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- ChartJS -->
        <!--<script src="{{asset('assets/adminlte-boot-4/plugins/chart.js/Chart.min.js')}}"></script>-->

        <!-- daterangepicker -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('assets/adminlte-boot-4/plugins/daterangepicker/daterangepicker.js')}}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <!-- Summernote -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/summernote/summernote-bs4.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- FastClick -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/fastclick/fastclick.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('assets/adminlte-boot-4/dist/js/adminlte.js')}}"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{asset('assets/adminlte-boot-4/dist/js/pages/dashboard.js')}}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{asset('assets/adminlte-boot-4/dist/js/demo.js')}}"></script>
        <!-- fullCalendar -->
        <script src="{{asset('assets/bower_components/moment/moment.js')}}"></script>
        <script src="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
        <!-- bootstrap time picker -->
        <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
        <!-- Select2 -->
        <script src="{{asset('assets/adminlte-boot-4/plugins/select2/js/select2.full.min.js')}}"></script>
		<!-- Select2 -->
        <script src="{{asset('assets/adminlte-boot-4/dist/js/checkbox-color.js')}}"></script>

        <script>
$(function() {
    $('.select2').select2();
    //Timepicker
    $('.timepicker').timepicker({
        showInputs: false
    });

});
        </script>
        <!-- DataTables -->
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/r-2.2.1/datatables.min.js"></script>
<!--        <script src="{{asset('assets/adminlte-boot-4/plugins/datatables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/adminlte-boot-4/plugins/datatables/dataTables.bootstrap4.js')}}"></script>-->
        <!--
<script src="{{asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
        -->
        <!---Datatables date sorting plugin------------->
        <!--<script src="{{asset('assets/bower_components/datatables.date/js/date-uk.js')}}"></script>-->
        <script>
var ajaxUrl = '<?php echo url('ajax/getlatestnotifications'); ?>';
var markallasread = '<?php echo url('ajax/markallasread'); ?>';
var markasread = '<?php echo url('ajax/markasread'); ?>';
$(function() {
    getNotifications();
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
        'paging': false,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': false,
        'autoWidth': false,
        'responsive': true
//        'columnDefs': [
//            {type: 'date-uk', targets: 4}
//        ]
    });

    $('#dealsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        'responsive': true,
        "pageLength": 5,
        // 'columnDefs': [
        //     {type: 'date-uk', targets: 4}
        // ]
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
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 5}
        ]
    });

    $('#formleadsTable').DataTable({
        "aaSorting": [],
        'paging': false,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': false,
        'autoWidth': false,
        'responsive': true
//        'columnDefs': [
//            {type: 'date-uk', targets: 5}
//        ]
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
    myVar = setInterval(getNotifications, 1 * 60 * 1000);    //
}

function getNotifications() {
//    alert("Hello! - 1minute");


    $.get(ajaxUrl, {'type': 'getLatestnotifications'}, function(result, status) {
//        alert(result);
        var res = eval("(" + result + ")");
        $("#not_menu").html(res.formstable);
        $("#not_count").html(res.not_count);
//        $("#limsg").html(res.limsg);
    });

}
        </script>
    </body>
</html>
