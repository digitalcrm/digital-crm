@extends('layouts.adminlte-boot-4.admin')

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Tasks <small class="badge badge-secondary">Total count</small></h1>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="row">
            <div class="col-12">
                @include('taskmanagement.includes.message')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ion ion-clipboard mr-1"></i>
                            Add Task
                            <a href="{{ route('tasks.create') }}"><i class="fas fa-plus"></i></a>
                        </h3>


                        <div class="card-tools">
                            {{$tasks->links()}}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <ul class="todo-list" data-widget="todo-list">
                            @forelse($tasks as $task)
                            <li>
                                <div class="row">
                                    <div class="col-2">
                                        <!-- drag handle -->
                                        {{-- <span class="handle">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </span> --}}
                                        <!-- checkbox -->
                                        <div class="icheck-primary d-inline ml-2">
                                            <input
                                            type="checkbox"
                                            value=""
                                            name="todo1"
                                            id="todoCheck-{{$task->id}}"
                                            />
                                            <label for="todoCheck-{{$task->id}}"></label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <!-- todo text -->
                                        <span class="text">{{$task->title}}</span>
                                        <br>
                                        <span class="{{
                                            ( ($task->due_time) >= ($task->currentDate()) ) ? 'badge badge-info' : 'badge badge-danger'
                                            }}">

                                            <i class='far fa-clock'></i>
                                            {{$task->checkOverdue()}}

                                        </span>

                                        <p style="justify-content: left;">
                                            {{$task->description}}
                                        </p>
                                    </div>

                                    <div class="col-3">
                                        @if(!empty($task->todoable))
                                        <div>
                                            <small>Related to</small>
                                        </div>
                                        <div class="mt-0">
                                            @if( !empty($task->todoable->picture) )

                                            <img
                                            class="direct-chat-img"
                                            src="{{asset($task->todoable->picture)}}"
                                            alt="message user image"
                                            />

                                            @else
                                            <img
                                            class="direct-chat-img"
                                            src="{{asset('defaultavatar.jpg')}}"
                                            alt="message user image"
                                            />
                                            @endif
                                            <a href="#"
                                            ><strong class="mx-4">{{$task->lead->first_name}}</strong></a
                                            >
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-3">
                                        <!-- General tools such as edit or delete-->
                                        <div class="tools">
                                            <a href="{{ route('tasks.edit',$task->id) }}">
                                                <i class="fas fa-edit mx-2">
                                                </i>
                                            </a>
                                            <a
                                            class="swalDefaultSuccess"
                                            href=""
                                            onclick="event.preventDefault();
                                            if(confirm('Are you sure!')){
                                                $('#form-delete-{{$task->id}}').submit();
                                            }
                                            ">
                                            <i class="fas fa-trash"></i>
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
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <p class="text-center">No Tasks is available</p>
                    @endforelse
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</section>

</div>
@endsection
