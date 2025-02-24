<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class,'index'])->name('welcome');

Route::get('/about', [HomeController::class,'about'])->name('about');

//Dashboard
Route::prefix('dashboard')->middleware('auth')->group(function(){
    Route::get('/', [DashboardController::class,'home'])->name('dashboard');
    Route::get('/myquizzes', [QuizController::class,'index'])->name('quizzes');
    Route::get('/statistics', [DashboardController::class,'statistics'])->name('statistics');

    Route::get('/createquiz', [QuizController::class,'create'])->name('createquiz');
    Route::post('/createquiz', [QuizController::class,'store'])->name('storequiz');

    Route::get('/myquizzes/{quiz}', [QuizController::class, 'edit'])->name('editquizzes');
    Route::post('/myquizzes/{quiz}/update', [QuizController::class, 'update'])->name('updatequiz');
    Route::delete('/deletequiz/{quiz}', [QuizController::class, 'destroy'])->name('deletequiz');

});

Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');


Route::get('/showquiz/{slug}', [QuizController::class, 'showquiz'])->middleware('auth')->name('showquiz');
Route::post('/startquiz/{slug}', [QuizController::class, 'startquiz'])->middleware('auth')->name('startquiz');
Route::post('/storeresults/{slug}', [QuizController::class, 'storeresults'])->middleware('auth')->name('storeresults');
Route::get('/showresults/{slug}', [QuizController::class, 'showResults'])->middleware('auth')->name('showresults');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';