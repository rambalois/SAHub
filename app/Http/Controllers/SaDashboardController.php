<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaDashboardController extends Controller
{
    //
    public function index(){
        return view('sa.sa_dashboard');
    }
}
