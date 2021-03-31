@extends('layouts.adminlte-boot-4.user')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Calendar</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content mx-0">

        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
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
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new Calendar(calendarEl, {
      plugins: [ momentPlugin, dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
      initialView: 'dayGridMonth',
      themeSystem: 'bootstrap',
      displayEventTime : false,
      height: 600,
      selectable:true,
      editable:true,
      headerToolbar: {
        left: 'today,prev,next',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      eventClick: function(info) {
          var eventObj = info.event;
        //   console.log(eventObj.extendedProps.description);
        if (eventObj.extendedProps.customer > 0) {
          alert(
            'Want\'s to view ' + eventObj.title + '.\n' +
            'Will open ' + eventObj.url + ' in a new tab'
          );

          window.open(eventObj.url);

          info.jsEvent.preventDefault(); // prevents browser from following link in current tab.
        } else {
          alert('Clicked ' + eventObj.title);
        }
      },
      eventDidMount: function(info) {
        $(info.el).tooltip({
            title: info.event.extendedProps.description,
            trigger: 'hover',
          })
      },
      events: "{{ url('bookingeventdata') }}",
      eventColor: '#1b2961',
    });

    calendar.render();
});

</script>

@endsection
