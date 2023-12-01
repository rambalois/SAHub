<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SaManagerDashboardController extends Controller
{
    //
    public function index(){
        return view('sam.sam_dashboard');
    }

    public function onGoing(){
        $assignedTasks = DB::table('user_tasks')
        ->join('tasks', 'user_tasks.task_id', '=', 'tasks.id')
        ->join('users', 'user_tasks.user_id', '=', 'users.id')
        ->where('task_status_id',1)
        ->select('user_tasks.id','tasks.start_date','tasks.start_time','tasks.end_time','tasks.preffred_program','users.email' , 'tasks.to_be_done', 'tasks.assigned_office','user_tasks.hours_rendered' ,'tasks.note')
        ->orderby('user_tasks.id', 'asc')->paginate(5);    
        return view('sam.sam_dashboard_ongoing', compact('assignedTasks'));//,'user'
    }

    public function finished(){
        $assignedTasks = DB::table('user_tasks')
        ->join('tasks', 'user_tasks.task_id', '=', 'tasks.id')
        ->join('users', 'user_tasks.user_id', '=', 'users.id')
        ->where('task_status_id',2)
        ->select('user_tasks.id','tasks.start_date','tasks.start_time','tasks.end_time','tasks.preffred_program','users.email' , 'tasks.to_be_done', 'tasks.assigned_office','user_tasks.hours_rendered' ,'tasks.note')
        ->orderby('user_tasks.id', 'asc')->paginate(5);    
        return view('sam.sam_dashboard_done', compact('assignedTasks'));//,'user'
    }
}
