@extends('layouts.adminlte-boot-4.booking')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/adminlte-boot-4/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<style>
    .bootstrap-datetimepicker-widget table td.disabled, .bootstrap-datetimepicker-widget table td.disabled:hover {
        background: #fafafa !important;
        color: #6c757d;
        cursor: not-allowed;
    }
</style>
<style>
    .error {
        color: #a94442;
        border-color: #ee0c31;
    }
</style>
@endsection
@section('content')

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="float-right"
                            href="{{ route('bookings') }}"
                            data-toggle="tooltip" data-placement="left" title="Gallery View">
                            Bookings
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-9 pl-0 pr-5">
            <div class="card">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </ul>
                </div>
                @endif
                @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Hooray!</strong> {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="card-header">
                    {{ $bookevent->event_name }}
                </div>
                <div class="card-body">
                    <form action="{{ route('event.store', ['bookservice' =>$bookevent->bookingService->service_name, 'bookevent'=>$bookevent->id]) }}"
                        method="POST"
                        enctype="multipart/form-data" id="form">
                        @csrf
                        <input type="hidden" name="eventId" value="{{ $bookevent->id }}">
                        <input type="hidden" name="userId" value="{{ $bookevent->user_id }}">

                        <div class="form-group">
                            <label for="start_from">From</label>
                            <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                 <input type="text"
                                        name="start_from"
                                        class="form-control datetimepicker-input @error('start_from') is-invalid @enderror"
                                        data-target="#datetimepicker7" data-toggle="datetimepicker" required/>
                                 <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                     <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                 </div>
                             </div>
                             @error('start_from')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                         </div>

                        <div class="form-group">
                            <label for="to_end">To</label>
                            <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                                 <input type="text" name="to_end"
                                        class="form-control datetimepicker-input @error('to_end') is-invalid @enderror"
                                        data-target="#datetimepicker8" data-toggle="datetimepicker" required/>
                                 <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                                     <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                 </div>
                             </div>
                             @error('to_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <label>Time</label>
                            <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                <input type="text"
                                        name="time"
                                        class="form-control datetimepicker-input"
                                        data-toggle="datetimepicker" data-target="#datetimepicker3"/>
                                <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="far fa-clock" aria-hidden="true"></i></div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label for="customer_name" class="col-form-label">Name:</label>
                            <input type="text"
                            class="form-control @error('customer_name') is-invalid @enderror"
                            id="customer_name"
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            required>
                            @error('customer_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mobile_number" class="col-form-label">Mobile:</label>
                            <input type="tel"
                            class="form-control @error('mobile_number') is-invalid @enderror"
                            id="mobile_number" name="mobile_number"
                            value="{{ old('mobile_number') }}"
                            required>
                            @error('mobile_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-form-label">Description:</label>
                            <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            >{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <div
                            class="g-recaptcha"
                            data-sitekey="{{ config('services.recaptcha.sitekey') }}"
                            data-expired-callback="Your Recaptcha has expired, please verify it again !">
                        </div>
                    </div>


                    <div class="card-footer">
                        <a class="btn btn-info" href="{{ url()->previous() }}">Back</a>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </div>
                </div>
            </form>
        </div> <!-- /.card -->
    </div>

        <div class="col-md-3">
            <div class="card mb-2">
                <div class="card-header">
                    <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendLinkModal">
                    Send Link To Friend
                </button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Event
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $bookevent->event_name }}</h5>
                    <label>Price:</label> <span class="text-muted">{{ $bookevent->price }}$</span>
                    <h6 class="mb-0">Description:</h6>
                    <p class="card-text">{{ $bookevent->event_description }}</p>
                    <p>Start Event: {{ $bookevent->event_start }}</p>
                    <p>End Event: {{ $bookevent->event_end }}</p>
                    <input type="hidden" name="start_date" id="start_date" value="{{ $bookevent->event_start }}">
                    <input type="hidden" name="end_date" id="end_date" value="{{ $bookevent->event_end }}">
                </div>
            </div>
         </div>
    </div>
</div>

    {{-- Modal send link to friend --}}
    @include('bookings.includes.send-event-to-friend')


@section('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('assets/adminlte-boot-4/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
{{-- <script>
    $(function () {
        //Below Started_at
        $("#booking_date").daterangepicker({

            startDate: moment().startOf('hour'),
            minYear: 1901,
            showDropdowns: true,
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: false,
            timePickerIncrement: 05,
            drops:"down",
            locale: {
                format: 'MM/DD/YYYY hh:mm A'
            }
        });

    });
</script> --}}
<script type="text/javascript">
    $(function () {
        var startEventDate = $('#start_date').val();
        var endEventDate = $('#end_date').val();
        // console.log(dateVal);
        $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down',
                previous: 'fa fa-arrow-left',
                next: 'fa fa-arrow-right',
                today: 'far fa-calendar-check-o',
                clear: 'far fa-trash',
                close: 'far fa-times'
            },
            minDate: new Date(startEventDate),
            maxDate: new Date(endEventDate).setHours(23,59,59,999),
            format: 'MM/DD/YYYY hh:mm A',
            // format: 'MM/DD/YYYY',
            sideBySide: true,
            stepping: 15,
            // daysOfWeekDisabled: [0, 6],
            // disabledDates: ['11/09/2020'],
            });

        $('#datetimepicker7').datetimepicker({

        });

        $('#datetimepicker8').datetimepicker({
            useCurrent: true,
        });

        $("#datetimepicker7").on("change.datetimepicker", function (e) {
            $('#datetimepicker8').datetimepicker('minDate', e.date);
        });

        $("#datetimepicker8").on("change.datetimepicker", function (e) {
            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });

        // $('#datetimepicker3').datetimepicker({
        //     format: 'LT'
        // });

    });
</script>
    {{-- @if ($errors->any())
        <script type="text/javascript">
            $(document).ready(function() {
                $('#sendLinkModal').modal('show');
            });
        </script>
    @endif --}}
    <script>

        $(document).ready(function () {

            $('#form1').validate({ // initialize the plugin

                rules: {
                    sender_name: "required",
                    receiver_name: {
                        required: true
                    },
                    sender_email: {
                        required: true,
                        email: true
                    },
                    receiver_email: {
                        required: true,
                        email: true
                    },
                    message: {
                        required: true,
                    },
                    cap: {
                        required: true,
                    }
                },
                messages: {
                    sender_name: "please enter your name",
                    receiver_name: "please enter receiver name",
                    sender_email: "please enter your valid email",
                    receiver_email: "please enter receiver valid email",
                    description: "please fill the message",
                },

                // highlight: function(element, errorClass) {
                //     $(element).fadeOut(function() {
                //     $(element).fadeIn();
                //     });
                // },
            });
        });
    </script>

@endsection
@endsection


