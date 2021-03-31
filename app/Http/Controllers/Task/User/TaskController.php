<?php

namespace App\Http\Controllers\Task\User;

use App\Todo;
// use App\User;
use App\Outcome;
use App\Tasktype;
use App\Tbl_leads;
use App\Tbl_projects;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTaskRequest;
use Illuminate\Database\Eloquent\Builder;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $tasks = Todo::with('todoable','user')
        //                     ->where('user_id', '=', Auth::user()->id)
        //                     ->latest()
        //                     ->paginate(5);

        $tasks = auth()->user()->currentuser()->with('todoable','tasktype','outcome')->latest()->get();

        return view('taskmanagement.cruds.user.index_tasktable',compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $outcomes = Outcome::all();
        $project = Tbl_projects::all();
        $tasktypes = Tasktype::all();
        $leads = auth()->user()->tbl_leads()->get();//This will show only leads created by curretn users

        return view('taskmanagement.cruds.user.create',compact('outcomes','tasktypes','leads','project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTaskRequest $request, Todo $todo)
    {
        $lead = Tbl_leads::find($request->todoable_id);

        $input = $request->validated();

        $input['user_id'] = Auth::user()->id;

        if ($request->todoable_id == '') {

            $input = $request->except(['todoable_id']);
            Todo::create($input);
            // dd($input);

        } else {

            // dd($input);
            $lead->todos()->create($input);
        }
        return redirect(route('taskmanagement.index'))->with('message', 'Task created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $taskmanagement)
    {
        return view('taskmanagement.cruds.user.view', compact('taskmanagement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $taskmanagement)
    {
        // dd($taskmanagement->priority);
        $outcomes = Outcome::all();
        $project = Tbl_projects::all();
        $tasktypes = Tasktype::all();
        $leads = auth()->user()->tbl_leads()->get();
        return view('taskmanagement.cruds.user.edit',compact('taskmanagement','outcomes','tasktypes','leads','project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTaskRequest $request, Todo $taskmanagement)
    {
        $leaddata = Tbl_leads::find($request->todoable_id);

            $input = $request->validated();

            if ($request->todoable_id == '') {

                $taskmanagement->update($input);
                // dd($input);

            } else {

                $input['todoable_id'] = $leaddata->ld_id;
                $input['todoable_type'] = get_class($leaddata);

                $taskmanagement->update($input);
                // dd($input);
            }

            return redirect(route('taskmanagement.index'))->with('success','task succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $taskmanagement)
    {
        $taskmanagement->delete();

        return back()->with('success', 'deleted successfully');
    }

    public function taskcompleted(Request $request, $id)
    {
        $task = Todo::findOrFail($id);
        // dd($task->getOriginal('outcome_id'));
        if($task->completed_at != Null) {//condition for update outcome column if checkbox is checked or not
            $outcome = 6;
            // $outcome = $task->getChanges('outcome_id');
        }
        else {
            $outcome = 9;
        }

        $task->update([
            'completed_at' => $task->completed_at ? null : now(),
            'outcome_id' => $outcome,
        ]);
            // dd($task);
        return back()->with('success','task updated successfully');
    }

    public function deletealltask(Request $request)
    {
        $ids = $request->ids;
        // dd($ids);
        Todo::whereIn('id',explode(",",$ids))->delete();

        return response()->json(['status'=>true,'message'=>"Task successfully deleted."]);
    }

    public function kanban()
    {
        $outcomes = Outcome::with(['todos' => function ($query) {
            $query->with('todoable','tasktype','outcome')->where('user_id', Auth::user()->id);
        }])->get();

        return view('taskmanagement.cruds.user.kanban',compact('outcomes'));
    }

    public function changedoutcomes(Request $request)

    {

        $dealIds = $request->input('todo_id');//get Todo id for task
        // dd(explode("_", $dealIds));
        $dealIds = explode("_", $dealIds);

        $d = count($dealIds) - 1;
        // dd($dealIds[$d]);
        $deal_id = $dealIds[$d];


        //below will check in which card it moved
        $stageIds = $request->input('outcome_id');//get outcome id

        $stageIds = explode("_", $stageIds);

        $s = count($stageIds) - 1;
        // dd($stageIds[$s]);
        $sfun_id = $stageIds[$s];


        //from card
        $fromIds = $request->input('from_id');

        $fromIds = explode("_", $fromIds);

        $f = count($fromIds) - 1;
        // dd($fromIds[$f]);
        $from_id = $fromIds[$f];



        $fromStage = Outcome::find($from_id);
        // dd($fromStage->name);
        $fromStageId = str_replace(" ", "_", $fromStage->name) . '_' . $from_id;


        $toStage = Outcome::find($sfun_id);
        // dd($toStage->name);
        $toStageId = str_replace(" ", "_", $toStage->name) . '_' . $sfun_id;

        $deals = Todo::find($deal_id);

        $deals->outcome_id = $sfun_id;

        $deal_status = null;

        if ($sfun_id == 9) {

            $deal_status = now();

        }

        $deals->completed_at = $deal_status;

        $res = $deals->save();

        if ($res) {

            $data['fromStageId'] =  $fromStageId;

            $data['toStageId'] =  $toStageId;

            return json_encode($data);

        } else {

            return 'error';

        }

    }

}
