@extends('layouts.app')

@section('title', 'Dashboard - Office')

@section('content')
    <!-- Your content here -->
    @include('include.nav_bar')
    <div style="padding: 3em;">
            <section>
                <div class="table-responsive" style="padding: 1em;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="background: #d9d9d9;">TASK NO.</th>
                                <th scope="col" style="background: #d9d9d9;">DATE &amp; tIME</th>
                                <th scope="col" style="background: #d9d9d9;">PROGRAM</th>
                                <th style="background: #d9d9d9;">Task</th>
                                <th style="background: #d9d9d9;">Hours</th>
                                <th style="background: #d9d9d9;">NOTE</th>
                                <th style="background: #d9d9d9;">Task Accepts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Attributes" scope="row">Attribute 1</td>
                                <td data-label="Base Class">Cell 2</td>
                                <td data-label="Simulated Case">Cell 3</td>
                                <td>Cell 4</td>
                                <td>Cell 5</td>
                                <td>Cell 5</td>
                                <td>Cell 6</td>
                                <td style="font-weight: bold;">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-2">Edit</button>
                                </td>
                                <td style="font-weight: bold;">Cancel</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </section>
            <div style="text-align: center;"><button class="btn btn-primary" type="button" style="font-size: 18px;background: rgb(248,190,91);color: rgb(0,0,0);font-weight: bold;border-style: none;" data-bs-toggle="modal" data-bs-target="#addTask">&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-plus-lg" style="width: 25px;height: 25px;">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"></path>
                    </svg> &nbsp;Add Task</button></div>
        </div>
    @include('include.modals')
    @include('include.offcanvas_menu')
@endsection