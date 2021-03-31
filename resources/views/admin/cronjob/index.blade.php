@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Content Header (Page header) -->

        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-12">

                <div class="card shadow card-primary card-outline">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            Cron job
                        </h3>
                    </div> 
                    <!--/.card-header--> 
                    <div class="card-body">

                        <table class="table table-active">
                            <thead>
                                <tr>
                                    <th>
                                        Minute 
                                    </th>
                                    <th>
                                        Hour 	
                                    </th>
                                    <th>
                                        Day
                                    </th>
                                    <th>
                                        Month 
                                    </th>
                                    <th>
                                        Weekday
                                    </th>
                                    <th>
                                        Command
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <td>
                                * 	
                            </td>
                            <td>
                                * 	
                            </td>
                            <td>
                                * 	
                            </td>
                            <td>
                                * 	
                            </td>
                            <td>
                                * 	
                            </td>
                            <td>
                                /usr/local/bin/php /home/lifecareayurveda/public_html/crm/artisan schedule:run 
                            </td>
                            </tbody>
                        </table>
                        <br>
                        <h4>CronJob is set for every minute</h4>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#licronjobs").addClass("active");

    });


</script>
@endsection