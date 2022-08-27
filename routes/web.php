<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Str;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::match(['get', 'post'], '/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/auth/redirect', function () {
    return Socialite::driver('azure')->redirect();
});
Route::get('/auth/callback', function () {
    $azureUser = Socialite::driver('azure')->user();

    $user = User::updateOrCreate([
        'username' => Str::before($azureUser->email,'@')
    ], [
        'username' => Str::before($azureUser->email,'@'),
       # 'email' => $azureUser->email,
       # 'azure_token' => $azureUser->token,
       # 'azure_refresh_token' => $azureUser->refreshToken,
    ]);

    Auth::login($user);

    return redirect('/home');
});
