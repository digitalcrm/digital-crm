<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Tasktype;
use Illuminate\Http\Request;

class TasktypeController extends Controller
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
        $types = Tasktype::all();
        return view('taskmanagement.tasktype.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Tasktype $tasktype)
    {
        return view('taskmanagement.tasktype.create', compact('tasktype'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tasktypes|max:35',
        ]);

        Tasktype::create([
            'name' => $request->name,
        ]);
        // dd($value);

        return redirect(route('tasktypes.index'))->with('success', 'tasktype created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tasktype $tasktype)
    {
        return view('taskmanagement.tasktype.edit', compact('tasktype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tasktype $tasktype)
    {
         $request->validate([
            'name' => 'required|unique:tasktypes|max:35',
        ]);

        $tasktype->update([
            'name' => $request->name,
        ]);

        return redirect(route('tasktypes.index'))->with('success', 'tasktype updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tasktype $tasktype)
    {
        $tasktype->delete();
        return redirect(route('tasktypes.index'))->with('success', 'tasktype deleted successfully');
    }
}
