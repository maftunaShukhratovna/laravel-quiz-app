<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function home()
    {
        return view('dashboard.home');
    }

    public function quizzes()
    {
        return view('dashboard.myquizzes');
    }
    public function createQuizzes(){
        return view('dashboard.createquiz');
    }

    public function statistics()
    {
       return view('dashboard.statistics');
    }
}