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
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif



                <!-- BAR CHART -->
                @include('includes.adminlte-boot-4.admin.report_sidebar')


                        <div class="col-md-10 float-right">
                            <!-- Custom Tabs -->
                                                <!--
                                                <div class="card shadow card-primary card-outline card-primary card-outline">
                                                    <div class="card-header with-border">
                                                        <div class="col-md-12">
                                                            <div class="col-md-6">
                                                                <h3 class="card-title">Web to Lead Forms</h3>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <select class="form-control" id="selectUser" name="selectUser" >
                                                                    {!!$data['useroptions']!!}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="nav-tabs-custom">
                                                            <ul class="nav nav-tabs">
                                                                <li class="active"><a href="#tab_1" data-toggle="tab" onclick="return dayForms();">Day</a></li>
                                                                <li><a href="#tab_2" data-toggle="tab" onclick="return weekForms();">Week</a></li>
                                                                <li><a href="#tab_3" data-toggle="tab" onclick="return monthForms();">Month</a></li>

                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="tab_1">
                                                                    <label id='card-title-days' class="card-title"></label>
                                                                    <div class="chart" id="dayChartdivForm">
                                                                        <canvas id="dayChartForm" style="height:230px"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="tab_2">
                                                                    <label id='card-title-weeks' class="card-title"></label>
                                                                    <div class="chart" id="weekChartdivForm">
                                                                        <canvas id="weekChartForm" style="height:230px"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="tab_3">
                                                                    <label id='card-title-months' class="card-title"></label>
                                                                    <div class="chart" id="monthChartdivForm">
                                                                        <canvas id="monthChartForm" style="height:230px"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            -->
                                            <!-- /.card -->

                                            <!-- Custom Tabs -->
                                <div class="card shadow card-primary card-outline card-primary card-outline">
                                       <div class="card-header with-border">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h3 class="card-title">Form Leads</h3>
                                                        </div>
                                            <div class="col-md-2">
                                               <select class="form-control" id="selectUser" name="selectUser" >
                                                                {!!$data['useroptions']!!}
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select class="form-control" id="forms" name="forms" >
                                                                {!!$data['formoptions']!!}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="nav-tabs-custom">
                                                        <ul class="nav nav-tabs">
                                                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab" onclick="return dayFormleads();">Day</a></li>
                                                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab" onclick="return weekFormleads();">Week</a></li>
                                                            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab" onclick="return monthFormleads();">Month</a></li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="tab_1">
                                                                <label id='card-title-days' class="card-title"></label>
                                                                <div class="chart" id="dayChartdivFormlead">
                                                                    <canvas id="dayChartFormlead" style="height:230px"></canvas>
                                                                </div>
                                                            </div>
                                                            <!-- /.tab-pane -->
                                                            <div class="tab-pane" id="tab_2">
                                                                <label id='card-title-weeks' class="card-title"></label>
                                                                <div class="chart" id="weekChartdivFormlead">
                                                                    <canvas id="weekChartFormlead" style="height:230px"></canvas>
                                                                </div>
                                                            </div>
                                                            <!-- /.tab-pane -->
                                                            <div class="tab-pane" id="tab_3">
                                                                <label id='card-title-months' class="card-title"></label>
                                                                <div class="chart" id="monthChartdivFormlead">
                                                                    <canvas id="monthChartFormlead" style="height:230px"></canvas>
                                                                </div>
                                                            </div>
                                                            <!-- /.tab-pane -->
                                                        </div>
                                                        <!-- /.tab-content -->
                                                    </div>
                                                    <!-- nav-tabs-custom -->
                                                </div>
                                                <!-- /.card-body -->
                                            <div class="card-footer bg-white pull-right text-right">
                                        <a href="{{url('admin/webtolead')}}" class="btn btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
                                    </div>
											</div>
                                            <!-- /.card -->

                                {{-- </div> --}}
                                <!-- /.card -->
                        </div>

                {{--
                <div class="row">
                    <div class="col-md-12">

                    </div>
                    <!-- /.col -->
                </div> --}}
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
                                                var getUserFormOptions = "{{url('admin/reports/getUserFormOptions')}}";
                                                var formleadsdayurl = "{{url('admin/reports/getDayFormleads')}}";
                                                var formleadsweekurl = "{{url('admin/reports/getWeekFormleads')}}";
                                                var formleadsmonthurl = "{{url('admin/reports/getMonthFormleads')}}";
