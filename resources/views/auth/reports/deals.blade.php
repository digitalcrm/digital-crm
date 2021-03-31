@extends('layouts.adminlte-boot-4.user')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Reports</h1>
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
                {{-- <div class="card shadow card-primary card-outline">
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a href="{{url('reports/webtolead')}}" class="nav-link">
                                    Web to Lead
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('reports/leads')}}" class="nav-link">
                                    Leads
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('reports/accounts')}}" class="nav-link">
                                    Accounts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('reports/contacts')}}" class="nav-link">
                                    Contacts
                                </a>
                            </li>
                            <li class="nav-item active-bg">
                                <a href="{{url('reports/deals')}}" class="nav-link">
                                    Deals
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('reports/customers')}}" class="nav-link">
                                    Customers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('reports/sales')}}" class="nav-link">
                                    Sales
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('reports.tasks')}}" class="nav-link">
                                    Tasks
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div> --}}
                <!-- /.card -->

            </div>
            <!-- /.col -->
            <div class="col-md-10">
                <div class="card shadow card-primary card-outline shadow">
                    <div class="card-header with-border">
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
                                <a
                                class="nav-link active"
                                id="home-tab"
                                data-toggle="tab"
                                href="#tab_1"
                                role="tab"
                                aria-controls="home" aria-selected="true" onclick="return getFormleads('days');">Day</a>
                            </li>
                            <li class="nav-item">
                                <a
                                class="nav-link"
                                id="profile-tab"
                                data-toggle="tab"
                                href="#tab_2"
                                role="tab"
                                aria-controls="profile" aria-selected="false" onclick="return getFormleads('weeks');">Week</a>
                            </li>
                            <li class="nav-item">
                                <a
                                class="nav-link"
                                id="contact-tab"
                                data-toggle="tab"
                                href="#tab_3"
                                role="tab"
                                aria-controls="contact" aria-selected="false" onclick="return getFormleads('months');">Month</a>
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
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('deals')}}" class="btn btn-sm btn-outline-secondary"><i class="nav-icon fas fa-long-arrow-alt-left"></i></a>
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
<script src="{{asset('assets/bower_components/chart.js/Chart.js')}}"></script>
<script>
                                    var default_form = '<?php echo $data['default_form']; ?>';
//                                        alert(default_form);
                                    var dayurl = "{{url('reports/getDayDeals/{time}/{form_id}')}}";
                                    var weekurl = "{{url('reports/getWeekDeals/{time}/{form_id}')}}";
                                    var monthurl = "{{url('reports/getMonthDeals/{time}/{form_id}')}}";
                                    $(function() {

                                        $(".sidebar-menu li").removeClass("active");
                                        $("#lireports").addClass("active");


                                        getDayDeals(default_form);
                                        $("#timer").val('days');
                                        $("#forms").change(function() {
                                            var id = $(this).val();
                                            if (id > 0) {
                                                var time = $("#timer").val();
//                                                    alert(id + " " + time);
                                                if (time == 'days') {

                                                    getDayDeals(id);
                                                }
                                                if (time == 'weeks') {
                                                    getWeekDeals(id);
                                                }
                                                if (time == 'months') {
                                                    getMonthDeals(id);
                                                }
                                            } else {
                                                alert('Please select form...!');
                                                return false;
                                            }

                                        });
                                    });

                                    function getFormleads(time) {

//                                            alert(time);
                                        $("#timer").val(time);
                                        var id = $("#forms").val();
                                        if (id > 0) {
                                            if (time == 'days') {
                                                getDayDeals(id);
                                            }
                                            if (time == 'weeks') {
                                                getWeekDeals(id);
                                            }
                                            if (time == 'months') {
                                                getMonthDeals(id);
                                            }
                                        } else {
                                            alert('Please select deal stage...!');
                                            return false;
                                        }

                                    }


                                    function getDayDeals(id) {
//                                            alert('getDayDeals Id: ' + id);
                                        $.get(dayurl, {'time': 'day', 'form_id': id}, function(result) {
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

                                    function getMonthDeals(id) {
//                                            alert('getMonthFormleads Id: ' + id);
                                        $.get(monthurl, {'time': 'month', 'form_id': id}, function(result) {
//                                                alert('Months ' + result);
                                            var res = eval("(" + result + ")");
                                            var data = res.formleads;
                                            var label = res.label;
                                            var labels = res.labels;
                                            var canvasId = "monthChart";
                                            var divId = "monthChartdiv";
                                            $("#card-title-months").text(res.label);
                                            barGragh(label, data, labels, canvasId, divId);

                                        });
                                    }

                                    function getWeekDeals(id) {
//                                            alert('getWeekFormleads Id: ' + id);
                                        $.get(weekurl, {'time': 'week', 'form_id': id}, function(result) {
//                                                alert('Weeks ' + result);
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
                                            datasets: [{
                                                    label: label,
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


</script>
@endsection
