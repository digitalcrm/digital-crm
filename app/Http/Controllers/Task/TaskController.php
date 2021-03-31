<?php

namespace App\Http\Controllers\Task;

use App\Todo;
use App\User;
use App\Outcome;
use App\Tasktype;
use App\Tbl_leads;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTaskRequest;

class TaskController extends Controller
{
    public function __construct() {

        $this->middleware('auth:admin');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        // $tasks = Todo::where('admin_id', '=', Auth::user()->id)->with('todoable')->latest()->paginate(5);
        // $tasks = auth()->user()->currentadmin()->with('todoable')->latest()->paginate(5);
        $tasks = Todo::with('todoable')->latest()->paginate(5);
        // dd($tasks);
        return view('taskmanagement.cruds.index_tasktable',compact('tasks'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Todo $task)
    {
        $outcomes = Outcome::all();
        $tasktypes = Tasktype::all();
        $users = User::where('active', '=', 1)->latest()->get();
        return view('taskmanagement.cruds.create',compact('task','outcomes','tasktypes','users'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(CreateTaskRequest $request, Todo $todo)
    {
        // dd($request->all());
        $user = User::find($request->todoable_id);

        $input = $request->validated();

        $input['admin_id'] = Auth::user()->id;

        if ($request->todoable_id == '') {

            // $input = $request->except(['todoable_id']);
            // dd($input);
            Todo::create($input);


        } else {

            $user->todos()->create($input);
        }

            // dd($input);

            return redirect(route('tasks.index'))->with('message', 'Task created successfully');
        }

        /**
        * Display the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function show(Todo $task)
        {
            return view('taskmanagement.cruds.view', compact('task'));
        }

        /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function edit(Todo $task)
        {
            // dd($task);
            $outcomes = Outcome::all();
            $tasktypes = Tasktype::all();
            $users = User::where('active', '=', 1)->latest()->get();
            return view('taskmanagement.cruds.edit',compact('task','outcomes','tasktypes','users'));
        }

        /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function update(CreateTaskRequest $request, Todo $task)
        {
            $user = User::find($request->todoable_id);

            $input = $request->validated();

            if ($request->todoable_id == '') {

                $task->update($input);

            } else {

                $input['todoable_id'] = $user->id;
                $input['todoable_type'] = get_class($user);

                $task->update($input);
                // dd($input);
            }

            return redirect(route('tasks.index'))->with('success','succesfully updated');

        }

        /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function destroy(Todo $task)
        {
            $task->delete();

            return back()->with('success', 'deleted successfully');
        }

        public function taskcompleted(Request $request, $id)
        {
            $task = Todo::findOrFail($id);

            $task->update([
                'completed_at' => $task->completed_at ? null : now()
            ]);

            return back()->with('success','task updated successfully');
        }
    }
