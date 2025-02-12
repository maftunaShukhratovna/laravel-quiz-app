<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('dashboard.myquizzes', [
            'quiz' => Quiz::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.createquiz');
    
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=$request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'timeLimit'=>'required|integer',
            'questions'=>'required|array'
        ]);

        $quiz = Quiz::create([
            'user_id' => auth()->id(),
            'title' => $validator['title'],
            'description' => $validator['description'],
            'time_limit' => $validator['timeLimit'],
            'slug' => Str::slug(strtotime('now') . '/' . $validator['title']),
        ]);

        dd($quiz);

        foreach ($validator['questions'] as $question) {
           $questionItem = $quiz->questions()->create([
                'name' => $question['quiz'],
            ]);
            foreach ($question['options'] as $optionKey => $option) {
                $questionItem->options()->create([
                    'name' => $option,
                    'is_correct' => $question['correct'] == $optionKey ? 1 : 0,
                ]);
            }
        }
        return to_route('quizzes');

    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        return view('dashboard.edit-quiz', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validator = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'timeLimit' => 'required|integer',
            'question' => 'required|array',
        ]);

        $quiz->update([
            'title' => $validator['title'],
            'description' => $validator['description'],
            'time_limit' => $validator['timeLimit'],
            'slug' => Str::slug(strtotime('now') . '/' . $validator['title']),
        ]);

        $quiz->questions()->delete();

        foreach ($validator['question'] as $question) {
            $questionItem = $quiz->questions()->create([
                'name' => $question['quiz'],
            ]);
            foreach ($question['option'] as $optionKey => $option) {
                $questionItem->options()->create([
                    'name' => $option,
                    'is_correct' => $question['is_correct'] == $optionKey ? 1 : 0,
                ]);
            }

        }
        return to_route('quizzes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
    }

    public function takequiz(){
        return view('quiz.takequiz');
    }

}
