@extends('layouts.adminlte-boot-4.admin')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Tasks <small class="badge badge-secondary">Create</small></h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">

                <form method="post" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input
                                    type="text"
                                    class="form-control {{ $errors->first('title', 'is-invalid') }}"
                                    name="title"
                                    id="title"
                                    aria-describedby="helpId"
                                    placeholder=""
                                    value="{{$task->title}}"
                                    />
                                    {{-- @error('title') --}}
                                    <small class="text-danger">{{ $errors->first('title') }}</small>
                                    {{-- @enderror --}}
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea
                                    id="description"
                                    class="form-control"
                                    name="description"
                                    rows="3"
                                    >{{$task->description}}</textarea>
                                </div>

                                <div class="row">

                                <div class="col">
                                    <div class="form-group">
                                        <label>Start date:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input
                                            type="text"
                                            class="form-control float-right"
                                            id="started_at"
                                            name="started_at"
                                            value="{{$task->started_at->format('m/d/Y h:i A')}}"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label>Due date:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input
                                            type="text"
                                            class="form-control float-right"
                                            id="duetime"
                                            name="due_time"
                                            value="{{$task->due_time->format('m/d/Y h:i A')}}"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Task type</label>
                                        <select
                                        class="form-control tasktype"
                                        style="width: 100%;"
                                        name="tasktype_id"
                                        >
                                        <option></option>
                                        @foreach ($tasktypes as $type)
                                        <option value="{{$type->id}}" {{$task->tasktype_id == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select id="priority" class="custom-select" name="priority">
                                            <option value="3" {{ ($task->priority == 3) ? 'selected' : '' }}>High</option>
                                            <option value="2" {{ ($task->priority == 2) ? 'selected' : '' }}>Medium</option>
                                            <option value="1" {{ ($task->priority == 1) ? 'selected' : '' }}>Low</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Outcomes</label>
                                <select
                                class="form-control outcome"
                                style="width: 100%;"
                                name="outcome_id"
                                >
                                <option></option>
                                @foreach($outcomes as $outcome)
                                <option value="{{$outcome->id}}" {{$task->outcome_id == $outcome->id ? 'selected' : ''}}>{{$outcome->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{--  <div class="form-check">
                            <label class="form-check-label">
                                <input
                                type="checkbox"
                                class="form-check-input"
                                name=""
                                id=""
                                value="checkedValue"
                                />
                                Mark as completed
                            </label>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <div class="form-group">
                                    <label>Completed date:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input
                                        type="text"
                                        value="if above check-box is completed then this box appear with current values"
                                        class="form-control float-right"
                                        id=""
                                        readonly
                                        />
                                    </div>
                                    /.input group
                                </div>
                            </div>
                            <div class="col">
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Time range</label>
                                        <div
                                        class="input-group date"
                                        id="timepicker1"
                                        data-target-input="nearest"
                                        >
                                        <div
                                        class="input-group-append"
                                        data-target="#timepicker1"
                                        data-toggle="datetimepicker"
                                        >
                                        <div class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </div>
                                    </div>
                                    <input
                                    type="text"
                                    class="form-control datetimepicker-input"
                                    data-target="#timepicker1"
                                    readonly/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Owner</label>
                    <select
                    class="form-control select2"
                    data-placeholder="Select Owner"
                    style="width: 100%;"
                    name="user_id"
                    >
                    <option value="0"></option>
                    <option value="{{auth()->user()->id}}" selected>{{auth()->user()->name}}</option>
                </select>
            </div>

            <div class="form-group">
                <label>Related to</label>
                <select
                class="form-control relatedto"
                style="width: 100%;"
                name="todoable_id"
                >
                <option></option>
                @foreach($users as $user)
                    <option value="{{$user->id}}" {{$task->todoable_id == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                @endforeach
            </select>
        </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
</div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-success float-right">
        Submit
    </button>
    <a
    href="#"
    class="btn btn-light float-right mx-2"
    role="button"
    >Cancel</a
    >
</div>
</form>
</div>
</div>
<!-- /.container-fluid -->
</section>
</div>
<script>
    $(function () {
        $(".tasktype").select2({
            allowClear: true,
            placeholder: "Type",
            maximumInputLength: 2,

        });
        $(".outcome").select2({
            allowClear: true,
            //   placeholder: {
                //     id: '0', // the value of the option
                //     text: 'Outcomes'
                //   },
                placeholder: "Outcomes",
                maximumInputLength: 2,//For minimum search result
                // minimumResultsForSearch: -1
                //For hiding the search box and replace -1 with other value it will show search results

            });
            $(".relatedto").select2({
                allowClear: true,
                placeholder: "Link thsi task to",
                // placeholder: {
                //     id: '0', // the value of the option
                //     text: 'Link this task to'
                // },
                maximumInputLength: 2,

            });

            //Date range picker changed into single datepicker using singleDatePicker: true, do false if range needed
            $("#duetime").daterangepicker({

                autoUpdateInput: false,
                startDate: false,
                minYear: 1901,
                showDropdowns: true,
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: false,
                timePickerIncrement: 05,
                drops:"up",
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                },
            });
            $("#duetime").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY hh:mm A'));
            });

            $("#duetime").on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // StartedDate Start
            $("#started_at").daterangepicker({

                autoUpdateInput: false,
                startDate: false,
                minYear: 1901,
                showDropdowns: true,
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: false,
                timePickerIncrement: 05,
                drops:"up",
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                },
            });
            $("#started_at").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY hh:mm A'));
            });

            $("#started_at").on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            // End Started Date

        });
    </script>
    @endsection
