<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Bare - Start Bootstrap Template</title>
        <!-- Bootstrap core CSS -->
        <link href="{{asset('assets/bootstrap4/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

        <!--Datatables-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    </head>
    <body>

        <!-- header -->
        @include('includes.user.bootstrap4.header')

        <!-- Content yield function -->
        @yield('content')
        <!-- Content yield function -->

        <!-- Bootstrap core JavaScript -->
        <script src="{{asset('assets/bootstrap4/vendor/jquery/jquery.slim.min.js')}}"></script>
        <script src="{{asset('assets/bootstrap4/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

        <!--Datatables-->
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script>
$(document).ready(function() {
    $('#example').DataTable();

    $('#subusersTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
    });

});
        </script>
    </body>

</html>
