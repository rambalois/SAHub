@extends('layouts.app')

@section('title', 'Dashboard - Student Assistant')

@section('content')
    <!-- Your content here -->
    @include('include.nav_bar')
    <div>
        <div style="padding: 3em;">
            <section>
                <h1>URGENT</h1>
                <div class="table-responsive" style="padding: 1em;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="background: #d9d9d9;">TASK NO.</th>
                                <th scope="col" style="background: #d9d9d9;">DATE &amp; tIME</th>
                                <th scope="col" style="background: #d9d9d9;">PROGRAM</th>
                                <th style="background: #d9d9d9;">OFFICE</th>
                                <th style="background: #d9d9d9;">NOTE</th>
                                <th style="background: #d9d9d9;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Attributes" scope="row">Attribute 1</td>
                                <td data-label="Base Class">Cell 2</td>
                                <td data-label="Simulated Case">Cell 3</td>
                                <td>Cell 4</td>
                                <td>Cell 5</td>
                                <td>Cell 6</td>
                            </tr>
                            <tr>
                                <td data-label="Attributes" scope="row">Attribute 2</td>
                                <td data-label="Base Class">Cell 4</td>
                                <td data-label="Simulated Case">Cell 5</td>
                                <td>Cell 4</td>
                                <td>Cell 5</td>
                                <td>Cell 6</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div style="padding: 3em;border-top-style: groove;">
            <section>
                <h1>Assigned Tasks</h1>
                <div class="table-responsive" style="padding: 1em;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="background: #d9d9d9;">TASK NO.</th>
                                <th scope="col" style="background: #d9d9d9;">DATE &amp; tIME</th>
                                <th scope="col" style="background: #d9d9d9;">PROGRAM</th>
                                <th style="background: #d9d9d9;">Task</th>
                                <th style="background: #d9d9d9;">Office</th>
                                <th style="background: #d9d9d9;">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Attributes" scope="row">Attribute 1</td>
                                <td data-label="Base Class">Cell 2</td>
                                <td data-label="Simulated Case">Cell 3</td>
                                <td>Cell 4</td>
                                <td>Cell 5</td>
                                <td>Cell 6</td>
                            </tr>
                            <tr>
                                <td data-label="Attributes" scope="row">Attribute 2</td>
                                <td data-label="Base Class">Cell 4</td>
                                <td data-label="Simulated Case">Cell 5</td>
                                <td>Cell 4</td>
                                <td>Cell 5</td>
                                <td>Cell 6</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>


    @include('include.offcanvas_menu')
@endsection