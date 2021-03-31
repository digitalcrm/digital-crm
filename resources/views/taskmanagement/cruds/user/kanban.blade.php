@extends('layouts.adminlte-boot-4.user')

@section('content')


<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-4">
                    <h1>Tasks <small class="badge badge-secondary" id="total"></small></h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                            class="btn btn-sm  btn-primary mr-1 px-3"
                            href="{{ route('taskmanagement.create') }}"><i class="far fa-plus-square mr-1"></i>New Task
                            </a>
                            {{-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal2">
                                <i class="fa fa-filter"></i>
                            </button> --}}
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('taskmanagement.index') }}">TableView</a>
                        </li>
                    </ol>
                </div>
            </div>

        </div>

    </section>

    <section class="content mt-2 mx-0">
        <div class="conatiner-fluid">
            <div class="row">
                @include('taskmanagement.includes.message')
                <div class="col-lg-12">

                    <div class="card shadow card-primary card-outline">
                        <div class="card-body" id="table">

                            <div id="kanban" class="row">
                                {{-- card loop start here--}}
                                @foreach($outcomes as $outcome)
                                <div class="col-3">

                                    <div class="card card-default"">

                                        <div class="card-header kanban-card">

                                            <h3 class="card-title">{{ $outcome->name }}</h3>

                                        </div>

                                        <div class="card-body">

                                            {{-- dynamic id will call --}}
                                            <div id="{{ Str::replaceFirst(' ','_',$outcome->name) }}_{{$outcome->id}}" class="dlstage">&nbsp;

                                                {{-- Loop start outcomes has task--}}
                                                @foreach($outcome->todos as $todo)
                                                <div id="card_{{ $todo->id }}" class="kanban-card" role="alert">

                                                    <div class="callout callout-success">
                                                        <div class="info-box-content">
                                                            <span>
                                                                <a
                                                                    style="color: blue"
                                                                    class="card-link"
                                                                    href="{{ route('taskmanagement.show',$todo->id) }}">
                                                                    {{$todo->title}}
                                                                </a>
                                                            </span><br>

                                                            <span>
                                                                {{ (!empty($todo->todoable)) ? $todo->todoable->first_name : 'Null' }}
                                                            </span><br>

                                                            <span>{{ $todo->tasktype->name ?? 'Task type not defined'}}</span><br>

                                                            @if ($todo->priority == 1)
                                                            <span class='dot dot-sm dot-success'></span>Low<br>
                                                            @elseif($todo->priority == 2)
                                                            <span class='dot dot-sm dot-warning'></span>Medium<br>
                                                            @else
                                                            <span class="dot dot-sm dot-danger"></span>High<br>
                                                            @endif

                                                            <span>{{ $todo->checkOverdue() }}</span><br>

                                                            <a class="btn btn-info" href="{{  route('taskmanagement.edit',$todo->id) }}">
                                                                <i class="far fa-edit"></i>
                                                            </a>
                                                            <a
                                                            class="btn btn-danger"
                                                            href=""
                                                            onclick="event.preventDefault();
                                                            if(confirm('Are you sure!')){
                                                                $('#form-delete-{{$todo->id}}').submit();
                                                            }
                                                            ">
                                                            <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                            <form
                                                            style="display:none"
                                                            method="post"
                                                            id="form-delete-{{$todo->id}}"
                                                            action="{{route('taskmanagement.destroy',$todo->id)}}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('delete')
                                                            </form>


                                                        </div>

                                                    </div>

                                                </div>
                                                @endforeach
                                                {{-- loop end --}}
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                @endforeach
                                {{-- loop end card --}}


                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
<script type="text/javascript">

    window.onload = function() {

        var stageIdsArr = [];

        $(".dlstage").each(function() {

            // alert($(this).attr('id'));

            var stId = "#" + $(this).attr('id');

            stageIdsArr.push(document.querySelector(stId));

        });

        var dragulaCards = dragula(stageIdsArr);

        dragulaCards.on('drop', function(el, target, source, sibling) {

            changeDealStage(el.id, target.id, source.id);

        });

        var dragulaKanban = dragula([

        document.querySelector('#kanban')

        ], {

            moves: function(el, container, handle) {

                return handle.classList.contains('card-title');

            }

        });

    }
</script>

<script>
    var changedealstagedragndrop = "{{url('tasks/kanban/changedoutcomes/{todo_id}/{outcome_id}/{from_id}')}}";
    //Drag and drop feature
    function changeDealStage(todo_id, outcome_id, from_id) {
        // alert(todo_id + ' ' + outcome_id + ' ' + from_id);

        // alert(changedealstagedragndrop);

        $.get(changedealstagedragndrop, {

            'todo_id': todo_id,

            'outcome_id': outcome_id,

            'from_id': from_id,

        },function(result, status) {

            // alert(result);

            var res = eval("(" + result + ")");

        });

    }

</script>

@endsection
