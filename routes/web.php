<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Facades\Image;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('test-image', function () {
    $img = Image::canvas(400, 200, '#ff0000');
    $img->save(storage_path('app/public/test-red.png'));
    return "Image created!";
});


use App\Http\Controllers\NewsImageController;

Route::get('/image-with-text', [NewsImageController::class, 'generate']);
Route::get('/generateHtmlImage', [NewsImageController::class, 'generateHtmlImage']);
Route::get('/generate-image-with-prompt', [NewsImageController::class, 'generateImageWithPrompt']);
Route::get('/generate-image-with-browsershot', [NewsImageController::class, 'generateImageWithPrompt']);




require __DIR__.'/auth.php';
