@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style class="cp-pen-styles">
        #funnelPanel {
            width: auto;
            margin: 0 auto;
        }

    </style>
    @php
        $global_reports = $data['global_reports'];
    @endphp
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <livewire:dashboard.info-box />
            <!-- Main row -->
            <div class="row">
                <!--D3 Funnel  sales-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move; border-bottom-none">
                            <h3 class="card-title p-3">
                                Sales Funnel
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="d3funnelTime" name="d3funnelTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="text-bold text-center">
                                    <p id="wonAmount">Total Sales</p>
                                </div>
                                <div id="funnelPanel">
                                    <div id="funnelContainer">
                                        <div id="funnel"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Funnel Chart-->
                <!--            <div class="col-lg-6">
                                <div class="card card-info">
                                    <div class="card-header with-border">
                                        <div class="col-lg-8 pull-left">
                                            <h3 class="card-title">Sales Funnel</h3>
                                        </div>
                                        <div class="col-lg-4">
                                            <select class="form-control" id="funnelTime" name="funnelTime">
                                                <option value="day">Day</option>
                                                <option value="week">Week</option>
                                                <option value="month">Month</option>
                                                <option value="year" selected>Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div>
                                            <h4 id="wonAmount"></h4>
                                        </div>
                                        <div id="canvas-funnel" style="width:80%;margin-left: 85px;">
                                            <canvas id="funnelchart" height=""></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>-->


                <!--Latest-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Latest Deals <span
                                    class="badge badge-secondary"><?php echo $data['latestDeals']['total']; ?></span>
                            </h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="tab-content p-0">
                                <?php echo $data['latestDeals']['table']; ?>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <!--Deals by Deal Stage-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Deals in Stage <span class="badge badge-secondary" id="dealstageTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="dealstageTime" name="dealstageTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div id="div-dealstage" style="">
                                    <canvas id="canvas-dealstage"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Leads Reports-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Leads <span class="badge badge-secondary" id="leadsTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="leadsTime" name="leadsTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div id="div-leads">
                                    <canvas id="canvas-leads"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Accounts-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Accounts <span class="badge badge-secondary" id="accountsTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="accountsTime" name="accountsTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div id="div-accounts">
                                    <canvas id="canvas-accounts"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Contacts-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Contacts <span class="badge badge-secondary" id="contactsTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="contactsTime" name="contactsTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div id="div-contacts">
                                    <canvas id="canvas-contacts"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Deals-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Deals <span class="badge badge-secondary" id="dealsTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="dealsTime" name="dealsTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                                <li class="nav-item">
                                    <select class="form-control" id="salesStage" name="salesStage">
                                        <?php echo $data['salesfunnelOptions']; ?>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div id="div-deals">
                                    <canvas id="canvas-deals"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Customers-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Customers <span class="badge badge-secondary" id="customersTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="customersTime" name="customersTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div id="div-customers">
                                    <canvas id="canvas-customers"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Sales-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Sales <span class="badge badge-secondary" id="salesTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="salesTime" name="salesTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div id="div-sales">
                                    <canvas id="canvas-sales"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Web to Lead-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Web to Lead <span class="badge badge-secondary" id="formleadsTotal"></span>
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <select class="form-control" id="formleadsTime" name="formleadsTime">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month">Month</option>
                                        <option value="year" selected>Year</option>
                                    </select>
                                </li>
                                <li class="nav-item">
                                    <select class="form-control fc-custom" id="forms" name="forms">
                                        <?php echo $data['form_Select']; ?>
                                    </select>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div id="div-formleads">
                                    <canvas id="canvas-formleads"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Global Reports-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Global Reports
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="tab-content p-0">
                                <table id="globalreports" class="table">
                                    <tbody>
                                        <tr>
                                            <td>Sub Users</td>
                                            <td><?php echo $global_reports['subusers'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Accounts</td>
                                            <td><?php echo $global_reports['accounts'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Contacts</td>
                                            <td><?php echo $global_reports['contacts'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Leads</td>
                                            <td><?php echo $global_reports['leads'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Deals</td>
                                            <td><?php echo $global_reports['deals'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Won</td>
                                            <td><?php echo $global_reports['won'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Lost</td>
                                            <td><?php echo $global_reports['lost'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Customers</td>
                                            <td><?php echo $global_reports['customers'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Sales</td>
                                            <td><?php echo $global_reports['sales'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Forms</td>
                                            <td><?php echo $global_reports['forms'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Form Leads</td>
                                            <td><?php echo $global_reports['formleads'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Events</td>
                                            <td><?php echo $global_reports['events'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Territory</td>
                                            <td><?php echo $global_reports['territory'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>


                <!--Calendar-->
                <div class="col-lg-6">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Calendar
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div id="calendarDiv"></div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <!--Sub Users-->
                <div class="col-lg-12">
                    <div class="card shadow card-primary card-outline" style="position: relative; left: 0px; top: 0px;">
                        <div class="card-header d-flex p-0 ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title p-3">
                                Sub Users <span class="badge badge-secondary">{!!$data['subusers']['total']!!}</span>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="tab-content p-0">
                                {!!$data['subusers']['table']!!}
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- ChartJS -->

<script src="https://www.chartjs.org/dist/2.7.3/Chart.bundle.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script>
    function barchart(data, labels, title, canvas) {

        var color = Chart.helpers.color;
        var barChartData = {
            labels: labels,
            datasets: [{
                label: title,
                backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data: data
            }]

        };

        var ctx = document.getElementById(canvas).getContext('2d'); //'canvas-dealstage'
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

<script src="{{ asset('assets/funnelChart/dist/chart.funnel.bundled.js') }}"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script>
    var defaultsalesstage = "<?php echo $data['default_salesfunnel']; ?>";
    var defaultform = "<?php echo $data['form_id']; ?>";
    //    alert(defaultform);
    var form_id = "{{ $data['form_id'] }}";
    var viewsurl = "{{ url('dashboard/viewsreport') }}";
    var getDealsStage = "{{ url('dashboard/getDealsStage') }}";
    var getSalesFunnel = "{{ url('dashboard/getSalesFunnel') }}";
    var getSalesFunnelD3 = "{{ url('dashboard/getSalesFunnelD3') }}";
    var getleads = "{{ url('dashboard/getLeadsData') }}";
    var getaccounts = "{{ url('dashboard/getAccountsData') }}";
    var getcontacts = "{{ url('dashboard/getContactsData') }}";
    var getcustomers = "{{ url('dashboard/getCustomersData') }}";
    var getsales = "{{ url('dashboard/getSalesData') }}";

    var dayurlDeal = "{{ url('reports/getDayDeals/{time}/{form_id}') }}";
    var weekurlDeal = "{{ url('reports/getWeekDeals/{time}/{form_id}') }}";
    var monthurlDeal = "{{ url('reports/getMonthDeals/{time}/{form_id}') }}";
    var yearurlDeal = "{{ url('reports/getYearDeals/{time}/{form_id}') }}";

    var dayurlFormleads = "{{ url('reports/getDayFormleads/{time}/{form_id}') }}";
    var weekurlFormleads = "{{ url('reports/getWeekFormleads/{time}/{form_id}') }}";
    var monthurlFormleads = "{{ url('reports/getMonthFormleads/{time}/{form_id}') }}";
    var yearurlFormleads = "{{ url('reports/getYearFormleads/{time}/{form_id}') }}";


    $(function () {
        $(".sidebar-menu li").removeClass("active");
        $("#lidashboard").addClass("active");
        //        salesfunnel('year');
        dealstage('year');

        getLeads('year');
        getAccounts('year');
        getContacts('year');
        getDeals('year', defaultsalesstage);
        getCustomers('year');
        getSales('year');
        getFormleads('year', defaultform);
        eventCalendar();
        salesfunnelD3('year')

        $("#funnelTime").change(function () {
            var time = $(this).val();
            salesfunnel(time);
        });

        $("#d3funnelTime").change(function () {
            var time = $(this).val();
            salesfunnelD3(time);
        });

        $("#dealstageTime").change(function () {
            var time = $(this).val();
            dealstage(time);
        });

        $("#leadsTime").change(function () {
            var time = $(this).val();
            getLeads(time);
        });

        $("#accountsTime").change(function () {
            var time = $(this).val();
            getAccounts(time);
        });

        $("#contactsTime").change(function () {
            var time = $(this).val();
            getContacts(time);
        });

        $("#dealsTime").change(function () {
            var time = $(this).val();
            var salesstage = $("#salesStage").val();
            getDeals(time, salesstage);
        });

        $("#salesStage").change(function () {
            var time = $("#dealsTime").val();
            var salesstage = $(this).val();
            getDeals(time, salesstage);
        });

        $("#customersTime").change(function () {
            var time = $(this).val();
            getCustomers(time);
        });

        $("#salesTime").change(function () {
            var time = $(this).val();
            getSales(time);
        });

        $("#formleadsTime").change(function () {
            var time = $(this).val();
            var form = $("#forms").val();
            //            alert(time + ' ' + form);
            getFormleads(time, form);
        });

        $("#forms").change(function () {

            var time = $("#formleadsTime").val();
            var form = $(this).val();
            //            alert(time + ' ' + form);
            getFormleads(time, form);
        });
    });

    function dealstage(time) {
        $.get(getDealsStage, {
            'time': time
        }, function (result) {
            //            alert(result);
            var res = eval("(" + result + ")");
            var data = res.dataset;
            var labels = res.labels;
            var title = res.title;
            var canvas = "canvas-dealstage";
            $("#dealstageTotal").text(res.total);
            $("#div-dealstage").html('<canvas id="canvas-dealstage" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function salesfunnel(time) {
        //    alert(time);
        $.get(getSalesFunnel, {
            'time': time
        }, function (result) {
            //        alert(result);
            var res = eval("(" + result + ")");
            //            alert(res.wonAmount);
            var data = res.dataset;
            var color = res.color;
            var labels = res.labels;
            var title = res.title;
            var wonAmount = res.wonAmount;
            $("#wonAmount").html(wonAmount);
            funnelChart(data, labels, color, title);
        });
    }

    function getLeads(time) {
        $.get(getleads, {
            'time': time
        }, function (result) {
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-leads";
            $("#leadsTotal").text(res.total);
            $("#div-leads").html('<canvas id="canvas-leads" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getAccounts(time) {
        $.get(getaccounts, {
            'time': time
        }, function (result) {
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-accounts";
            $("#accountsTotal").text(res.total);
            $("#div-accounts").html('<canvas id="canvas-accounts" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getContacts(time) {
        //        alert(time);
        $.get(getcontacts, {
            'time': time
        }, function (result) {
            //            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-contacts";
            $("#contactsTotal").text(res.total);
            $("#div-contacts").html('<canvas id="canvas-contacts" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getDeals(time, salesstage) {
        //        alert();

        var url = '';
        if (time == 'day') {
            url = dayurlDeal;
        }
        if (time == 'week') {
            url = weekurlDeal;
        }
        if (time == 'month') {
            url = monthurlDeal;
        }
        if (time == 'year') {
            url = yearurlDeal;
        }

        //        alert(time + ' ' + salesstage + ' ' + url);

        $.get(url, {
            'time': time,
            'form_id': salesstage
        }, function (result) {
            //            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-deals";
            $("#dealsTotal").text(res.total);
            $("#div-deals").html('<canvas id="canvas-deals" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getCustomers(time) {
        //        alert(time);
        $.get(getcustomers, {
            'time': time
        }, function (result) {
            //            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-customers";
            $("#customersTotal").text(res.total);
            $("#div-customers").html('<canvas id="canvas-customers" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getSales(time) {
        $.get(getsales, {
            'time': time
        }, function (result) {
            //            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-sales";
            $("#salesTotal").html(res.total);
            $("#div-sales").html('<canvas id="canvas-sales" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getFormleads(time, form) {
        //        alert(time + ' ' + form);

        var url = '';
        if (time == 'day') {
            url = dayurlFormleads;
        }
        if (time == 'week') {
            url = weekurlFormleads;
        }
        if (time == 'month') {
            url = monthurlFormleads;
        }
        if (time == 'year') {
            url = yearurlFormleads;
        }

        //        alert(time + ' ' + salesstage + ' ' + url);

        $.get(url, {
            'time': time,
            'form_id': form
        }, function (result) {
            //            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-formleads";
            $("#formleadsTotal").text(res.total);
            $("#div-formleads").html('<canvas id="canvas-formleads" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function funnelChart(data, labels, color, title) {
        //    alert('data ' + data);
        //    alert('labels ' + labels);
        //    alert('color ' + color);
        $("#canvas-funnel").html('<canvas id="funnelchart" height="180"></canvas>');
        var config = {
            type: 'funnel',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: color,
                    hoverBackgroundColor: color
                }],
                labels: labels
            },
            options: {
                responsive: true,
                sort: 'desc',
                legend: {
                    position: 'right'
                },
                title: {
                    position: 'top',
                    fontFamily: "sans-serif",
                    fontSize: 20,
                    display: true,
                    text: title //'Chart.js Funnel Chart'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        };
        var ctx = document.getElementById("funnelchart").getContext("2d");
        window.myDoughnut = new Chart(ctx, config);
    }


    function eventCalendar() {
        var events = '';
        var url = '{{ url("ajax/getUserEvents") }}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                uid: 0
            },
            success: function (data) {
                //                alert(data);
                events = eval('(' + data + ')');
                //                alert(data);
                //                $("#user").html(data);



                /* initialize the calendar
                 -----------------------------------------------------------------*/
                //Date for the calendar events (dummy data)
                var date = new Date();
                var d = date.getDate(),
                    m = date.getMonth(),
                    y = date.getFullYear();
                $('#calendarDiv').fullCalendar({
                    height: 496,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    buttonText: {
                        today: 'today',
                        month: 'month',
                        week: 'week',
                        day: 'day'
                    },
                    events: events,
                    editable: false,
                    droppable: false, // this allows things to be dropped onto the calendar !!!
                    drop: function (date,
                    allDay) { // this function is called when something is dropped

                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');

                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;
                        copiedEventObject.backgroundColor = $(this).css('background-color');
                        copiedEventObject.borderColor = $(this).css('border-color');

                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                        // is the "remove after drop" checkcard checked?
                        if ($('#drop-remove').is(':checked')) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }

                    }
                });
            }
        });
    }

</script>
<script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="https://cdn.rawgit.com/jakezatecky/d3-funnel/0.3.2/d3-funnel.js?1"></script>
<script>
    function salesfunnelD3(time) {
        //        alert(time);
        $.get(getSalesFunnelD3, {
            'time': time
        }, function (result) {
            //            alert(result);
            var res = eval("(" + result + ")");
            var data = res.dataset;
            //            var color = res.color;
            //            var labels = res.labels;
            //            var title = res.title;
            var wonAmount = res.wonAmount;
            $("#wonAmount").html(wonAmount);
            d3FunnelChart(data, "#funnelContainer", "#funnelPanel");
        });
    }

    function d3FunnelChart(data, container, panel) {



        //        var data = [
        //            ['Impression', '12000', '#008080', '#080800'],
        //            ['Traffic', '4000', '#702963'],
        //            ['Lead', '2500', '#ff634d', '#6f34fd'],
        //            ['Customer', '150', '#3f2ae2', '#07fff0']];


        var options = {
            width: 360,
            height: 365,
            bottomWidth: 1 / 4,
            bottomPinch: 2, // How many sections to pinch
            isCurved: false, // Whether the funnel is curved
            curveHeight: 0, // The curvature amount
            fillType: "gradient", // Either "solid" or "gradient"
            isInverted: false, // Whether the funnel is inverted
            hoverEffects: true, // Whether the funnel has effects on hover
            fontSize: '18px',
        };


        var funnel = new D3Funnel(data, options);
        funnel.draw(container);

        $(window).on("resize", function () {
            var width = $(panel).width();
            //$( "#funnelContainer" ).css( "width", width);
            options.width = width;
            var funnel = new D3Funnel(data, options);
            funnel.draw(container);
        });
    }

</script>
@endsection
