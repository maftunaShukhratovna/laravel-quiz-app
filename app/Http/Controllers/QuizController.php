<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Result;

use App\Models\Question;
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
             'quizzes' => Quiz::where('user_id', auth()->id())
                 ->withCount('questions')
                 ->orderBy('created_at', 'desc')
                 ->paginate(9)
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
            'questions'=>'required|array',
        ]);


        $quiz = Quiz::create([
            'user_id' => auth()->id(),
            'title' => $validator['title'],
            'description' => $validator['description'],
            'time_limit' => $validator['timeLimit'],
            'slug' => Str::slug(strtotime('now') . '/' . $validator['title']),
        ]);

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
        return view('dashboard.editquiz', [
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
            'questions' => 'required|array',
        ]);

        $quiz->update([
            'title' => $validator['title'],
            'description' => $validator['description'],
            'time_limit' => $validator['timeLimit'],
            'slug' => Str::slug(strtotime('now') . '/' . $validator['title']),
        ]);

        $quiz->questions()->delete();
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
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return to_route('quizzes');

    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function startquiz(Quiz $quiz)
    {

        // dd($quiz);
        return view('quiz.startquiz', [
            'quiz' => $quiz,
        ]);
    }
    
    public function showquiz(Quiz $quiz)
    {

        return view('quiz.showquiz', [
            'quiz' => $quiz,
        ]);
    }

    public function storeresults(Request $request)
    {
        $validator = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'answers' => 'required|array',
        ]);

        $quiz = Quiz::findOrFail($validator['quiz_id']);
        $score = 0;
        $totalQuestions = count($quiz->questions);

        foreach ($validator['answers'] as $questionId => $selectedOptionId) {
            $question = Question::find($questionId);

            if ($question && $question->correct_option_id == $selectedOptionId) {
                $score++;
            }

            Answer::create([
                'user_id' => auth()->id(),
                'quiz_id' => $quiz->id,
                'question_id' => $questionId,
                'selected_option_id' => $selectedOptionId,
            ]);
        }

        Result::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_questions' => $totalQuestions,
        ]);

        return to_route('showresults');
        
    }

    public function showresults()
    {
        $result = Result::where('user_id', auth()->id())
            ->latest()
            ->first();

        return view('quiz.showresults', [
            'result' => $result,
        ]);
    }



}
