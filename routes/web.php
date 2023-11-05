<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SaDashboardController;
use App\Http\Controllers\SaManagerDashboardController;
use App\Http\Controllers\OfficeAdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

//Login Route
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');

Route::get('/student_assistant/dashboard', [SaDashboardController::class, 'index'])->name('sa.dashboard');
Route::get('/sa_manager/dashboard', [SAManagerDashboardController::class, 'index'])->name('sa.manager.dashboard');
Route::get('/office_admin/dashboard', [OfficeAdminDashboardController::class, 'index'])->name('office.admin.dashboard');

//Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


//Student Assistant Routes

//Student Assistant Manager Routes

//Office Admin Routes

