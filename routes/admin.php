<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguagesController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MainCategoriesController;
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

define('PAGINATION_COUNT', 10);

Route::group(['middleware' => 'auth:admin'], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    ############################## Begin Languages Route ##############################
    Route::group(['prefix' => 'languages'], function () {
        Route::get('/', [LanguagesController::class, 'index'])->name('admin.languages');
        Route::get('create', [LanguagesController::class, 'create'])->name('admin.languages.create');
        Route::post('store', [LanguagesController::class, 'store'])->name('admin.languages.store');
        Route::get('edit/{id}', [LanguagesController::class, 'edit'])->name('admin.languages.edit');
        Route::post('update/{id}', [LanguagesController::class, 'update'])->name('admin.languages.update');
        Route::get('delete/{id}', [LanguagesController::class, 'delete'])->name('admin.languages.delete');
    });
    ############################## End Languages Route ##############################

    ############################## Begin Main Categories Route ##############################
    Route::group(['prefix' => 'main_categories'], function () {
        Route::get('/', [MainCategoriesController::class, 'index'])->name('admin.maincategories');
        Route::get('create', [MainCategoriesController::class, 'create'])->name('admin.maincategories.create');
        Route::post('store', [MainCategoriesController::class, 'store'])->name('admin.maincategories.store');
        Route::get('edit/{id}', [MainCategoriesController::class, 'edit'])->name('admin.maincategories.edit');
        Route::post('update/{id}', [MainCategoriesController::class, 'update'])->name('admin.maincategories.update');
        Route::get('delete/{id}', [MainCategoriesController::class, 'delete'])->name('admin.maincategories.delete');
        Route::get('status/{id}', [MainCategoriesController::class, 'status'])->name('admin.maincategories.status');
    });
    ############################## End Main Categories Route ##############################
});

Route::group(['middleware' => 'guest:admin'], function() {
    Route::get('login', [LoginController::class, 'showLogin'])->name('adminShowLogin');
    Route::post('adminLogin', [LoginController::class, 'login'])->name('adminLogin');
});


require __DIR__.'/auth.php';
