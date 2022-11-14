<?php

use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

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
// redirecting '/' and '/register'
Route::permanentRedirect('/', '/login');
Route::permanentRedirect('/register', '/login');
Route::permanentRedirect('/password/reset', '/login');

// auth route for laravel - ui runtime
Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

// routing for authenticated users
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // settings route
    Route::get('settings/newpassword', [SettingsController::class, 'newPassword']);
    Route::get('settings/manageusers', [SettingsController::class, 'manageUsers']);
    Route::get('settings/username/{username}', [SettingsController::class, 'username']);
    Route::post('settings/generatenewpassword', [SettingsController::class, 'generateNewPassword']);
    Route::resource('settings', SettingsController::class);
});