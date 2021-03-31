@extends('layouts.adminlte-boot-4.user')
@section('content')
<style>
    .fc-event {
        padding: 5px;
    }
    .fc-time{
    display : none;
    }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Calendar</h1>
                </div>

                <div class="col-sm-8">
                    <a class="btn btn-outline-secondary float-right mr-1" href="{{ route('confirmed_users.index') }}">Bookings</a>
                    <a class="btn btn-outline-secondary float-right mr-1" href="{{ route('bookevents.index',['events'=>'upcoming']) }}">Appointments</a>
                    <div class="dropdown mr-1 mx-1 float-right">
                        <button type="button" class="btn btn-primary dropdown-toggle" id="dropdownMenuOffset"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,20">
                            <i class="far fa-plus-square mr-1"></i> New
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                            <a class="dropdown-item" href="{{ url('calendar/create') }}">New
                                Event</a>
                            <a class="dropdown-item" href="{{ route('bookevents.create') }}">New
                                Appointment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mx-0">
        @if(session('success'))
            <div class='alert alert-success'>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class='alert alert-danger'>
                {{ session('error') }}
            </div>
        @endif
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-9">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendarDiv"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body p-0">
                            <!-- the events -->
                            <ul class="nav nav-tabs pull-right" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="appointment-tab" data-toggle="tab"
                                        href="#appointment" role="tab" aria-controls="appointment"
                                        aria-selected="true">Appointments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                        aria-controls="home" aria-selected="true">Events</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false">Deals</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="appointment" role="tabpanel"
                                    aria-labelledby="appointment-tab">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <h5>Upcoming</h5>
                                            <div id="external-events">
                                                {!!$data['myappointmentsli']!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div id="external-events">
                                                {!!$data['myeventsli']!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="card shadow card-primary card-outline">
                                        <div class="card-body p-0">
                                            {{-- {!!$data['mydealsli']!!} --}}
                                            Deals
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<script>
    $(function () {
        eventCalendar();
    });

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
                    selectable: false,
                    selectHelper: true,
                    eventRender: function (event, element) {
                        element.popover({
                            animation: true,
                            delay: 300,
                            content: event.popup_content,
                            trigger: 'hover',
                            html: true,
                            container: 'body',
                            placement: function (context, source) {
                                var position = $(source).position();

                                if (position.left > 515) {
                                    return "left";
                                }

                                if (position.left < 515) {
                                    return "right";
                                }

                                if (position.top < 110) {
                                    return "bottom";
                                }

                                return "top";
                            }
                        });
                    },
                    select: function (start, end, allDay) {
                        var title = prompt('Event Title:');
                        if (title) {
                            calendar.fullCalendar('renderEvent', {
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                },
                                true // make the event "stick"
                            );
                        }
                        calendar.fullCalendar('unselect');
                    },
                    events: events,
                    eventLimit: 3,
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

                    },
                });
            }
        });
    }

</script>
@endsection
