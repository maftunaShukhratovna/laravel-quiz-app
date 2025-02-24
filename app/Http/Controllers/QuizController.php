<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Result;
use App\Models\Option;

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

    public function startquiz(string $slug)
    {
        $quiz = Quiz::where('slug', $slug)->with('questions.options')->first();
        $result = Result::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'started_at' => now(),
            'finished_at' => date('Y-m-d H:i:s', strtotime('+' . $quiz->time_limit . ' minutes')),
        ]);
        return view('quiz.startquiz', [
            'quiz' => $quiz->load('questions.options'),
        ]);

    }
    
    public function showquiz(string $slug)
    {
        $quiz = Quiz::where('slug', $slug)->first();
        $result = Result::query()
            ->where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->first();


        if (!$result) {
            return view('quiz.showquiz', [
                'quiz' => $quiz,
            ]);
        }
        return 'test !';
        
    }

    public function storeResults( Request $request, string $slug)
    {
        $validator = $request->validate([
            'answer' => 'required|integer|exists:options,id',
        ]);


        $user_id = auth()->id();
        $quiz = Quiz::where('slug', $slug)->first();

        $result = Result::where('quiz_id', $quiz->id)
            ->where('user_id', $user_id)
            ->first();

        Answer::create([
            'result_id' => $result->id,
            'option_id' => $validator['answer'],
        ]);

        if ($result->finished_at <= now()) {
            return 'Seni vaqting tugagan';
        }

        return to_route('showresults', $slug);

    }

    public function showResults(string $slug)
{
    $quiz = Quiz::where('slug', $slug)->first();

    if (!$quiz) {
        return redirect()->back()->with('error', 'Quiz topilmadi!');
    }

    $result = Result::query()
        ->where('quiz_id', $quiz->id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$result) {
        return redirect()->back()->with('error', 'Siz bu testni hali yechmagansiz!');
    }

  
    $totalQuestions = Question::where('quiz_id', $quiz->id)->count();

   
    $correctAnswers = Answer::query()
        ->where('result_id', $result->id)
        ->whereHas('option', function ($query) {
            $query->where('is_correct', true);
        })
        ->count();

        $timeTaken = strtotime($result->finished_at) - strtotime($result->started_at);

       
        $hours = floor($timeTaken / 3600);
        $minutes = floor(($timeTaken % 3600) / 60);
        $seconds = $timeTaken % 60;
        
       
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        

    return view('quiz.showresults', [
        'quiz' => $quiz,
        'totalQuestions' => $totalQuestions,
        'correctAnswers' => $correctAnswers,
        'time' => $formattedTime,
    ]);
}




}