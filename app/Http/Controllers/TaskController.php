<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Task::all();
        return view('office.office_dashboard', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        //return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $task = new Task();
        $task->preffred_program = $request->input('preffred_program');
        $task->start_date = $request->input('start_date');
        //$task->end_date = $request->input('end_date');
        $task->start_time = $request->input('start_time');
        $task->end_time = $request->input('end_time');
        $task->number_of_sa = $request->input('number_of_sa');
        $task->status = $request->input('status');
        $task->assignment_type = $request->input('assignment_type');
        $task->to_be_done = $request->input('to_be_done');
        $task->note = $request->input('note');
        //$task->assigned_office = $request->input('assigned_office');
        $task->save();

        return redirect()->route('office.admin.active.dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        //return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        //return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $task = Task::findOrFail($id);

        $task->preffred_program = $request->input('preffred_program');
        $task->start_date = $request->input('start_date');
        //$task->end_date = $request->input('end_date');
        $task->number_of_sa = $request->input('number_of_sa');
        $task->status = $request->input('status');
        $task->assignment_type = $request->input('assignment_type');
        $task->to_be_done = $request->input('to_be_done');
        $task->note = $request->input('note');
        $task->assigned_office = $request->input('assigned_office');
        $task->save();
    
        //return redirect()->route('tasks.show', $task);
        return redirect()->route('office.admin.inactive.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
