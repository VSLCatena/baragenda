<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaAdminController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftAdminController;
use App\Http\Controllers\SkillController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//home
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');

//login
Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//auth azure
Route::get('/auth/callback', [LoginController::class,'callback'])->name('callback');



//agenda
Route::match(['get'], '/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::match(['post'], '/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::match(['get'], '/agenda/edit', [AgendaAdminController::class, 'edit'])->name('agenda.edit');
Route::match(['post'], '/agenda/edit', [AgendaAdminController::class, 'edit'])->name('agenda.edit');
Route::match(['post'], '/agenda/delete', [AgendaAdminController::class, 'destroy'])->name('agenda.delete');

Route::post('agenda/getdate', [AgendaController::class, 'getdate'])->name('agenda.getdate');


//user settings
Route::match(['get', 'post'], '/settings', [UserSettingsController::class, 'changeSettings'])->name('user.settings');

//management settings
Route::match(['get','post'], '/management', [ManagementController::class, 'changeSettings'])->name('management.settings');
Route::post( '/management/newRow/', [ManagementController::class, 'newRow'])->name('management.newRow');
Route::match(['get','post'],'/management/delRow/{shifttype}/', [ManagementController::class, 'delRow'])->name('management.delRow');

//shifts
Route::match(['get'], '/shifts', [ShiftController::class, 'index'])->name('shifts');
Route::match(['post'], '/shifts', [ShiftController::class, 'UpdateShifts'])->name('shifts');
Route::match(['post'], '/shiftsEnlist', [ShiftController::class, 'enlist'])->name('shifts.enlist');
Route::get('/shifts/page/{page}', [ShiftController::class, 'index'])->name('shifts.page');
Route::get('/shifts/{date}', [ShiftController::class, 'openDate'])->name('shifts.date');
Route::post('/shifts/{date}', [ShiftController::class, 'removeUser'])->name('shifts.removeUser');

//shiftmanagement

Route::match(['get','post'], '/shiftmanagement', [ShiftAdminController::class, 'admin'])->name('shifts.admin');
Route::get('/shiftmanagement/page/{page}', [ShiftAdminController::class, 'admin'])->name('shifts.admin.page');
Route::post('/shiftmanagement/page/{page}', [ShiftAdminController::class, 'admin'])->name('shifts.admin.page');

//skills
Route::get('/skills', [SkillController::class, 'index'])->name('skills.admin');
