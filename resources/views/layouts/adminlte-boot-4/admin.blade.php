<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title','AdminPanel |'.config('app.name')) </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="description" content="Digital CRM">
    <meta name="keywords" content="Digital CRM">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/fontawesome-free/css/all.min.css')}}">
    {{-- google source https://www.bootstrapcdn.com/fontawesome/ --}}
    {{-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> --}}
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
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Noto+Sans|Oswald|Roboto+Slab|Ubuntu&display=swap" rel="stylesheet">
    <!-- datatables bootstrap4-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/r-2.2.1/datatables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/dist/css/custom.css')}}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/select2/css/select2.min.css')}}">
    <!--JS file-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('assets/adminlte-boot-4/plugins/daterangepicker/daterangepicker.css')}}">
	@livewireStyles
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- navbar -->
        @include('includes.adminlte-boot-4.admin.navbar')
        <!-- /.navbar -->

        <!-- aside -->
        @include('includes.adminlte-boot-4.admin.aside')
        <!-- /.aside -->

        <!-- Content yield function -->
        @yield('content')
        <!-- Content yield function -->

        <!-- footer -->
        @include('includes.adminlte-boot-4.admin.footer')
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
    {{-- <script src="{{asset('assets/adminlte-boot-4/plugins/chart.js/Chart.min.js')}}"></script> --}}
    <!-- daterangepicker -->
    <script src="{{asset('assets/adminlte-boot-4/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('assets/adminlte-boot-4/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Sparkline -->
    <script src="{{asset('assets/adminlte-boot-4/plugins/sparklines/sparkline.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{asset('assets/adminlte-boot-4/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('assets/adminlte-boot-4/plugins/jqvmap/maps/jquery.vmap.world.js')}}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{asset('assets/adminlte-boot-4/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
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
    <script src="{{asset('assets/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('assets/adminlte-boot-4/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{asset('assets/adminlte-boot-4/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(function() {
            //    $('.select2').select2();
            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        });
    </script>


    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/r-2.2.1/datatables.min.js"></script>
    <!-- DataTables -->
    {{-- <script src="{{asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    --}}
    <!---Datatables date sorting plugin------------->
    {{-- <script src="{{asset('assets/bower_components/datatables.date/js/date-uk.js')}}"></script> --}}
    <script>
        var ajaxUrl = '<?php echo url('admin/ajax/getlatestnotifications'); ?>';
        var markallasread = '<?php echo url('ajax/markallasread'); ?>';
        var markasread = '<?php echo url('ajax/markasread'); ?>';
        $(function() {
            // getNotifications();
            callNotification();

            $('#accountsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 4
                }]
            });

            $('#contactsTable').DataTable({
                //        "aaSorting": [],
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 4
                }]
            });

            $('#leadsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true,
				'responsive': true,
                // 'columnDefs': [{
                //     type: 'date-uk',
                //     targets: 4
                // }]
            });

            $('#dealsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'responsive': true,
                // 'columnDefs': [{
                //     type: 'date-uk',
                //     targets: 4
                // }]
            });

            $('#customersTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 3
                }]
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
                // 'columnDefs': [
                //     {type: 'date-uk', targets: 5}
                // ]
            });

            $('#formleadsTable').DataTable({
                "aaSorting": [],
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 5
                }]
            });
            $('#latestformleadsTable').DataTable({
                "aaSorting": [],
                ////        'paging': true,
                ////        'lengthChange': true,
                ////        'searching': true,
                ////        'ordering': false,
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
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 3
                }]
            });

            $('#subusersTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 2
                }]
            });

            $('#usersTable').DataTable({
                'paging': false,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 4
                }]
            });

            $('#example1').DataTable({
                'paging': false,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': false,
                'autoWidth': true,
            });

            $('#example2').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true,
            });

            // $('#exampletable').DataTable({
            //     'paging': true,
            //     'lengthChange': true,
            //     'searching': true,
            //     'ordering': false,
            //     'info': true,
            //     'autoWidth': false,
            // });

            $('#latestdealsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 4
                }],
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
            $.get(markallasread, {
                'type': 'Markallasread'
            }, function(result, status) {
                //                    alert(result);
                //                    var res = eval("(" + result + ")");
                //                    $("#not_menu").html(res.formstable);
                //                    $("#not_count").html(res.not_count);
                //                    $("#limsg").html(res.limsg);
                $.get(ajaxUrl, {
                    'type': 'getLatestnotifications'
                }, function(result, status) {
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
            $.get(markasread, {
                'id': id
            }, function(result, status) {
                //        alert(result);
            });

        }

        var myVar;

        function callNotification() {
            myVar = setInterval(getNotifications, 1 * 60 * 1000);
        }

        function getNotifications() {
            //    alert("Hello! - 1minute");


            $.get(ajaxUrl, {
                'type': 'getLatestnotifications'
            }, function(result, status) {
                //                    alert(result);
                var res = eval("(" + result + ")");
                $("#not_menu").html(res.formstable);
                $("#not_count").html(res.not_count);
                // $("#limsg").html(res.limsg);
            });

        }
    </script>
</body>

</html>
