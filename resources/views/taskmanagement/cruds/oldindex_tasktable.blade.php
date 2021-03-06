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
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Tasks <small class="badge badge-secondary">{{ count($tasks) }}</small></h1>

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
                            <i class="ion ion-clipboard mr-1"></i>
                            Add Task
                            <a class="float-right" href="{{ route('tasks.create') }}"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
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
                                {{-- <td>
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
                                    <a class="mx-1" href="{{  route('taskmanagement.show',$task->id) }}" ><span>{{$task->title}}</span><br>
                                    </a>

                                </div>
                            </div>
                        </td> --}}
                                <td>
                                    <div class="icheck-primary d-inline ml-2">
                                        <form
                                                style="display:none"
                                                id="form-checked-{{$task->id}}"
                                                action="{{ route('completed.update',$task->id) }}"
                                                method="post">
                                            @csrf
                                            @method('put')
                                        </form>
                                        <input
                                        type="checkbox"
                                        class="icheck-primary d-inline ml-2"
                                        id="linethrough"
                                        onclick="event.preventDefault();
                                        if(confirm('Want\'s to complete the Task!')){
                                            $('#form-checked-{{$task->id}}').submit();
                                        }
                                        "
                                        {{ !empty($task->completed_at) ? 'checked' : '' }}/>
                                    </div>
                                </td>

                                <td>
                                    <a href="{{  route('tasks.show',$task->id) }}" >
                                        <span class="{{ !empty( $task->completed_at) ? 'addlinethrough' : ''}}">{{$task->title}}</span><br>
                                    </a>
                                    {{-- <span id="someId" class="hello" >{{$task->title}}</span><br> --}}
                                </td>

                                <td>
                                    {{-- {{ $task->user->name }} --}}
                                    {{ Auth::user()->name }}
                                </td>

                                <td>
                                    {{ (!empty($task->todoable)) ? $task->todoable->name : 'Null' }}
                                </td>

                                <td>
                                    {{ $task->description }}
                                </td>

                                <td>
                                    {{$task->priority}}
                                </td>

                                <td>
                                    <span
                                        {{-- class="{{
                                        ( ($task->due_time) >= ($task->currentDate()) ) ? 'badge badge-info' : 'badge badge-danger'
                                        }}" --}}
                                        >

                                        <i class='far fa-clock'></i>
                                        {{$task->checkOverdue()}}

                                    </span>
                                </td>

                                <td>
                                    {{-- {{ $task->created_at->diffForHumans() }} --}}
                                    {{ $task->started_at->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ (!empty($task->completed_at)) ? Carbon\Carbon::parse($task->completed_at)->format('d-m-Y') : 'Not Completed Yet' }}
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
                                                <a class="dropdown-item" href="{{  route('tasks.edit',$task->id) }}">
                                                    Edit
                                                </a>
                                            </small>
                                            {{-- <small>
                                                <a class="dropdown-item" href="{{  route('tasks.show',$task->id) }}" >
                                                    View
                                                </a>
                                            </small> --}}
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
                                </td>
                            </tr>





                            {{-- Modal Content Goes here Start --}}
                            {{-- <div class="modal fade" id="exampleModal-{{$task->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-{{$task->id}}" aria-hidden="true">
                             <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                               <div class="modal-content">
                                 <div class="modal-header">
                                    <div class="text-center">
                                        @if( !empty($task->todoable->picture) )

                                            <img
                                            class="rounded-circle img-responsive" width="50px" height="50px"
                                            src="{{asset($task->todoable->picture)}}"
                                            alt="message user image"
                                            />

                                            @else
                                            <img
                                            class="rounded-circle img-responsive" width="50px" height="50px"
                                            src="{{asset('defaultavatar.jpeg')}}"
                                            alt="message user image"
                                            />
                                        @endif
                                    </div>
                                   {{-- <h5 class="modal-title" id="exampleModalLabel-{{$task->id}}">View</h5> --}}
                                   {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                   </button>
                                 </div>
                                 <div class="modal-body">
                                    <div class="form-group">
                                        <label for="title" class="col-form-label">Title:</label>
                                        <input type="text" class="form-control" id="title" value="{{ $task->title }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="my-textarea">Text</label>
                                        <textarea id="my-textarea" class="form-control" name="" rows="3" readonly>{{ $task->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="relatedto" class="col-form-label">RelatedTo</label>
                                        <input type="text" class="form-control" id="relatedto" value="{{ (!empty($task->todoable)) ? $task->todoable->first_name : 'Null' }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="col-form-label">Type</label>
                                        <input type="text" class="form-control" id="type" value="{{ $task->tasktype->name ?? 'Null' }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="outcome" class="col-form-label">Outcome</label>
                                        <input type="text" class="form-control" id="outcome" value="{{ $task->outcome->name ?? 'Null' }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="duedate" class="col-form-label">Duedate</label>
                                        <input type="duedate" class="form-control" id="duedate" value="{{ $task->due_time }}" readonly>
                                    </div>
                                 </div>
                               </div>
                             </div>
                           </div> --}}
                           {{-- Modal Content Goes here End --}}



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
@endsection
