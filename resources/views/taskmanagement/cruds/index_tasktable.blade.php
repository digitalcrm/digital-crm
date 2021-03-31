@extends('layouts.adminlte-boot-4.admin')

@section('content')

<style>
    .addlinethrough {
        text-decoration: line-through;
    }
</style>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10 mt-0">
                    <h1 class="m-0 text-dark">Tasks <small class="badge badge-secondary">{{ count($tasks) }}</small></h1>
                </div>
                <div class="col-sm-2" id="filtertable">
                    {{-- Here datatable select field appears --}}
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
                        <h3 class="card-title">
                            <i class="fas fa-tasks"></i>
                            Add Task
                            {{-- <a class="float-right" href="{{ route('tasks.create') }}"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a> --}}
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="exampletable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>Task Title</th>
                          <th style="display:none">Owner</th>
                          {{-- <th>AssignTo</th> --}}
                          <th>Priority</th>
                          <th>Type</th>
                          <th>Start Date</th>
                          <th>Due Date</th>
                          <th>Completed Date</th>
                          <th>Status</th>
                          {{-- <th>Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div class="row">

                                        <div class="col-1">
                                            <form
                                            style="display:none"
                                            id="form-checked-{{$task->id}}"
                                            action="{{ route('completed.update',$task->id) }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                        </form>
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
                                    <a class="mx-1" href="{{  route('tasks.show',$task->id) }}" ><span>{{$task->title}}</span><br>
                                    </a>

                                </div>
                            </div>
                        </td>

                                <td style="display: none">
                                    {{ ($task->user->name ?? Auth::user()->name) }}
                                    {{-- {{ Auth::user()->name }} --}}
                                </td>

                                <!--<td>
                                    {{-- {{ (!empty($task->todoable)) ? $task->todoable->name : 'Null' }} --}}
                                    {{-- {{ ($task->todoable_type == 'App\User') ? $task->todoable->name : $task->todoable->first_name }} --}}
                                </td>-->

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
                                    <span>
                                        {{ $task->started_at->format('d-m-Y') }}
                                    </span>
                                </td>

                                <td>
                                    {{$task->checkOverdue()}}
                                </td>

                                <td>
                                    {{ (!empty($task->completed_at)) ? Carbon\Carbon::parse($task->completed_at)->format('d-m-Y') : 'Not Completed Yet' }}
                                </td>

                                <td>
                                    @if($task->outcome_id == 9)
                                    <a class="badge badge-pill badge-success">{{ $task->outcome->name ?? ''}}</a>
                                    @else
                                    <a class="badge badge-pill badge-secondary">{{ $task->outcome->name ?? ''}}</a>
                                    @endif
                                </td>

                                {{-- <td>
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
                                                <a class="dropdown-item" href="{{  route('tasks.edit',$task->id) }}">
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
                                                action="{{route('tasks.destroy',$task->id)}}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('delete')
                                            </form>
                                            </small>
                                        </div>
                                    </div>
                                </td> --}}
                            </tr>


                                @empty
                                <tr>
                                    <td class="text-center" colspan="10">
                                        No Task Available Yet!
                                    </td>
                                </tr>



                                @endforelse
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>

            </div>
        </div>

    </section>




</div>
{{-- <script src="{{asset('/js/vanilla.js')}}"></script>
<script>

    $(document).ready(function($id){
        // $('input[type="checkbox"]').click(function(){
        //     $('#clls-'+$id).toggleClass('addlinethrough', $(this).is(":checked"));
        // });

    const checkboxElement = document.getElementById("linethrough")
    const elementWithLineThrough = document.getElementById("someId" + id)

    checkboxElement.addEventListener("click", () => {
    const checkboxStatus = checkboxElement.checked;

    // when the checkbox is checked, the class is added, if not, is deleted
    elementWithLineThrough.classList.toggle("addlinethrough", checkboxStatus);
    });

</script> --}}
<script>
    $(document).ready(function() {
        $('#exampletable').DataTable( {
            "ordering": false,
            initComplete: function () {
                this.api().columns([1]).every( function (d) {//THis is used for specific column
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
                } );
            }
        } );
    } );
</script>
@endsection
