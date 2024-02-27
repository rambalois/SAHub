@extends('layouts.app')

@section('title', 'Dashboard - Student Assistant Manager')

@section('content')
    <!-- Your content here -->
    @include('include.nav_bar')

    <div>
        <div class="d-flex d-lg-flex justify-content-center justify-content-lg-center align-items-lg-center" style="padding: 3em;">
            <div class="row">
                    <div class="col text-center" style="margin: auto;border-bottom-style: none;padding: 1em;">
                        <button onclick="window.location.href='{{ route('sa.manager.dashboard.ongoing') }}'" class="btn btn-secondary" type="button" style="width: 20em;color: rgb(0,0,0);font-weight: bold;height: 3em;font-size: 1em;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;">
                            On-going
                        </button>
                    </div>
                    <div class="col text-center" style="margin: auto;padding: 1em;">
                        <button onclick="window.location.href='{{ route('sa.manager.dashboard.done') }}'" class="btn btn-warning" type="button" style="width: 20em;color: rgb(0,0,0);font-weight: bold;margin: auto;height: 3em;font-size: 1em;border-style: none;border-top-style: none;border-right-style: none;border-bottom-style: none;border-left-style: none;">
                            Done
                        </button>
                    </div>
            </div>
        </div>
        <div style="padding: 3em;border-top-style: groove;">
            <section>
                <h1>Completed Tasks</h1>
                <div class="table-responsive" style="padding: 1em;">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col" style="background: #FFBD59;">TASK NO.</th>
                                <th scope="col" style="background: #FFBD59;">DATE &amp; TIME</th>
                                <th scope="col" style="background: #FFBD59;">PROGRAM</th>
                                <th style="background: #FFBD59;">Task</th>
                                <th style="background: #FFBD59;">Office</th>
                                <th style="background: #FFBD59;">Note</th>
                                <th style="background: #FFBD59;">Hours</th>
                                <th style="background: #FFBD59;">SA</th>
                            </tr>
                        </thead>
                        <tbody>
                                @if($assignedTasks->count() == 0)
                                    <tr>
                                        <td data-label="No Task Available" scope="row" colpan="8"><strong> No Task Available </strong></td>   
                                    </tr>
                                @else
                                    @foreach ($assignedTasks as $task)
                                        <tr>
                                            <td data-label="Attributes" scope="row">{{ $task->id }}</td>
                                            <td data-label="Base Class">
                                                <p style="margin: 0px;">{{ $task->start_date }}</p>
                                                <p style="font-size: 12px;">{{ $task->start_time }} - {{ $task->end_time }}</p>
                                            </td>
                                            <td data-label="Simulated Case">{{ $task->preffred_program }}</td>
                                            <td>{{$task->to_be_done}}</td>
                                            <td>
                                                <p style="margin: 0px;">{{ $task->assigned_office }}</p>
                                                <p style="font-size: 12px;">{{ $task->email }}</p>
                                            </td>

                                            <td>{{ $task->note }}</td>
                                            <td>
                                                <p style="margin: 0px;">{{ $task->total_hours }}</p>  
                                            </td>

                                                <td>
                                                    <a href="{{ route('sa.manager.saListDone', $task->id) }}" class="btn btn-warning fw-bold" > 
                                                        View # SA
                                                    </a>
                                                </td>
  
                                            
                                        </tr>
                                        
                                    @endforeach
                                @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    @include('nav.offcanvas_menu_sam')
@endsection