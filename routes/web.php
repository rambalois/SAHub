<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SaDashboardController;
use App\Http\Controllers\SaManagerDashboardController;
use App\Http\Controllers\OfficeAdminDashboardController;
//use App\Http\Controllers\TaskController;

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

//Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    


//Student Assistant Routes
Route::get('/student_assistant/dashboard', [SaDashboardController::class, 'index'])->name('sa.dashboard');
Route::post('/student_assistant/{task}/accept', [SaDashboardController::class, 'acceptTask'])->name('sa.accept');
Route::get('/student_assistant/profile', [SaDashboardController::class, 'profile'])->name('sa.profile');

//Student Assistant Manager Routes
Route::get('/sa_manager/dashboard/on-going', [SAManagerDashboardController::class, 'onGoing'])->name('sa.manager.dashboard.ongoing');
Route::get('/sa_manager/dashboard/done', [SAManagerDashboardController::class, 'finished'])->name('sa.manager.dashboard.done');

//Office Admin Routes
Route::get('/office_admin/dashboard', [OfficeAdminDashboardController::class, 'index'])->name('office.admin.dashboard');
Route::get('/office_admin/dashboard/active_task', [OfficeAdminDashboardController::class, 'active'])->name('office.admin.active.dashboard');
Route::get('/office_admin/dashboard/inactive_task', [OfficeAdminDashboardController::class, 'inactive'])->name('office.admin.inactive.dashboard');
Route::put('/office_admin/{task}/edit', [OfficeAdminDashboardController::class, 'update'])->name('office.edit');
Route::post('/office_admin/add', [OfficeAdminDashboardController::class, 'store'])->name('office.add');
Route::post('/office_admin/{task}/delete', [OfficeAdminDashboardController::class, 'delete'])->name('office.delete');
Route::post('/office_admin/{task}/cancel', [OfficeAdminDashboardController::class, 'cancel'])->name('office.cancel');

//Route::resource('tasks', TaskController::class);

});