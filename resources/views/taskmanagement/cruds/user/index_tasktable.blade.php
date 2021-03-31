@extends('layouts.adminlte-boot-4.user')

@section('content')

<style>
    .addlinethrough {
        text-decoration: line-through;
    }
</style>
{{-- Modal righ side open css --}}
<style type="text/css">
    .modal-dialog-slideout {
        min-height: 100%;
        margin: 0 0 0 auto;
        background: #fff;
    }

    .modal.fade .modal-dialog.modal-dialog-slideout {
        -webkit-transform: translate(100%, 0)scale(1);
        transform: translate(100%, 0)scale(1);
    }

    .modal.fade.show .modal-dialog.modal-dialog-slideout {
        -webkit-transform: translate(0, 0);
        transform: translate(0, 0);
        display: flex;
        align-items: stretch;
        -webkit-box-align: stretch;
        height: 100%;
    }

    .modal.fade.show .modal-dialog.modal-dialog-slideout .modal-body {
        overflow-y: auto;
        overflow-x: hidden;
    }

    .modal-dialog-slideout .modal-content {
        border: 0;
    }

    .modal-dialog-slideout .modal-header,
    .modal-dialog-slideout .modal-footer {
        height: 69px;
        display: block;
    }

    .modal-dialog-slideout .modal-header h5 {
        float: left;
    }
