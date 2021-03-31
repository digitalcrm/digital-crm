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
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
        <!--JS file-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('assets/bower_components/select2/dist/css/select2.min.css')}}">
		<!--Custom Css file--->
        <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <!-- header -->
            @include('includes.admin.header')
            <!-- /.header -->

            <!-- Left side column. contains the logo and sidebar -->
            <!-- sidebar: style can be found in sidebar.less -->
            @include('includes.admin.main-sidebar')
            <!-- /.sidebar -->

            <!-- Content yield function -->
            @yield('content')
            <!-- Content yield function -->

            <!-- footer -->
            @include('includes.admin.footer')
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
        <!-- Select2 -->
        <script src="{{asset('assets/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
        <!-- fullCalendar -->
        <script src="{{asset('assets/bower_components/moment/moment.js')}}"></script>
        <script src="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
        <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>

        <script>
$(function() {
    //Initialize Select2 Elements
    $('.select2').select2()
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
$(function() {
    $('#accountsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 4}
        ]
    })
    $('#contactsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 4}
        ]
    })
    $('#leadsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 4}
        ]
    })
    $('#dealsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 4}
        ]
    })
    $('#customersTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 3}
        ]
    })
    $('#salesTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 3}
        ]
    })
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
    })
    $('#formsleadsTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 5}
        ]
    })
    $('#usersTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 4}
        ]
    })
    $('#example1').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
    })
    $('#example2').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
    })
    $('#example12').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'columnDefs': [
            {type: 'date-uk', targets: 4}
        ]
    });

})
        </script>
    </body>
</html>
