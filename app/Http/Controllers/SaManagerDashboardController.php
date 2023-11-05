<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class SaManagerDashboardController extends Controller
{
    //
    public function index(){
        return view('sam.sam_dashboard');
    }
}
