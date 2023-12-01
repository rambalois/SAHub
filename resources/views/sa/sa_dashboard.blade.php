@extends('layouts.app')

@section('title', 'Dashboard - Student Assistant')

@section('content')
    <!-- Your content here -->
    @include('include.nav_bar')
    <div>
        <div style="padding: 3em;">
            <section>
                <h1>Voluntary Tasks</h1>
                    @if (session('accept_task_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('accept_task_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </div>
                    @endif
                <div class="table-responsive" style="padding: 1em;">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col" style="background: #d9d9d9;">TASK NO.</th>
                                <th scope="col" style="background: #d9d9d9;">DATE &amp; TIME</th>
                                <th scope="col" style="background: #d9d9d9;">PROGRAM</th>
                                <th style="background: #d9d9d9;">OFFICE</th>
                                <th style="background: #d9d9d9;">NOTE</th>
                                <th style="background: #d9d9d9;"></th>
                            </tr>
                        </thead>
                        <tbody>
                                @if($urgentTasks->count() == 0)
                                    <tr>
                                        <td data-label="Attributes" scope="row" colpan="5"><strong> No Urgent Task Available </strong></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                                @else
                                    @foreach ($urgentTasks as $task)
                                        <tr>
                                            <td data-label="Attributes" scope="row">{{ $task->id }}</td>
                                            <td data-label="Base Class">{{ $task->created_at }}</td>
                                            <td data-label="Simulated Case">{{ $task->program }}</td>
                                            <td>{{ $task->office }}</td>
                                            <td>{{ $task->note }}</td>
                                            <form action="{{ route( 'sa.accept', $task->id ) }}" method="post">
                                                @csrf
                                                <td>
                                                    <input type="hidden" name="task_id" value="{{ $task->id }}">    
                                                    <button type="submit" class="btn btn-primary">Accept</button>
                                                </td>
                                            </form>
                                            
                                        </tr>
                                        
                                    @endforeach
                                @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div style="padding: 3em;border-top-style: groove;">
            <section>
                <h1>Assigned Tasks</h1>
                <div class="table-responsive" style="padding: 1em;">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th  scope="col" style="background: #d9d9d9;">TASK NO.</th>
                                <th  scope="col" style="background: #d9d9d9;">DATE &amp; TIME</th>
                                <th  scope="col" style="background: #d9d9d9;">PROGRAM</th>
                                <th  style="background: #d9d9d9;">Task</th>
                                <th  style="background: #d9d9d9;">Office</th>
                                <th  style="background: #d9d9d9;">Note</th>
                            </tr>
                        </thead>
                        <tbody >
                            @if($assignedTasks->count() == 0)
                                    <tr>
                                        <td data-label="Attributes" scope="row" colpan="5"><strong> No Ongoing Task Available </strong></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                            @else
                                @foreach ($assignedTasks as $assignedtask)
                                    <tr>
                                        <td data-label="Attributes" scope="row">{{ $assignedtask->id }}</td>
                                        <td data-label="Base Class">
                                            <p style="margin: 0px;">{{ $assignedtask->start_date }}</p>
                                            <p style="font-size: 12px;">{{ $assignedtask->start_time }} - {{ $assignedtask->end_time }}</p>
                                        </td>
                                        <td data-label="Simulated Case">{{ $assignedtask->preffred_program }}</td>
                                        <td>{{ $assignedtask->to_be_done }}</td>
                                        <td>
                                            <p style="margin: 0px;">{{ $assignedtask->assigned_office }}</p>
                                            <p style="font-size: 12px;">{{ $assignedtask->email }}</p>
                                        </td>
                                        <td>{{ $assignedtask->note }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>


    @include('nav.offcanvas_menu_sa')
@endsection