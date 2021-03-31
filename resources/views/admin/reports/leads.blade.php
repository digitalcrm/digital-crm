@extends('layouts.adminlte-boot-4.admin')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4">
                    <h1>Reports</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
                @if(session('success'))
                <div class='alert alert-success'>
                    {{session('success')}}
                </div>
                @endif

                @if(session('error'))
                <div class='alert alert-daner'>
                    {{session('error')}}
                </div>
                @endif

                <!-- BAR CHART -->

                @include('includes.adminlte-boot-4.admin.report_sidebar')
                        <div class="col-lg-10 float-right">
                            <div class="card shadow card-primary card-outline card-primary card-outline card-primary card-outline">
                                <div class="card-header with-border">
                                    <div class="col-lg-10 float-left">
                                        <h3 class="card-title">Leads</h3>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <select class="form-control" id="selectUser" name="selectUser" style="">
                                            {!!$data['useroptions']!!}
                                        </select>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Custom Tabs -->
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab" onclick="return getDay();">Day</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab" onclick="return getWeek();">Week</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab" onclick="return getMonth();">Month</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1">
                                                <label id='card-title-days' class="card-title"></label>
                                                <div class="chart" id="dayChartdiv">
                                                    <canvas id="dayChart" style="height:230px"></canvas>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="tab_2">
                                                <label id='card-title-weeks' class="card-title"></label>
                                                <div class="chart" id="weekChartdiv">
                                                    <canvas id="weekChart" style="height:230px"></canvas>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="tab_3">
                                                <label id='card-title-months' class="card-title"></label>
                                                <div class="chart" id="monthChartdiv">
                                                    <canvas id="monthChart" style="height:230px"></canvas>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                </div>
							<div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('admin/leads')}}" class="btn btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                    </div>
                            </div>

                    <!-- /.card-body -->


                </div>
                <!-- /.card -->
            <!-- /.card -->


            <div class="row">
                <div class="col-lg-12">

                </div>
                <!-- /.col -->
            </div>
        </div>
</div>
<!-- /.row -->
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- ChartJS -->
<script src="{{asset('assets/bower_components/chart.js/Chart.js')}}"></script>
<script>
                                                /**/
                                                var dayurl = "{{url('admin/reports/getDayLeads')}}";
                                                var weekurl = "{{url('admin/reports/getWeekLeads')}}";
                                                var monthurl = "{{url('admin/reports/getMonthLeads')}}";
                                                var firstUser = "<?php echo $data['user']; ?>";
                                                var chart = 'day';
                                                $(function() {
                                                    $(".lisidebar").removeClass("active");
                                                    $("#lireports").addClass("active");
//                                                alert('hello .........');
                                                    getDayLeads(firstUser);

                                                    $("#selectUser").change(function() {
                                                        var user = $(this).val();
                                                        if (chart == 'day') {
                                                            getDayLeads(user);
                                                        }
                                                        if (chart == 'week') {
                                                            getWeekLeads(user);
                                                        }
                                                        if (chart == 'month') {
                                                            getMonthLeads(user);
                                                        }
                                                    });
                                                });

                                                function getDay() {
                                                    chart = 'day';
                                                    var user = $("#selectUser").val();
                                                    getDayLeads(user);
                                                }

                                                function getDayLeads(user) {
//                                            alert(user);
                                                    $.get(dayurl, {'time': 'day', 'uid': user}, function(result) {
//                                                alert('Days ' + result);
                                                        var res = eval("(" + result + ")");
                                                        var data = res.formleads;
                                                        var label = res.label;
                                                        var labels = res.labels;
                                                        var canvasId = "dayChart";
                                                        var divId = "dayChartdiv";
                                                        $("#card-title-days").text(res.label);
                                                        barGragh(label, data, labels, canvasId, divId);

                                                    });
                                                }

                                                function getMonth() {
                                                    chart = 'month';
                                                    var user = $("#selectUser").val();
                                                    getMonthLeads(user);
                                                }

                                                function getMonthLeads(user) {
                                                    $.get(monthurl, {'time': 'month', 'uid': user}, function(result) {
//                                        alert('Months ' + result);
                                                        var res = eval("(" + result + ")");
                                                        var data = res.formleads;
                                                        var label = res.label;
                                                        var labels = res.labels;
                                                        var canvasId = "monthChart";
                                                        var divId = "monthChartdiv";
                                                        $("#card-title-months").text(res.label);
                                                        barGragh(label, data, labels, canvasId, divId);

//        alert(barChartOptions);
                                                    });
                                                }

                                                function getWeek() {
                                                    chart = 'week';
                                                    var user = $("#selectUser").val();
                                                    getWeekLeads(user);
                                                }

                                                function getWeekLeads(user) {
                                                    $.get(weekurl, {'time': 'week', 'uid': user}, function(result) {
//                                        alert(result);
//        var res = eval("(" + result + ")");
                                                        var res = eval("(" + result + ")");
                                                        var data = res.formleads;
                                                        var label = res.label;
                                                        var labels = res.labels;
                                                        var canvasId = "weekChart";
                                                        var divId = "weekChartdiv";
                                                        $("#card-title-weeks").text(res.label);
                                                        barGragh(label, data, labels, canvasId, divId);

                                                    });
                                                }


                                                function barGragh(label, data, labels, canvasId, divId) {
//                                    alert(label + " " + data + " " + labels + " " + canvasId + " " + divId);
                                                    $('#' + divId).html('<canvas id="' + canvasId + '" style="height:230px"></canvas>');
                                                    var areaChartData = {
                                                        labels: labels,
                                                        datasets: [{label: label,
                                                                data: data,
                                                            }]
                                                    };

                                                    var barChartCanvas = $('#' + canvasId).get(0).getContext('2d');
                                                    var barChart = new Chart(barChartCanvas);
                                                    var barChartData = areaChartData;
                                                    barChartData.datasets[0].fillColor = '#66a3ff';
                                                    barChartData.datasets[0].strokeColor = '#66a3ff';
                                                    barChartData.datasets[0].pointColor = '#66a3ff';
                                                    var barChartOptions = {
                                                        scaleBeginAtZero: true,
                                                        scaleShowGridLines: true,
                                                        scaleGridLineColor: 'rgba(0,0,0,.05)',
                                                        scaleGridLineWidth: 1, scaleShowHorizontalLines: true,
                                                        scaleShowVerticalLines: true,
                                                        barShowStroke: true,
                                                        barStrokeWidth: 2, barValueSpacing: 5, barDatasetSpacing: 1, legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
                                                        responsive: true,
                                                        maintainAspectRatio: true
                                                    };

                                                    barChartOptions.datasetFill = false;
                                                    barChart.Bar(barChartData, barChartOptions);
                                                }


</script>
@endsection
