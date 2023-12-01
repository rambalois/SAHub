<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Session;
use App\Models\User;

class OfficeAdminDashboardController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     */
    //View all tasks - not used
     public function index()
    {
        $tasks = Task::all();
        return view('office.office_dashboard', compact('tasks'));
    }

    //view active tasks
    public function active()
    {
        //$tasks = Task::all();
        $user = $this->getuserID();
        $tasks = Task::where('isActive', true)->get();
        return view('office.office_dashboard_active', compact('tasks','user'));
    }

    //view inactive tasks

    public function inactive()
    {
        //$tasks = Task::all();
        $user = $this->getuserID();
        $tasks = Task::where('isActive', false)->get();
        return view('office.office_dashboard_inactive', compact('user','tasks'));
    }

    public function getuserID()
    {
        //
        $user_id = session('user_id');
        $user = User::find($user_id);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function store(Request $request)
    {
        //
        $task = new Task();
        $task->preffred_program = $request->input('preffred_program');
        $task->start_date = $request->input('start_date');
        $task->end_date = $request->input('end_date');
        $task->start_time = $request->input('start_time');
        $task->end_time = $request->input('end_time');
        $task->number_of_sa = $request->input('number_of_sa');
        $task->status = $request->input('status');
        $task->assignment_type = $request->input('assignment_type');
        $task->to_be_done = $request->input('to_be_done');
        $task->note = $request->input('note');
        //$task->assigned_office = $request->input('assigned_office');
        $task->save();

        session()->flash('add_task_success', 'Task added successfully!');
        return redirect()->route('office.admin.active.dashboard');
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
        $task->end_date = $request->input('end_date');
        $task->number_of_sa = $request->input('number_of_sa');
        $task->status = $request->input('status');
        $task->assignment_type = $request->input('assignment_type');
        $task->to_be_done = $request->input('to_be_done');
        $task->note = $request->input('note');
        $task->assigned_office = $request->input('assigned_office');
        $task->save();
        
        session()->flash('edit_task_success', 'Task edited successfully!');
        //return redirect()->route('tasks.show', $task);
        return redirect()->route('office.admin.inactive.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(string $id)
    {
        //
        $task = Task::findOrFail($id);
        $task->isActive = false;
        $task->delete_at = now();
        $task->save();

        session()->flash('cancel_task_success', 'Task canceled successfully!');
        return redirect()->route('office.admin.inactive.dashboard');

    }
    public function delete(string $id)
    {
        //
        $task = Task::findOrFail($id);
        $task->delete();

        session()->flash('delete_task_success', 'Task deleted successfully!');
        return redirect()->route('office.admin.inactive.dashboard');

    }
}
