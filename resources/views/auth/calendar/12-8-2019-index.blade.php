@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <style>
        .fc-event{
            padding: 5px;
        }
    </style>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12">
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


                <div class="col-md-9">
                    <div class="box">
                        <div class="box-header">
                            <h1 class="box-title">Calendar &nbsp;<small class="badge badge-secondary">{{$data['total']}}</small></h1>
                            <div class="btn-group btn-flat pull-right">
                                <a class="btn bg-blue" href="{{url('calendar/create')}}"><i class="fas fa-plus"></i>&nbsp;New Event</a>
                            </div>
                        </div>
                        <!--/.box-header-->
                        <div class="box-body">
                            <div class="no-padding with-border">
                                <!-- THE CALENDAR -->
                                <div id="calendarDiv"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-md-3">
                    <!--                    <div class="box box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">My Events</h4>
                                            </div>
                                            <div class="box-body">
                                                <ul class="todo-list ui-sortable">
                                                    <li>
                                                        <a href="#">
                                                            <span class="text">Design a nice theme</span>
                                                            <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>-->

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="true">Events</a></li>
                            <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">Deals</a></li>
                            <li class="pull-left header"><i class="fa fa-th"></i> Upcoming</li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1-1">
                                <ul class="todo-list ui-sortable">
                                    {!!$data['myeventsli']!!}
                                </ul>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2-2">
                                <ul class="todo-list ui-sortable">

                                    {!!$data['mydealsli']!!}

                                </ul>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>

                </div>
                <!-- /.col -->

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
//        alert('calendar');
        eventCalendar();
    });

    function eventCalendar() {
        var events = '';
        var url = '{{url("ajax/getUserEvents")}}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {uid: 0},
            success: function(data) {
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
                    drop: function(date, allDay) { // this function is called when something is dropped

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

                        // is the "remove after drop" checkbox checked?
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
@endsection