</style>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">UserTasks <small class="badge badge-secondary">{{ count($tasks) }}</small></h1>

                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                            class="btn btn-sm  btn-primary mr-1 px-3"
                            href="{{ route('taskmanagement.create') }}"><i class="far fa-plus-square mr-1"></i>New Task
                            </a>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('tasks.kanban') }}">Kanban</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @include('taskmanagement.includes.message')
    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            {{-- <div class="col-6" id="filtertable"> --}}
                            <div class="col-6">
                            <h5 id="columnfilter">
                                {{-- <i class="fas fa-tasks"></i> Add Task --}}
                                {{-- <label class="badge badge-secondary">

                                    Assign To : <span id="leadStatusVal"></span>

                                </label>

                                <label class="badge badge-secondary">

                                    Priority : <span id="timer"></span>

                                </label>

                                <label class="badge badge-secondary">

                                    Type : <span id="timer"></span>

                                </label>

                                <label class="badge badge-secondary">

                                    Status : <span id="timer"></span>

                                </label> --}}
                            </h5>
                            </div>
                            {{-- <div class="col-6">
                                <h5 class="card-title" id="none1">
                                    <a
                                        class="btn btn-sm  btn-primary mr-1 px-3 float-right"
                                        href="{{ route('taskmanagement.create') }}"><i class="far fa-plus-square mr-1"></i>New Task
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
                                        <i class="fa fa-filter"></i>
                                    </button>
                                </h5>
                            </div> --}}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="exampletable" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input class="checkbox custom-control-input" type="checkbox" id="check_all">
                                            <label for="check_all" class="custom-control-label ml-2"></label>
                                        </div>
                                    </th>
                                    <th>Task Title</th>
                                    <th>Owner</th>
                                    <th>AssignTo</th>
                                    <th>Priority</th>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>Due Date</th>
                                    <th>Completed Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input
                                            id="{{$task->id}}" data-id="{{$task->id}}"
                                            class="checkbox custom-control-input" type="checkbox" >
                                            <label class="custom-control-label ml-2" for="{{$task->id}}"></label>
                                        </div>
                                    </td>
                                    {{-- <td> --}}
                                        {{-- <div class="icheck-primary d-inline ml-2"> --}}
                                            {{-- <input
                                                type="checkbox"
                                                class="icheck-primary d-inline ml-2"
                                                id="linethrough"
                                                onclick="event.preventDefault();
                                                if(confirm('Want\'s to complete the Task!')){
                                                    $('#form-checked-{{$task->id}}').submit();
                                                }
                                                "
                                                {{ !empty($task->completed_at) ? 'checked' : '' }} /> --}}
                                                {{-- </div> --}}
                                                {{-- </td> --}}

                                                <td>
                                                    <div class="row">

                                                        <div class="col-1">
                                                            <form
                                                            style="display:none"
                                                            id="form-checked-{{$task->id}}"
                                                            action="{{ route('taskcompleted.update',$task->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('put')
                                                        </form>
                                                        {{-- checkbox material goes here --}}
                                                        <a
                                                        href=""
                                                        class="{{ (empty($task->completed_at)) ? 'classA' : 'classB' }}"
                                                        id="linethrough"
                                                        onclick="event.preventDefault();
                                                        if(confirm('Want\'s to complete the Task!')){
                                                            $('#form-checked-{{$task->id}}').submit();
                                                        }"
                                                        data-toggle="tooltip"
                                                        data-placement="left"
                                                        title="{{ (empty($task->completed_at)) ? 'Marked as complete' : 'Marked as not completed'}}"
                                                        >
                                                        @if(empty($task->completed_at))
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                        @else
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="col mx-2">
                                                    {{-- below title part --}}
                                                    <a class="mx-1" href="{{  route('taskmanagement.show',$task->id) }}" >
                                                        {{-- <span class="{{ !empty( $task->completed_at) ? 'addlinethrough' : ''}}">{{$task->title}}</span><br> --}}
                                                        <span>{{$task->title}}</span><br>
                                                    </a>

                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            {{-- {{ $task->user->name }} --}}
                                            {{ Auth::user()->name }}
                                        </td>

                                        <td>
                                            {{ (!empty($task->todoable)) ? $task->todoable->first_name : 'Null' }}
                                        </td>

                                        <td style="width: 85px">
                                            {{-- {!!  $task->priority !!} --}}
                                            @if ($task->priority == 1)
                                            <span class='dot dot-sm dot-success'></span>Low
                                            @elseif($task->priority == 2)
                                            <small class='dot dot-sm dot-warning'></small>Medium
                                            @else
                                            <span class='dot dot-sm dot-danger'></span>High
                                            @endif
                                        </td>

                                        <td>
                                            {{ $task->tasktype->name ?? 'Null'}}
                                        </td>

                                        <td>
                                            {{-- {{ $task->created_at->diffForHumans() }} --}}
                                            {{ $task->started_at->format('d-m-Y') }}
                                        </td>

                                        <td>
                                            <span
                                            {{-- class="{{
                                                ( ($task->due_time) >= ($task->currentDate()) ) ? 'badge badge-info' : 'badge badge-danger'
                                            }}" --}}
                                            >

                                            {{-- <i class='far fa-clock'></i> --}}
                                            {{$task->checkOverdue()}}

                                        </span>
                                    </td>


                                    <td>
                                        {!! (!empty($task->completed_at)) ? Carbon\Carbon::parse($task->completed_at)->format('d-m-Y') : '<span class="badge badge-warning">Not Completed Yet</span>' !!}
                                    </td>

                                    <td>
                                        @if($task->outcome_id == 9)
                                        <a class="badge badge-pill badge-success">{{ $task->outcome->name ?? ''}}</a>
                                        @else
                                        <a class="badge badge-pill badge-secondary">{{ $task->outcome->name ?? ''}}</a>
                                        @endif

                                        {{-- <a class="{{ (($task->outcome_id == 9)) ? 'badge badge-pill badge-success' : 'badge badge-pill badge-secondary' }}">
                                            {{ $task->outcome->name ?? ''}}
                                        </a> --}}
                                    </td>

                                    <td>
                                        <!-- Example single danger button -->
                                        <div class="btn-group">
                                            <button
                                            type="button"
                                            class="btn btn-info dropdown-toggle"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu">
                                            <small>
                                                <a class="dropdown-item" href="{{  route('taskmanagement.edit',$task->id) }}">
                                                    Edit
                                                </a>
                                            </small>
                                            <small>
                                                <a
                                                class="dropdown-item"
                                                href=""
                                                onclick="event.preventDefault();
                                                if(confirm('Are you sure!')){
                                                    $('#form-delete-{{$task->id}}').submit();
                                                }
                                                ">
                                                Delete
                                            </a>
                                            <form
                                            style="display:none"
                                            method="post"
                                            id="form-delete-{{$task->id}}"
                                            action="{{route('taskmanagement.destroy',$task->id)}}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </small>
                                </div>
                            </div>
                        </td>

                        @empty
                        <td class="text-center" colspan="11">
                            No Task Available Yet!
                        </td>

                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="border-top bg-white card-footer text-muted">
            <button
                class="btn btn-sm btn-outline-secondary delete-all"
                data-url=""><i class="fa fa-trash mr-1" aria-hidden="true"></i>Delete</button>
       </div>
    </div>
