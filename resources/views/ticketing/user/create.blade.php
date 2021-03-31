@extends('layouts.adminlte-boot-4.user')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Ticket Create</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">

                <form method="post" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="name">Ticket Name</label>
                                    <input
                                    type="text"
                                    class="form-control {{ $errors->first('name', 'is-invalid') }}"
                                    name="name"
                                    id="name"
                                    aria-describedby="helpId"
                                    value="{{ old('name') }}"
                                    placeholder=""
                                    />
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea
                                    id="description"
                                    class="form-control {{ $errors->first('description', 'is-invalid')}}"
                                    name="description"
                                    rows="3"
                                    >{{ old('description') }}</textarea>
                                    <small class="text-danger">{{ $errors->first('description') }}</small>
                                </div>

                                <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Date Checks:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input
                                            type="text"
                                            class="form-control float-right"
                                            id="start_date"
                                            name="start_date"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select id="priority"
                                            class="custom-select {{ $errors->first('priority_id', 'is-invalid') }}"
                                            name="priority_id">
                                            <option value="0">Select Field</option>
                                            {{-- <option value="3">High</option>
                                            <option value="2" selected>Medium</option>
                                            <option value="1">Low</option> --}}
                                            @foreach ($priorities as $priority)
                                            <option
                                            value="{{$priority->id}}" {{ old('priority_id') == $priority->id  ? 'selected' : '' }}>
                                                {{$priority->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger">{{ $errors->first('priority_id') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Ticket type</label>
                                        <select
                                        class="form-control {{ $errors->first('type_id', 'is-invalid') }}"
                                        style="width: 100%;"
                                        name="type_id"
                                        >
                                        <option value="0">Select Type</option>
                                        @foreach ($ticket_type as $type)
                                        <option value="{{$type->id}}" {{ old('type_id') == $type->id  ? 'selected' : '' }}>
                                            {{$type->name}}
                                        </option>
                                        @endforeach
                                        </select>
                                        <small class="text-danger">{{ $errors->first('type_id') }}</small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Ticket Status</label>
                                        <select
                                        class="form-control outcome {{ $errors->first('status_id', 'is-invalid') }}"
                                        style="width: 100%;"
                                        name="status_id"
                                        >
                                        <option value="0">Select status</option>
                                        @foreach($ticket_status as $status)
                                        <option value="{{$status->id}}" {{ old('status_id') == $status->id  ? 'selected' : '' }}>
                                            {{$status->name}}
                                        </option>
                                        @endforeach
                                        </select>
                                            <small class="text-danger">{{ $errors->first('status_id') }}</small>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Product</label>
                                        <select
                                        class="form-control {{ $errors->first('product_id', 'is-invalid') }}"
                                        style="width: 100%;"
                                        name="product_id"
                                        >
                                        <option value="0">Select Type</option>
                                        @foreach ($products as $product)
                                        <option value="{{$product->pro_id}}" {{ old('product_id') == $product->pro_id  ? 'selected' : '' }}>
                                            {{$product->name}}
                                        </option>
                                        @endforeach
                                        </select>
                                        <small class="text-danger">{{ $errors->first('product_id') }}</small>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="ticket_image">Image</label>
                                        <input
                                            id="ticket_image"
                                            class="form-control-file {{ $errors->first('ticket_image', 'is-invalid') }}"
                                            type="file"
                                            name="ticket_image"
                                            aria-describedby="fileHelp">
                                        <small id="fileHelp" class="form-text text-muted">
                                            Please upload a valid image file. Size of image should not be more than 2MB.
                                        </small>
                                        @error('ticket_image')
                                        <small class="text-danger">{{ $errors->first('ticket_image') }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>


            </div>

            <div class="col-md-6">
                <div class="form-group d-none">
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
                <label>Contacts</label>
                <select
                class="form-control relatedto"
                style="width: 100%;"
                name="contact_id"
                >
                <option value="0">select customer</option>
                @foreach($contacts as $contact)
                {{-- <input type="hidden" style="visibility: hidden;" class="form-control" name="contactemail" id="contactemail" value="{{$contact->email}}" aria-describedby="emailHelpId" readonly /> --}}
                <option value="{{$contact->cnt_id}}" {{ old('contact_id') == $contact->cnt_id  ? 'selected' : '' }}>
                    {{$contact->fullname()}}
                </option>
                @endforeach
            </select>
            <small class="text-danger">{{ $errors->first('contact_id') }}</small>
        </div>

        {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif --}}
    </div>
</div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-success float-right">
        Submit
    </button>
    <a
    href="{{ url()->previous() }}"
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
            //Below Started_at
            $("#start_date").daterangepicker({

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
