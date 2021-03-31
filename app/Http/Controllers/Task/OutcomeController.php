<?php

namespace App\Http\Controllers\Task;

use App\Outcome;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OutcomeController extends Controller
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
        $outcomes = Outcome::all();
        return view('taskmanagement.outcome.index', compact('outcomes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Outcome  $outcome)
    {

        return view('taskmanagement.outcome.create', compact('outcome'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Outcome $outcome)
    {
        $request->validate([
            'name' => 'required|unique:outcomes|max:35',
        ]);

        Outcome::create([
            'name' => $request->name,
        ]);
        // dd($value);

        return redirect(route('outcomes.index'))->with('success', 'outcome created successfully');
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
    public function edit(Outcome $outcome)
    {
        return view('taskmanagement.outcome.edit', compact('outcome'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outcome $outcome)
    {
        $request->validate([
            'name' => 'required|unique:outcomes|max:35',
        ]);

        $outcome->update([
            'name' => $request->name,
        ]);

        return redirect(route('outcomes.index'))->with('success', 'outcome updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outcome $outcome)
    {
        $outcome->delete();
        return redirect(route('outcomes.index'))->with('success', 'outcome deleted successfully');
    }
}