</div>
</div>

</section>

{{-- Modal --}}
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal sideout normal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Launch demo modal sideout normal -->
                <div class="row">
                    <div class="form-group col-8" id="filtertable">
                        {{-- Here modal value comes through script --}}
                    </div>
                    {{-- <div class="form-group col-8" >
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

</div>
{{-- script for filter --}}
<script>
    $(document).ready(function() {
        $('#exampletable').DataTable( {
            "ordering": false,
            initComplete: function () {
                this.api().columns([3,4,5,9]).every( function (d) {//THis is used for specific column
                    var column = this;
                    var theadname = $('#exampletable th').eq([d]).text();
                    var select = $('<select class="form-control my-1"><option value="">'+theadname+': All</option></select>')
                    .appendTo( '#filtertable' )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                        );

                        column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                    } );
                    column.data().unique().sort().each( function ( d, j ) {
                        var val = $('<div/>').html(d).text();
                        select.append( '<option value="'+val+'">'+val+'</option>' )
                    } );

                    var select = $('<label class="badge badge-secondary m-1">'+theadname+': All</label>')
                    .appendTo( '#columnfilter' )
                    // .on( 'change', function () {
                    //     var val = $.fn.dataTable.util.escapeRegex(
                    //     $(this).val()
                    //     );

                    //     column
                    //     .search( val ? '^'+val+'$' : '', true, false )
                    //     .draw();
                    // } );
                    // column.data().unique().sort().each( function ( d, j ) {
                    //     var val = $('<div/>').html(d).text();
                    //     select.append( '<option value="'+val+'">'+val+'</option>' )
                    // } );

                } );
            }
        } );
    } );
</script>
{{-- below script for delete all --}}
<script type="text/javascript">
    $(document).ready(function () {
        $('#check_all').on('click', function(e) {
            if($(this).is(':checked',true))
            {
                $(".checkbox").prop('checked', true);

            } else {
                $(".checkbox").prop('checked',false);

            }
        });

        $('.checkbox').on('click',function(){

            if($('.checkbox:checked').length == $('.checkbox').length){

                $('#check_all').prop('checked',true);

            }else{
                $('#check_all').prop('checked',false);

            }

        });
        $('.delete-all').on('click', function(e) {
            var idsArr = [];
            $(".checkbox:checked").each(function() {
                idsArr.push($(this).attr('data-id'));
            });
            if(idsArr.length <=0)
            {
                alert("Please select atleast one record to delete.");

            }else {
                if(confirm("Are you sure, you want to delete the selected record?")){

                    var strIds = idsArr.join(",");
                    $.ajax({
                        url: "{{ route('delete-all-task.task') }}",
                        type: 'GET',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+strIds,
                        success: function (data) {
                            if (data['status']==true) {
                                $(".checkbox:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });

                                alert(data['message']);

                            } else {

                                alert('Whoops Something went wrong!!');

                            }

                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                }
            }
        });
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.closest('form').submit();
            }
        });
    });
</script>

@endsection
