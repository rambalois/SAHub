<?php

namespace App\Http\Controllers;

use App\Models\SaTaskTimeLog;
use App\Models\UserTask;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Courses;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\DB;  


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

    public function program()
    {
        $program = Courses::where('is_offered',true)->get();
        return $program;
    }

    //view active tasks
    public function active()
    {
        //$tasks = Task::all();
        $courses = $this->program();
        $user = $this->getuserID();
        $tasks = Task::where('isActive', true)->get();
        
        return view('office.office_dashboard_active', compact('tasks','user','courses'));
    }

    //view inactive tasks

    public function dashboard()
    {
        //$tasks = Task::all();
        $courses = $this->program();
        $user = $this->getuserID();
        $tasks = Task::where('isActive', true)->get();
        return view('office.office_dashboard', compact('user','tasks','courses'));
    }

    public function taskView()
    {
        return view('office.office_task_review');
    }

    public function getuserID()
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        return $user;
    }

    public function saCounter($num_sa,$task)
    {
        $saCount = DB::table('user_tasks_timelog')
        ->where('task_id',$task->id)
        ->where('task_status',1)
        ->count();
        if ($saCount < $num_sa){
            session()->flash('add_task_success', 'Student Assistant full!!');
        }
    }
    
    public function taskSaList(Request $request)
    {
        $user = $this->getuserID();
        $taskId = $request->route('taskId');

        $saLists = User::join('user_tasks_timelog','users.id','=','user_tasks_timelog.user_id')
        ->join('tasks','user_tasks_timelog.task_id','=','tasks.id')
        ->join('sa_profiles','users.id_number','=','sa_profiles.user_id')
        ->select(
            'user_tasks_timelog.id AS timelogId',
            'user_tasks_timelog.feedback',
            'sa_profiles.user_id',
            'sa_profiles.first_name',
            'sa_profiles.last_name',
            'sa_profiles.course_program',
            'tasks.start_date',
            DB::raw('DATE_FORMAT(user_tasks_timelog.time_in, "%H:%i") AS timein'), 
            DB::raw('DATE_FORMAT(user_tasks_timelog.time_out, "%H:%i") AS timeout'),
        )
        ->where('tasks.id','=', $taskId)
        ->get();
       
        return view('office.salist_task', compact('saLists','user','taskId'));
    }

    public function addFeedback(Request $request)
    {   
        $timeLogId = $request->input('timelogId');
        $feedback = $request->input('feedback');

        // Retrieve the specific time log using first()
        $timeLog = SaTaskTimeLog::where('id', $timeLogId)->first();

        if ($timeLog) {
            $timeLog->feedback = $feedback;
            $timeLog->save();

            return redirect()->back()->with('success', 'Feedback saved successfully!');
        } else {
            // Handle the error case where a time log is not found
            return redirect()->back()->with('error', 'Time log not found.');
        }
    }

    public function getSaData($status)
    {
        $query = User::join('user_tasks_timelog','users.id','=','user_tasks_timelog.user_id')
        ->join('sa_profiles','users.id_number','=','sa_profiles.user_id')
        ->select(
            'users.id_number',
            'sa_profiles.first_name',
            'sa_profiles.last_name',
            'users.email',
            DB::raw('SUM(user_tasks_timelog.total_hours) as total_hours')
        )
        ->where('user_tasks_timelog.total_hours','!=', null)
        ->groupBy('users.id_number', 'sa_profiles.first_name', 'sa_profiles.last_name', 'users.email'); // Group by these fields
        
        if ($status === 'ongoing') {
            $query->where('user_tasks_timelog.total_hours', '<', 90);
        } elseif ($status === 'completed') {
            $query->where('user_tasks_timelog.total_hours', 90); 
        }
    
        return $query->get(); 
    }

    public function saReport($status='ongoing')
    {
        $user = $this->getuserID(); 
        $saLists = $this->getSaData($status);  // Use a helper function (explained below)
        

        return view('reports.sa_report', ['status'=>$status,'saLists'=>$saLists, 'user'=>$user]);
    }

    public function officeReport()
    {
        $user = $this->getuserID();
        $officeLists = User::join('tasks', 'users.id', '=', 'tasks.office_id') // Users who posted tasks
            ->join('user_tasks_timelog', 'tasks.id', '=', 'user_tasks_timelog.task_id') // Tasks with logged work
            ->select(
                'users.faculty',
                DB::raw('COUNT(distinct tasks.id) as total_tasks_posted'), // Distinct count for accurate reporting
                DB::raw('COUNT(distinct user_tasks_timelog.user_id) as total_accepted_sa'),
                DB::raw('SUM(user_tasks_timelog.total_hours) as total_rendered_hours')
            )
            ->where('user_tasks_timelog.total_hours', '!=', null) // Keep focus on completed tasks 
            ->groupBy('users.faculty') 
            ->get();
       

        return view('reports.office_report', ['officeLists'=>$officeLists,'user'=>$user]);
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
        //$task->end_date = $request->input('end_date');
        $task->start_time = $request->input('start_time');
        $task->end_time = $request->input('end_time');
        $task->number_of_sa = $request->input('number_of_sa');
        $task->status = $request->input('status');
        $task->assignment_type = $request->input('assignment_type');
        $task->to_be_done = $request->input('to_be_done');
        $task->note = $request->input('note');
        $task->assigned_office = $request->input('assigned_office');
        $task->save();

        return redirect()->back()->with('success', 'Task added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $task = Task::findOrFail($id);

        $task->update($request->all());
        /* Check for button action
        if ($request->input('action') === 'save_draft') {
            $task->isActive = 0; // Save as draft
        } else {
            $task->isActive = 1; // Repost task
            //$task->;
        }*/

        $task->save();
        
        return redirect()->back()->with('success', 'Task editted successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(string $id)
    {
        //
        $task = Task::findOrFail($id);
        $task->isActive = false;
        $task->deleted_at = now();
        $task->save();

        return redirect()->back()->with('success', 'Task cancelled successfully!');

    }
    public function delete(string $id)
    {
        //
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully!');

    }
}
