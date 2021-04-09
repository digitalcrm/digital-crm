@extends('layouts.adminlte-boot-4.user')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Appointments</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>



    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <div class="row">
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


            <div class="col-md-2">

               @include('includes.adminlte-boot-4.user.report_sidebar')
                <!-- /.card -->

            </div>
            <!-- /.col -->
            <div class="col-md-10">
                <div class="card shadow card-primary card-outline card-primary card-outline">
                    <div class="card-header with-border" style="display: none">
                        <div class="col-lg-3">
                            <select class="form-control" id="forms" name="forms">
                                <?php echo $data['formSelect']; ?>
                            </select>
                        </div>

                    </div>
                    <div class="card-body">
                        <input type="hidden" value="" id="timer" name="timer">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active"
                                    id="home-tab"
                                    data-toggle="tab"
                                    href="#tab_1"
                                    role="tab"
                                    aria-controls="home"
                                    aria-selected="true"
                                    onclick="return getFormAppointments('days');">Day</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    id="profile-tab"
                                    data-toggle="tab"
                                    href="#tab_2"
                                    role="tab"
                                    aria-controls="profil
                                    e" aria-selected="false"
                                    onclick="return getFormAppointments('weeks');">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    id="contact-tab"
                                    data-toggle="tab"
                                    href="#tab_3"
                                    role="tab"
                                    aria-controls="contac
                                    t" aria-selected="false"
                                    onclick="return getFormAppointments('months');">Month</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="home-tab">
                                <label id='card-title-days' class="card-title"></label>
                                <div class="chart" id="dayChartdiv">
                                    <canvas id="dayChart" style="height:230px"></canvas>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="profile-tab">
                                <label id='card-title-weeks' class="card-title"></label>
                                <div class="chart" id="weekChartdiv">
                                    <canvas id="weekChart" style="height:230px"></canvas>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="contact-tab">
                                <label id='card-title-months' class="card-title"></label>
                                <div class="chart" id="monthChartdiv">
                                    <canvas id="monthChart" style="height:230px"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white pull-right text-right">
                        <a
                            href="{{ URL::previous() }}"
                            class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<!-- ChartJS -->
<script src="https://www.chartjs.org/dist/2.7.3/Chart.bundle.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script>
    var default_form = '<?php echo $data['default_form']; ?>';
    var dayurl = "{{url('reports/getDayAppointment/{time}/{form_id}')}}";
    var weekurl = "{{url('reports/getMonthAppointment/{time}/{form_id}')}}";
    var monthurl = "{{url('reports/getWeekAppointment/{time}/{form_id}')}}";
    $(function() {

        getDayAppointments(default_form);
        $("#timer").val('days');
        $("#forms").change(function() {
            var id = $(this).val();
            if (id > 0) {
                var time = $("#timer").val();
                if (time == 'days') {

                    getDayAppointments(id);
                }
                if (time == 'weeks') {
                    getWeekAppointments(id);
                }
                if (time == 'months') {
                    getMonthAppointments(id);
                }
            } else {
                alert('Please select form...!');
                return false;
            }

        });
    });

    function getFormAppointments(time) {

            $("#timer").val(time);
            var id = $("#forms").val();
            if (id > 0) {
                if (time == 'days') {
                    getDayAppointments(id);
                }
                if (time == 'weeks') {
                    getWeekAppointments(id);
                }
                if (time == 'months') {
                    getMonthAppointments(id);
                }
            } else {
                alert('Please select one stage...!');
                return false;
            }

        }


    function getDayAppointments(id) {
        $.get(dayurl, {'time': 'day', 'form_id': id}, function(result) {
            var res = eval("(" + result + ")");
            var data = res.formAppointment;
            var label = res.label;
            var labels = res.labels;
            var canvasId = "dayChart";
            var divId = "dayChartdiv";
            $("#card-title-days").text(res.label);
            barGragh(label, data, labels, canvasId, divId);
        });
    }

    function getMonthAppointments(id) {
        $.get(monthurl, {'time': 'month', 'form_id': id}, function(result) {
            var res = eval("(" + result + ")");
            var data = res.formAppointment;
            var label = res.label;
            var labels = res.labels;
            var canvasId = "monthChart";
            var divId = "monthChartdiv";
            $("#card-title-months").text(res.label);
            barGragh(label, data, labels, canvasId, divId);

        });
    }

    function getWeekAppointments(id) {
        $.get(weekurl, {'time': 'week', 'form_id': id}, function(result) {
            var res = eval("(" + result + ")");
            var data = res.formAppointment;
            var label = res.label;
            var labels = res.labels;
            var canvasId = "weekChart";
            var divId = "weekChartdiv";
            $("#card-title-weeks").text(res.label);
            barGragh(label, data, labels, canvasId, divId);

        });
    }


    function barGragh(label, data, labels, canvasId, divId) {
        var color = Chart.helpers.color;
        var barChartData = {
            labels: labels,
            datasets: [{
                    label: label,
                    backgroundColor: '#3374FF',
                    borderColor: '#3364FF',
                    borderWidth: 1,
                    data: data
                }]

        };

        var ctx = document.getElementById(canvasId).getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                }
            }
        });

    }


</script>

@endsection
