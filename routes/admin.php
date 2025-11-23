<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminHomeController;

Route::get('/login', [AdminHomeController::class, 'index'])->name('admin-login');
Route::post('/login', [AdminHomeController::class, 'login_post']);
Route::get('logout', [AdminHomeController::class, 'logout'])->name('logout');
// Route::post('dashboard-login', [AdminHomeController::class, 'login_post'])->name('admin-post');

Route::group(['middleware' => 'admin'], function () {

    Route::get('/dashboard', [AdminHomeController::class, 'dashboard'])->name('dashboard');



});
