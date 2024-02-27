<?php

namespace App\Http\Controllers;

use App\Models\SaTaskTimeLog;
use App\Models\SaProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Rule;
use Illuminate\Support\Facades\Validator;

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
    public function index()// Does not do anything
    {
        $tasks = Task::all();
        return view('office.office_dashboard', compact('tasks'));
    }

    public function program()
    {
        $program = Courses::where('is_offered',true)->get();
        return $program;
    }

    public function StartTime()
    {

    }

    //view inactive tasks

    public function dashboard()
    {
        //$tasks = Task::all();
        $courses = $this->program();
        $user = $this->getuserID();
        $tasks = Task::where('isActive', true)->get();
        $tasksWithInfo = $this->processTasks($tasks);
        return view('office.office_dashboard', compact('user','tasksWithInfo','courses'));
    }

    private function processTasks($tasks)
    {
        $processedTasks = [];
        foreach($tasks as $task) {
        $startTime = strtotime($task->start_time); // Convert to Unix timestamp
        $endTime = strtotime($task->end_time);
        $task->startTimeFormatted = date("h:i A", strtotime($task->start_time));
        $task->endTimeFormatted = date("h:i A", strtotime($task->end_time));
        $task->totalHours = round(($endTime- $startTime) / 3600, 1); // Assumes these are timestamps
        $task->saCount = DB::table('user_tasks_timelog')
                               ->where('task_id', $task->id)
                               ->where('task_status', 1)
                               ->count();
        $processedTasks[] = $task;
    }
        return $processedTasks;
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
            session()->flash('success', 'Student Assistant full!!');
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
            'user_tasks_timelog.total_hours',
            'sa_profiles.user_id',
            'sa_profiles.first_name',
            'sa_profiles.last_name',
            'sa_profiles.course_program',
            DB::raw('DATE_FORMAT(user_tasks_timelog.time_in, "%H:%i") AS timein'), 
            DB::raw('DATE_FORMAT(user_tasks_timelog.time_out, "%H:%i") AS timeout'),
        )
        ->where('tasks.id','=', $taskId)
        //->groupBy( 'sa_profiles.user_id','sa_profiles.first_name', 'sa_profiles.last_name', 'sa_profiles.course_program','timein','timeout' )
        ->orderBy('user_tasks_timelog.updated_at', 'DESC')
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
            $query->having('total_hours', '<=', 89);
        }elseif ($status === 'completed') {
            $query->having('total_hours','>=', 90 ); 
        }

        return $query->get(); 
    }

    public function saReport($status="completed")
    {
        $user = $this->getuserID(); 
        $saLists = $this->getSaData($status); 
        

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
        $now = Carbon::now();
        $user = $this->getuserID();

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => [
                'required',
                'date_format:H:i', 
                function ($attribute, $value, $fail) use ($now) {
                    $startTime = Carbon::parse($value);
                    if ($startTime->lt(Carbon::parse('08:00')) || $startTime->gt(Carbon::parse('22:00')) || $startTime->lt($now)) {
                        $fail('Start time must be between 8 AM and 10 PM, and after the current time.');
                    }
                },
            ],
            'end_time' => [
                'required', 
                'date_format:H:i', 
                'after:start_time',  
                function ($attribute, $value, $fail) {
                    $endTime = Carbon::parse($value);
                    if ($endTime->gt(Carbon::parse('22:00'))) {
                        $fail('End time must be before or at 10 PM.');
                    }
                },
            ],
            'number_of_sa' => 'required|integer',
            'preffred_program' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value && !DB::table('courses')->where('name', $value)->exists()) {
                        $fail('The selected preferred program is invalid.');
                    }
                },
            ],
            'assignment_type' => 'required|in:1,2',
            'to_be_done' => 'nullable|string', 
            'note' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator)
                         ->withInput(); // Preserve user input
        }

        $validatedData = $validator->validated(); // Get validated data 
        $task = new Task($validatedData);   
        $task->office_id = $user->id;
        $task->assigned_office = $user->faculty;
        $task->save();

        if ($task['assignment_type'] == 1) {
            $this->handleAutoAssignment($task, $validatedData);
        } else {
            $this->handleVoluntary($task);
        }

        // Return success response
        return redirect()->route('office.dashboard')->with('success', 'Task added successfully!'); 
    }

    private function handleAutoAssignment(Task $task, array $data)
    {   
        // Find SAs with matching program (if provided)
        $eligibleSAs = SaProfile::where('course_program', $data['preffred_program'])
                               ->get();
        // Find SAs with available time slots
        $availableSAs = $this->findSAsWithAvailability($eligibleSAs, $task);

        // Assign the task 
        $this->assignTaskToSAs($task, $availableSAs); 
    }

    private function findSAsWithAvailability($eligibleSAs, Task $task) 
    {
        return $eligibleSAs->filter(function($sa) use ($task) {
            // Check if the SA has any schedule conflicts
            return !DB::table('student_schedules')
                ->join('subject_offerings', 'subject_offerings.id', '=', 'student_schedules.subject_offering_id')
                ->join('subject_offering_details', 'subject_offering_details.subject_offering_id', '=', 'subject_offerings.id')
                ->where(function ($query) use ($task) {
                    $query->where(function($query) use ($task) { // Check for starts within task time
                            $query->whereRaw('SUBSTRING_INDEX(subject_offering_details.time_constraints, "-", 1) BETWEEN ? AND ?', [$task->start_time, $task->end_time]);
                        })
                        ->orWhere(function($query) use ($task) { // Check for ends within task time
                            $query->whereRaw('SUBSTRING_INDEX(subject_offering_details.time_constraints, "-", -1) BETWEEN ? AND ?', [$task->start_time, $task->end_time]);
                        });
                })
                ->exists();
        }); 
    }

    private function assignTaskToSAs(Task $task, $availableSAs)
    {
        $saIndex = 0; 
        $numberOfSAsNeeded = $task->number_of_sa; // Or retrieve dynamically if your task can handle a range

        foreach ($availableSAs as $sa) {

            // Notify assigned SA (to be implemented i think)
            //$this->notifySA($sa, $task); 

            $this->acceptTaskAndLog($task, $sa); 

            $saIndex++;
            if ($saIndex >= $numberOfSAsNeeded) {
                break; 
            }
        }
    }

    private function acceptTaskAndLog(Task $task, SaProfile $sa)
{
    // Assuming 'user_id' represents the SA in your user_tasks_timelog table
    $taskLog = new SaTaskTimeLog([
        'task_id' => $task->id,
        'user_id' => $sa->id,
    ]);
    $taskLog->task_status = 1;
    $taskLog->save();
}

    private function handleVoluntary(Task $task)
    {
        $task->status = 'pending';
        $task->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //


        $task = Task::findOrFail($id);

        //dd($request->all());
        $validatedData = $request->validate([
            // ... your other validations
            'start_time' => 'required', 
            'end_time' => 'required',
        ]);
    
        // Update the time portions
        $task->start_time = $validatedData['start_time']; 
        $task->end_time = $validatedData['end_time']; 

        //$task->update($request->all());

        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'number_of_sa' => 'required|integer',
            'preffred_program' => 'nullable|string',
            'assignment_type' => 'nullable|in:1,2',
            'to_be_done' => 'nullable|string',
            'note' => 'nullable|string',
        ]);
    
        // Directly update the task properties
        $task->start_date = $validatedData['start_date'];
        $task->start_time = $validatedData['start_time'];
        $task->end_time = $validatedData['end_time'];
        $task->number_of_sa = $validatedData['number_of_sa'];
        $task->preffred_program = $validatedData['preffred_program'];
        $task->assignment_type = $validatedData['assignment_type'];
        $task->to_be_done = $validatedData['to_be_done'];
        $task->note = $validatedData['note'];

        /* Check for button action
        if ($request->input('action') === 'save_draft') {
            $task->isActive = 0; // Save as draft
        } else {
            $task->isActive = 1; // Repost task
            //$task->;
        }*/

        $task->save();
        
        return redirect()->back()->with('success', 'Task edited successfully!');
    }

    public function addTask()
    {
        $courses = $this->program();
        $user = $this->getuserID();
        return view('office.office_add_task',compact('user','courses'));
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
