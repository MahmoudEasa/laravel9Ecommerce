<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'auth:admin'], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('adminDashboard');
});

Route::group(['middleware' => 'guest:admin'], function() {
    Route::get('login', [LoginController::class, 'showLogin'])->name('adminShowLogin');
    Route::post('adminLogin', [LoginController::class, 'login'])->name('adminLogin');
});


require __DIR__.'/auth.php';