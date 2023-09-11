<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\QuizQuestion;
use App\Models\QuizResult;

class QuizController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin-pages.create-quiz', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required', // Validate that category_id is not empty
        'question' => 'required|string|max:255',
        'option1' => 'required|string|max:255',
        'option2' => 'required|string|max:255',
        'option3' => 'required|string|max:255',
        'option4' => 'required|string|max:255',
        'correct_answer' => 'required|integer|between:1,4',
    ]);

    // Create the QuizQuestion model with the provided data
    QuizQuestion::create([
        'category_id' => $request->category_id, // Set the category_id from the form data
        'question' => $request->question,
        'option1' => $request->option1,
        'option2' => $request->option2,
        'option3' => $request->option3,
        'option4' => $request->option4,
        'correct_answer' => $request->correct_answer,
    ]);

    return redirect()->back()->with('success', 'Quiz question added successfully.');
}
public function show()
{
    $quizQuestions = QuizQuestion::with('category')->get();
    return view('admin-pages.quiz-show', compact('quizQuestions'));
}

public function certificate(Request $request)
{
    $categories = Category::all();

    $perPage = 10;
    $certificates = QuizResult::with('user', 'category')
    ->when($request->category, function ($query) use ($request) {
        return $query->whereHas('category', function ($categoryQuery) use ($request) {
            $categoryQuery->where('name', $request->category);
        });
    })
    ->when($request->date, function ($query) use ($request) {
        return $query->whereDate('created_at', $request->date);
    })
    ->orderBy('created_at', 'desc')
    ->paginate($perPage);


    return view('admin-pages.certificates', compact('certificates', 'categories'));
}

public function edit(QuizQuestion $quiz)
    {
        $categories = Category::all();
        return view('admin-pages.quiz-edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, QuizQuestion $quizQuestion)
{
    $request->validate([
        'question' => 'required|string',
        'option1' => 'required|string',
        'option2' => 'required|string',
        'option3' => 'required|string',
        'option4' => 'required|string',
        'correct_answer' => 'required', // Validate based on your logic
        'category_id' => 'required|exists:categories,id',
    ]);

    // Convert the correct_answer option to its integer representation
    $correctAnswerOption = $request->input('correct_answer');
    $correctAnswerValue = null;
    if ($correctAnswerOption === 'option1') {
        $correctAnswerValue = 1;
    } elseif ($correctAnswerOption === 'option2') {
        $correctAnswerValue = 2;
    } elseif ($correctAnswerOption === 'option3') {
        $correctAnswerValue = 3;
    } elseif ($correctAnswerOption === 'option4') {
        $correctAnswerValue = 4;
    }

    if (!is_null($correctAnswerValue)) {
        $quizQuestion->correct_answer = $correctAnswerValue;
    } else {
        // Handle invalid option value
    }

    // Update other attributes
    $quizQuestion->question = $request->input('question');
    $quizQuestion->option1 = $request->input('option1');
    $quizQuestion->option2 = $request->input('option2');
    $quizQuestion->option3 = $request->input('option3');
    $quizQuestion->option4 = $request->input('option4');
    $quizQuestion->category_id = $request->input('category_id');
    $quizQuestion->save();

    return redirect()->route('admin.quiz.questions')->with('success', 'Quiz question updated successfully.');
}











    public function destroy(QuizQuestion $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quiz.questions')->with('success', 'Quiz question deleted successfully.');
    }

}
