<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class,'index'])->name('welcome');

Route::get('/about', [HomeController::class,'about'])->name('about');

Route::get('/takequiz', [QuizController::class,'takeQuiz'])->middleware('auth')->name('takequiz');


//Dashboard
Route::prefix('dashboard')->middleware('auth')->group(function(){
    Route::get('/', [DashboardController::class,'home'])->name('dashboard');
    Route::get('/myquizzes', [QuizController::class,'index'])->name('quizzes');
    Route::get('/statistics', [DashboardController::class,'statistics'])->name('statistics');

    Route::get('/createquiz', [QuizController::class,'create'])->name('createquiz');
    Route::post('/createquiz', [QuizController::class,'store'])->name('storequiz');

    Route::get('/myquizzes/{quiz}', [QuizController::class, 'edit'])->name('myquizzes');
    Route::post('/myquizzes/{quiz}/update', [QuizController::class, 'update'])->name('updatequizzes');
});




//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';