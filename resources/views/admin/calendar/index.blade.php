@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Calendar
            <small id="total">0</small>
        </h1>
    </section>
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
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
                <div class="card shadow card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title float-left">
                            <select class="form-control" id="selectUser" name="selectUser">
                                {!!$data['useroptions']!!}
                            </select>
                        </h3>
                        <div class="btn-group btn-default float-right">
                            <a class="btn bg-blue" href="{{url('admin/calendar/create')}}"><i class="fa fa-plus"></i>&nbsp; Add Event</a>
                        </div>
                    </div> 
                    <!--/.card-header--> 
                    <div class="card-body">
                        <div class="no-padding with-border">
                            <!-- THE CALENDAR -->
                            <div id="calendarDiv"></div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div id="resulttt">

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
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#selectUser").change(function() {
            eventUserCalendar($(this).val());
        });
        eventCalendar('All');
    });

    function eventUserCalendar(user) {
        var events = '';
        var url = '{{url("admin/ajax/getUserEvents")}}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {uid: user},
            success: function(data) {
//                alert(data);

                $('#calendarDiv').fullCalendar('removeEvents');
                events = eval('(' + data + ')');
                if (events.length > 0) {
                    $('#calendarDiv').fullCalendar('addEventSource', events);
                }
            }
        });
    }

    function eventCalendar(user) {
        var events = '';
        var url = '{{url("admin/ajax/getUserEvents")}}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {uid: user},
            success: function(data) {
//                alert(data);

                events = eval('(' + data + ')');
                if (events.length == 0) {
                    $('#calendarDiv').fullCalendar('removeEvents');
                } else {
                    $('#calendarDiv').fullCalendar('addEventSource', events);

//                alert(events);
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

                            // is the "remove after drop" checkcard checked?
                            if ($('#drop-remove').is(':checked')) {
                                // if so, remove the element from the "Draggable Events" list
                                $(this).remove();
                            }

                        }
                    });
                }
            }
        });
    }


    //Random default events
    /*
     events: [
     {
     title: 'All Day Event',
     start: new Date(y, m, 1),
     backgroundColor: '#f56954', //red
     borderColor: '#f56954' //red
     },
     {
     title: 'Long Event',
     start: new Date(y, m, d - 5),
     end: new Date(y, m, d - 2),
     backgroundColor: '#f39c12', //yellow
     borderColor: '#f39c12' //yellow
     },
     {
     title: 'Meeting',
     start: new Date(y, m, d, 10, 30),
     allDay: false,
     backgroundColor: '#0073b7', //Blue
     borderColor: '#0073b7' //Blue
     },
     {
     title: 'Lunch',
     start: new Date(y, m, d, 12, 0),
     end: new Date(y, m, d, 14, 0),
     allDay: false,
     backgroundColor: '#00c0ef', //Info (aqua)
     borderColor: '#00c0ef' //Info (aqua)
     },
     {
     title: 'Birthday Party',
     start: new Date(y, m, d + 1, 19, 0),
     end: new Date(y, m, d + 1, 22, 30),
     allDay: false,
     backgroundColor: '#00a65a', //Success (green)
     borderColor: '#00a65a' //Success (green)
     },
     {
     title: 'Click for Google',
     start: new Date(y, m, 28),
     end: new Date(y, m, 29),
     url: 'http://google.com/',
     backgroundColor: '#3c8dbc', //Primary (light-blue)
     borderColor: '#3c8dbc' //Primary (light-blue)
     }
     ],
     */

</script>
@endsection