//                                                var formsdayurl = "{{url('admin/reports/getDayForms')}}";
//                                                var formsweekurl = "{{url('admin/reports/getWeekForms')}}";
//                                                var formsmonthurl = "{{url('admin/reports/getMonthForms')}}";
//                                                var formchart = 'day';
                                                var formleadchart = 'day';
                                                var firstUser = "<?php echo $data['user']; ?>";
                                                var firstForm = "<?php echo $data['form']; ?>";
                                                $(function() {

                                                    $(".lisidebar").removeClass("active");
                                                    $("#lireports").addClass("active");

                                                    getDayFormleads(firstUser, firstForm);

                                                    $("#selectUser").change(function() {

                                                        var user = $(this).val();

                                                        $.get(getUserFormOptions, {'uid': user}, function(result) {

//                                                            alert(result);
                                                            var res = eval("(" + result + ")");
                                                            var formoptions = res.formoptions;
                                                            var form = res.form;
                                                            $("#forms").html(formoptions);

                                                            var form = $("#forms").val();
                                                            if (formleadchart == 'day') {
                                                                getDayFormleads(user, form);
                                                            }
                                                            if (formleadchart == 'week') {
                                                                getWeekFormleads(user, form);
                                                            }
                                                            if (formleadchart == 'month') {
                                                                getMonthFormleads(user, form);
                                                            }

                                                        });




                                                    });

                                                    $("#forms").change(function() {
                                                        var user = $("#selectUser").val();
                                                        var form = $(this).val();
                                                        if (formleadchart == 'day') {
                                                            getDayFormleads(user, form);
                                                        }
                                                        if (formleadchart == 'week') {
                                                            getWeekFormleads(user, form);
                                                        }
                                                        if (formleadchart == 'month') {
                                                            getMonthFormleads(user, form);
                                                        }
                                                    });
                                                });


                                                function dayFormleads() {
                                                    formleadchart = 'day';
                                                    var user = $("#selectUser").val();
                                                    var form = $("#forms").val();
                                                    getDayFormleads(user, form);
                                                }

                                                function getDayFormleads(user, id) {

                                                    $.get(formleadsdayurl, {'uid': user, 'form_id': id}, function(result) {

//                                                        alert(result);
                                                        var res = eval("(" + result + ")");
                                                        var data = res.formleads;
                                                        var label = res.label;
                                                        var labels = res.labels;
                                                        var canvasId = "dayChartFormlead";
                                                        var divId = "dayChartdivFormlead";
                                                        $("#card-title-days").text(res.label);
                                                        barGragh(label, data, labels, canvasId, divId);

                                                    });
                                                }

                                                function monthFormleads() {
                                                    formleadchart = 'month';
                                                    var user = $("#selectUser").val();
                                                    var form = $("#forms").val();
                                                    getMonthFormleads(user, form);
                                                }

                                                function getMonthFormleads(user, id) {
//                                                    alert(user + " " + id);
                                                    $.get(formleadsmonthurl, {'uid': user, 'form_id': id}, function(result) {

//                                                        alert(result);

                                                        var res = eval("(" + result + ")");
                                                        var data = res.formleads;
                                                        var label = res.label;
                                                        var labels = res.labels;
                                                        var canvasId = "monthChartFormlead";
                                                        var divId = "monthChartdiv";
                                                        $("#card-title-months").text(res.label);
                                                        barGragh(label, data, labels, canvasId, divId);

                                                    });
                                                }

                                                function weekFormleads() {
                                                    formleadchart = 'week';
                                                    var user = $("#selectUser").val();
                                                    var form = $("#forms").val();
                                                    getWeekFormleads(user, form);
                                                }


                                                function getWeekFormleads(user, id) {
//                                                    alert(user + " " + id);
                                                    $.get(formleadsweekurl, {'uid': user, 'form_id': id}, function(result) {
//                                                        alert(result);
                                                        var res = eval("(" + result + ")");
                                                        var data = res.formleads;
                                                        var label = res.label;
                                                        var labels = res.labels;
                                                        var canvasId = "weekChartFormlead";
                                                        var divId = "weekChartdivFormlead";
                                                        $("#card-title-weeks").text(res.label);
                                                        barGragh(label, data, labels, canvasId, divId);

                                                    });
                                                }


                                                function barGragh(label, data, labels, canvasId, divId) {
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
                                                        scaleGridLineWidth: 1,
                                                        scaleShowHorizontalLines: true,
                                                        scaleShowVerticalLines: true,
                                                        barShowStroke: true,
                                                        barStrokeWidth: 2,
                                                        barValueSpacing: 5,
                                                        barDatasetSpacing: 1,
                                                        legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
                                                        responsive: true,
                                                        maintainAspectRatio: true
                                                    };

                                                    barChartOptions.datasetFill = false;
                                                    barChart.Bar(barChartData, barChartOptions);
                                                }

                                                /*
                                                 * Forms
                                                 */
                                                /*
                                                 function getDayForms(id) {
                                                 $.get(formsdayurl, {'time': 'day', 'form_id': id}, function(result) {
                                                 var res = eval("(" + result + ")");
                                                 var data = res.formleads;
                                                 var label = res.label;
                                                 var labels = res.labels;
                                                 var canvasId = "dayChartForm";
                                                 var divId = "dayChartdivForm";
                                                 $("#card-title-days").text(res.label);
                                                 barGragh(label, data, labels, canvasId, divId);
                                                 });
                                                 }

                                                 function getWeekForms(id) {
                                                 $.get(formsweekurl, {'time': 'week', 'form_id': id}, function(result) {
                                                 var res = eval("(" + result + ")");
                                                 var data = res.formleads;
                                                 var label = res.label;
                                                 var labels = res.labels;
                                                 var canvasId = "weekChartForm";
                                                 var divId = "weekChartdivForm";
                                                 $("#card-title-weeks").text(res.label);
                                                 barGragh(label, data, labels, canvasId, divId);
                                                 });
                                                 }

                                                 function getMonthForms(id) {
                                                 $.get(formsmonthurl, {'time': 'month', 'form_id': id}, function(result) {
                                                 var res = eval("(" + result + ")");
                                                 var data = res.formleads;
                                                 var label = res.label;
                                                 var labels = res.labels;
                                                 var canvasId = "monthChartForm";
                                                 var divId = "monthChartdivForm";
                                                 $("#card-title-months").text(res.label);
                                                 barGragh(label, data, labels, canvasId, divId);
                                                 });
                                                 }
                                                 */


</script>
@endsection
