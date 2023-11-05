<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfficeAdminDashboardController extends Controller
{
    //
    public function index(){
        return view('office.office_dashboard');
    }
}
