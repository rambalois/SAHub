<?php

namespace App\Http\Controllers;

use App\Models\SaProfile;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;  
use App\Models\UserTask;
use App\Models\User;

class SaDashboardController extends Controller
{
    //

    public function index(){
        $user = $this->getuserID();
        $urgentTasks = Task::where('assignment_type', 2)->orderby('id', 'asc')->paginate(3);
        $assignedTasks = DB::table('user_tasks')
        ->join('tasks', 'user_tasks.task_id', '=', 'tasks.id')
        ->join('users', 'user_tasks.user_id', '=', 'users.id')
        ->where('users.id', $user->id)
        ->where('task_status_id',1)
        ->select('user_tasks.id','tasks.start_date','tasks.start_time','tasks.end_time','tasks.preffred_program','users.email' , 'tasks.to_be_done', 'tasks.assigned_office', 'tasks.note')
        ->orderby('user_tasks.id', 'asc')->paginate(5);    
        return view('sa.sa_dashboard', compact('urgentTasks','assignedTasks','user'));
    }

    public function getuserID()
    {
        //
        $user_id = session('user_id');
        $user = User::find($user_id);
        return $user;
    }

    public  function acceptTask($id){
        $user = $this->getuserID();
        $task = Task::find($id);
        $userTask = new UserTask();
        $userTask->user_id = $user->id;
        $userTask->task_id = $task->id;
        $userTask->task_status_id = 1;
        $userTask->save();

        session()->flash('accept_task_success', 'Task accepted successfully!');
        return redirect()->route('sa.dashboard');
    }

    public function profile(){
        $user = $this->getuserID();
        $userProfile = SaProfile::where('user_id', $user->id)->get();
        $taskHistory = DB::table('user_tasks')
        ->join('tasks', 'user_tasks.task_id', '=', 'tasks.id')
        ->join('users', 'user_tasks.user_id', '=', 'users.id')
        ->where('users.id', $user->id)
        ->select('tasks.id','tasks.start_date','tasks.preffred_program', 'tasks.to_be_done', 'tasks.assigned_office', 'tasks.note', 'user_tasks.hours_rendered', 'user_tasks.feedback')
        ->orderby('user_tasks.id', 'asc')->paginate(5);
        return view('sa.sa_profile', compact('user','userProfile', 'taskHistory'));
    }


}
