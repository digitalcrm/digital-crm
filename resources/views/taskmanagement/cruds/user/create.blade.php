@extends('layouts.adminlte-boot-4.user')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">New Task</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">

                <form method="post" action="{{ route('taskmanagement.store') }}" enctype="multipart/form-data">
                    @csrf

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
                                    ></textarea>
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
                                        class="form-control tasktype {{ $errors->first('tasktype_id', 'is-invalid') }}"
                                        style="width: 100%;"
                                        name="tasktype_id"
                                        >
                                        <option value="0">Task Type</option>
                                        @foreach ($tasktypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">{{ $errors->first('tasktype_id') }}</small>
                                </div>

                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select id="priority" class="custom-select" name="priority">
                                            <option value="3">High</option>
                                            <option value="2" selected>Medium</option>
                                            <option value="1">Low</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Outcomes</label>
                                <select
                                class="form-control outcome {{ $errors->first('outcome_id', 'is-invalid') }}"
                                style="width: 100%;"
                                name="outcome_id"
                                >
                                <option value="0">Outcome</option>
                                @foreach($outcomes as $outcome)
                                <option value="{{$outcome->id}}">{{$outcome->name}}</option>
                                @endforeach
                            </select>
                            {{-- @error('outcome_id') --}}
                                <small class="text-danger">{{ $errors->first('outcome_id') }}</small>
                            {{-- @enderror --}}
                        </div>
                        {{-- <div class="form-group">
                            <label for="my-select">Text</label>
                            <select id="my-select" class="custom-select @error('outcome_id') is-invalid @enderror" name="">
                                <option></option>
                                @foreach($outcomes as $outcome)
                                <option value="{{$outcome->id}}">{{$outcome->name}}</option>
                                @endforeach
                                @error('outcome_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </select>
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
                @foreach($leads as $lead)
                <option value="{{$lead->ld_id}}">{{$lead->first_name}}</option>
                @endforeach
            </select>
        </div>

            <div class="form-group">
                <label>Projects</label>
                <select
                class="form-control relatedto"
                style="width: 100%;"
                name="project_id"
                >
                <option></option>
                @foreach($project as $proj)
                <option value="{{$proj->project_id}}">{{$proj->name}}</option>
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
    href="{{ URL::previous() }}"
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
        // $(".tasktype").select2({
        //     allowClear: true,
        //     placeholder: "Type",
        //     maximumInputLength: 2,

        // });
        // $(".outcome").select2({
        //     allowClear: true,
        //     //   placeholder: {
        //         //     id: '0', // the value of the option
        //         //     text: 'Outcomes'
        //         //   },
        //         placeholder: "Outcomes",
        //         maximumInputLength: 2,//For minimum search result
        //         // minimumResultsForSearch: -1
        //         //For hiding the search box and replace -1 with other value it will show search results

        //     });
        //     $(".relatedto").select2({
        //         allowClear: true,
        //         placeholder: "Link this task to",
        //         maximumInputLength: 2,

        //     });

            //Date range picker changed into single datepicker using singleDatePicker: true, do false if range needed
            $("#duetime").daterangepicker({

                // startDate: moment().startOf('hour'),
                startDate: moment().add(1, 'days'),
                // minDate: moment().add(1, 'days'),//This will not do accessing the previous date
                minYear: 1901,
                // maxYear: parseInt(moment().format('YYYY'),10),//For this year not show future years
                showDropdowns: true,
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: false,
                timePickerIncrement: 05,
                drops:"up",
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            });

            //Below Started_at
            $("#started_at").daterangepicker({

                startDate: moment().startOf('hour'),
                minYear: 1901,
                showDropdowns: true,
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: false,
                timePickerIncrement: 05,
                drops:"up",
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            });

            //Timepicker
            $("#timepicker").datetimepicker({
                format: "LT",
            });

        });
    </script>
    @endsection
