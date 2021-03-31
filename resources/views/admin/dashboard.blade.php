@extends('layouts.adminlte-boot-4.admin')

@section('content')
<style class="cp-pen-styles">
    #funnelPanel {
        width: 400px;
        margin: 0 auto;
    }
    /*        #funnelPanel text {
                font-weight: 900;
            }*/
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content mt-2 mx-1">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">

            <div class="col-lg-12">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="col-lg-10 float-left">
                            <h3 class="card-title">Reports</h3>
                        </div>
                        <div class="col-lg-2 float-right">
                            <select class="form-control" id="reportsYear" name="reportsYear">
                                {!!$data['yearOptions']!!}
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0" id='yearData'>
                        {!!$data['yearData']!!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="col-lg-6 float-left">
                            <h3 class="card-title">Sales Funnel</h3>
                        </div>
                        <div class="col-lg-6 float-right">
                            <select class="form-control" id="d3funnelTime" name="d3funnelTime">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year" selected>Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body" id='funnelDiv'>
                        <div class="text-bold">
                            <h4 id="wonAmount"></h4>
                        </div>
                        <div id="funnelPanel">
                            <div id="funnelContainer">
                                <div id="funnel"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="col-lg-8 float-left">
                            <h3 class="card-title">Deal Stage</h3>
                        </div>
                        <div class="col-lg-4 float-right">
                            <select class="form-control" id="dealstageTime" name="dealstageTime">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year" selected>Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="div-dealstage">
                            <canvas id="canvas-dealstage" ></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!--Leads Reports-->
            <div class="col-lg-6">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="col-lg-8 float-left">
                            <h3 class="card-title">Leads</h3>
                        </div>
                        <div class="col-lg-4 float-right">
                            <select class="form-control" id="leadsTime" name="leadsTime">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year" selected>Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="div-leads">
                            <canvas id="canvas-leads" ></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!--Accounts-->
            <div class="col-lg-6">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="col-lg-8 float-left">
                            <h3 class="card-title">Accounts</h3>
                        </div>
                        <div class="col-lg-4 float-right">
                            <select class="form-control" id="accountsTime" name="accountsTime">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year" selected>Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="div-accounts">
                            <canvas id="canvas-accounts" ></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <!--Contacts-->
            <div class="col-lg-6">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="col-lg-8 float-left">
                            <h3 class="card-title">Contacts</h3>
                        </div>
                        <div class="col-lg-4 float-right">
                            <select class="form-control" id="contactsTime" name="contactsTime">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year" selected>Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="div-contacts">
                            <canvas id="canvas-contacts" ></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <!--Deals-->
            <div class="col-lg-6">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-4 float-left">
                                <h3 class="card-title">Deals</h3>
                            </div>
                            <div class="col-lg-4">
                                <select class="form-control" id="dealsTime" name="dealsTime">
                                    <option value="day">Day</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                    <option value="year" selected>Year</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <select class="form-control" id="salesStage" name="salesStage">
                                    <?php echo $data['salesfunnelOptions']; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="div-deals">
                            <canvas id="canvas-deals" ></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!--Customers-->
            <div class="col-lg-6">
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <div class="col-lg-8 float-left">
                            <h3 class="card-title">Customers</h3>
                        </div>
                        <div class="col-lg-4 float-right">
                            <select class="form-control" id="customersTime" name="customersTime">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year" selected>Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="div-customers">
                            <canvas id="canvas-customers" ></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var defaultsalesstage = "<?php echo $data['default_salesfunnel']; ?>";
    var getDealsStage = "{{url('admin/dashboard/getDealsStage')}}";
    var getReportsYearData = "{{url('admin/dashboard/getReportsYearData')}}";

    var getYearLeads = "{{url('admin/reports/getYearLeads')}}";
    var getWeekLeads = "{{url('admin/reports/getWeekLeads')}}";
    var getMonthLeads = "{{url('admin/reports/getMonthLeads')}}";
    var getDayLeads = "{{url('admin/reports/getDayLeads')}}";

    var getYearAccounts = "{{url('admin/reports/getYearAccounts')}}";
    var getWeekAccounts = "{{url('admin/reports/getWeekAccounts')}}";
    var getMonthAccounts = "{{url('admin/reports/getMonthAccounts')}}";
    var getDayAccounts = "{{url('admin/reports/getDayAccounts')}}";

    var getYearContacts = "{{url('admin/reports/getYearContacts')}}";
    var getWeekContacts = "{{url('admin/reports/getWeekContacts')}}";
    var getMonthContacts = "{{url('admin/reports/getMonthContacts')}}";
    var getDayContacts = "{{url('admin/reports/getDayContacts')}}";

    var getYearDeals = "{{url('admin/reports/getYearDeals/{time}/{form_id}/{uid}')}}";
    var getWeekDeals = "{{url('admin/reports/getWeekDeals/{time}/{form_id}/{uid}')}}";
    var getMonthDeals = "{{url('admin/reports/getMonthDeals/{time}/{form_id}/{uid}')}}";
    var getDayDeals = "{{url('admin/reports/getDayDeals/{time}/{form_id}/{uid}')}}";

    var getYearCustomers = "{{url('admin/reports/getYearCustomers')}}";
    var getWeekCustomers = "{{url('admin/reports/getWeekCustomers')}}";
    var getMonthCustomers = "{{url('admin/reports/getMonthCustomers')}}";
    var getDayCustomers = "{{url('admin/reports/getDayCustomers')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lidashboard").addClass("active");

        salesfunnelD3('year', 'All');
        dealstage('year', 'All');
        getLeads('year', 'All');
        getAccounts('year', 'All');
        getContacts('year', 'All');
        getDeals('year', defaultsalesstage, 'All');
        getCustomers('year', 'All');

        $("#reportsYear").change(function() {
            var year = $(this).val();
            $.get(getReportsYearData, {'year': year}, function(result) {
                $("#yearData").html(result);
            });
        });

        $("#d3funnelTime").change(function() {
            var time = $(this).val();
            salesfunnelD3(time, 'All');
        });

        $("#dealstageTime").change(function() {
            var time = $(this).val();
            dealstage(time, 'All');
        });

        $("#leadsTime").change(function() {
            var time = $(this).val();
            getLeads(time, 'All');
        });

        $("#accountsTime").change(function() {
            var time = $(this).val();
            getAccounts(time, 'All');
        });

        $("#contactsTime").change(function() {
            var time = $(this).val();
            getContacts(time, 'All');
        });

        $("#dealsTime").change(function() {
            var time = $(this).val();
            var salesstage = $("#salesStage").val();
            getDeals(time, salesstage, 'All');
        });

        $("#salesStage").change(function() {
            var time = $("#dealsTime").val();
            var salesstage = $(this).val();
            getDeals(time, salesstage, 'All');
        });

        $("#customersTime").change(function() {
            var time = $(this).val();
            getCustomers(time);
        });

    });


    function dealstage(time, user) {
//        alert(time + " " + user);
        $.get(getDealsStage, {'time': time, 'uid': 'All'}, function(result) {
//            alert(result);
            var res = eval("(" + result + ")");
            var data = res.dataset;
            var labels = res.labels;
            var title = res.title;
            var canvas = "canvas-dealstage";
            $("#div-dealstage").html('<canvas id="canvas-dealstage" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getLeads(time, user) {


        var url = '';
        if (time == 'day') {
            url = getDayLeads;
        }
        if (time == 'week') {
            url = getWeekLeads;
        }
        if (time == 'month') {
            url = getMonthLeads;
        }
        if (time == 'year') {
            url = getYearLeads;
        }


//        alert(time + ' ' + user);
        $.get(url, {'time': time, 'uid': 'All'}, function(result) {
//            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-leads";
            $("#div-leads").html('<canvas id="canvas-leads" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getAccounts(time, user) {


        var url = '';
        if (time == 'day') {
            url = getDayAccounts;
        }
        if (time == 'week') {
            url = getWeekAccounts;
        }
        if (time == 'month') {
            url = getMonthAccounts;
        }
        if (time == 'year') {
            url = getYearAccounts;
        }

        $.get(url, {'time': time, 'uid': 'All'}, function(result) {
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-accounts";
            $("#div-accounts").html('<canvas id="canvas-accounts" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getContacts(time, user) {
//        alert(time);


        var url = '';
        if (time == 'day') {
            url = getDayContacts;
        }
        if (time == 'week') {
            url = getWeekContacts;
        }
        if (time == 'month') {
            url = getMonthContacts;
        }
        if (time == 'year') {
            url = getYearContacts;
        }


        $.get(url, {'time': time, 'uid': 'All'}, function(result) {
//            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-contacts";
            $("#div-contacts").html('<canvas id="canvas-contacts" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getDeals(time, salesstage, user) {
//        alert();

        var url = '';
        if (time == 'day') {
            url = getDayDeals;
        }
        if (time == 'week') {
            url = getWeekDeals;
        }
        if (time == 'month') {
            url = getMonthDeals;
        }
        if (time == 'year') {
            url = getYearDeals;
        }

//        alert(time + ' ' + salesstage + ' ' + url);

        $.get(url, {'time': time, 'form_id': salesstage, 'uid': 'All'}, function(result) {
//            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-deals";
            $("#div-deals").html('<canvas id="canvas-deals" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }

    function getCustomers(time, user) {

        var url = '';
        if (time == 'day') {
            url = getDayCustomers;
        }
        if (time == 'week') {
            url = getWeekCustomers;
        }
        if (time == 'month') {
            url = getMonthCustomers;
        }
        if (time == 'year') {
            url = getYearCustomers;
        }

//        alert(time);
        $.get(url, {'time': time, 'uid': 'All'}, function(result) {
//            alert(result);
            var res = eval("(" + result + ")");
            var data = res.formleads.reverse();
            var labels = res.labels.reverse();
            var title = res.label;
            var canvas = "canvas-customers";
            $("#div-customers").html('<canvas id="canvas-customers" height="180"></canvas>');
            barchart(data, labels, title, canvas);
        });
    }
</script>
<script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="https://cdn.rawgit.com/jakezatecky/d3-funnel/0.3.2/d3-funnel.js?1"></script>
<script>
    var getSalesFunnelD3 = "{{url('admin/dashboard/getSalesFunnelD3')}}";
    function salesfunnelD3(time, user) {

        $.get(getSalesFunnelD3, {'time': time, 'uid': 'All'}, function(result) {
//            alert(result);
            var res = eval("(" + result + ")");
            var data = res.dataset;
            var wonAmount = res.wonAmount;
            $("#wonAmount").html(wonAmount);
            d3FunnelChart(data, "#funnelContainer", "#funnelPanel");
        });
    }

    function d3FunnelChart(data, container, panel) {


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

        $(window).on("resize", function() {
            var width = $(panel).width();
            //$( "#funnelContainer" ).css( "width", width);
            options.width = width;
            var funnel = new D3Funnel(data, options);
            funnel.draw(container);
        });
    }
</script>

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

        var ctx = document.getElementById(canvas).getContext('2d');   //'canvas-dealstage'
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
<!--
       <div class="row">
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-aqua bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['users']!!}</h3>
                       <p>Users</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-bag"></i>
                   </div>
                   <a href="{{url('admin/users')}}" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-aqua bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['subusers']!!}</h3>
                       <p>Sub Users</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-bag"></i>
                   </div>
                   <a href="{{url('admin/users')}}" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-green bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['webtolead']!!}</h3>
                       <p>Web to Lead</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-stats-bars"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-yellow bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['deals']!!}</h3>
                       <p>Deals</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-person-add"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-red bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['leads']!!}</h3>
                       <p>Leads</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="{{url('admin/leads')}}" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-orange bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['forms']!!}</h3>
                       <p>Forms</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['accounts']!!}</h3>

                       <p>Accounts</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['contacts']!!}</h3>

                       <p>Contacts</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['customers']!!}</h3>

                       <p>Customers</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['sales']!!}</h3>

                       <p>Sales</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['products']!!}</h3>

                       <p>Products</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['documents']!!}</h3>

                       <p>Documents</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['invoices']!!}</h3>

                       <p>Invoices</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['events']!!}</h3>

                       <p>Events</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['territory']!!}</h3>

                       <p>Territory</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
           <div class="col-lg-3 col-xs-6">
               <div class="small-card bg-blue bg-dashboard">
                   <div class="inner">
                       <h3>{!!$data['cronjobs']!!}</h3>

                       <p>Cron Jobs</p>
                   </div>
                   <div class="icon">
                       <i class="ion ion-pie-graph"></i>
                   </div>
                   <a href="#" class="small-card-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
               </div>
           </div>
       </div>
-->
@endsection
