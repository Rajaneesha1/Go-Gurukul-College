<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class QuizUserController extends Controller
{
    public function showQuizByCategory(Category $category)
    {
        $quizQuestions = QuizQuestion::where('category_id', $category->id)->get();
        return view('frontend.quiz-display', compact('category', 'quizQuestions'));
    }

    public function submitQuiz(Request $request)
    {
        $answers = $request->input('answer');
        $quizQuestions = QuizQuestion::whereIn('id', array_keys($answers))->get();

        // Initialize variables for score and answers
        $score = 0;
        $correctAnswers = 0;
        $wrongAnswers = 0;

        foreach ($quizQuestions as $quizQuestion) {
            $questionId = $quizQuestion->id;
            $selectedOption = (int) $answers[$questionId];
            $correctAnswer = $quizQuestion->correct_answer;

            // Check if the selected option is correct
            if ($selectedOption === $correctAnswer) {
                $score++;
                $correctAnswers++;
            } else {
                $wrongAnswers++;
            }
        }

        $totalQuestions = count($quizQuestions);
        $percentage = ($score / $totalQuestions) * 100;

        $user = auth()->user();

        $q=QuizResult::where('user_id',$user->id)->first();

        /*$quizResult = QuizResult::updateOrCreate(
            ['user_id' => $user->id, 'category_id' => $quizQuestions[0]->category_id],
            ['percentage' => $percentage]
        );*/
        $count=QuizResult::where('user_id',$user->id)->where('category_id',$quizQuestions[0]->category_id)->count();
        if($count==0){
            if ($percentage >= 70) {
                QuizResult::updateOrCreate(
                    ['user_id' => $user->id, 'category_id' => $quizQuestions[0]->category_id],
                    ['percentage' => $percentage,'correctAnswers' => $correctAnswers,'wrongAnswers' => $wrongAnswers]);
                $certificateIssued = true;
            } else {
                QuizResult::updateOrCreate(
                    ['user_id' => $user->id, 'category_id' => $quizQuestions[0]->category_id],
                    ['percentage' => $percentage,'correctAnswers' => $correctAnswers,'wrongAnswers' => $wrongAnswers]);
                $certificateIssued = false;
            }
        }
        // Check if the user has already earned a certificate for this category
        else {
            QuizResult::where('user_id',$user->id)->update(['percentage' => $percentage,'correctAnswers' => $correctAnswers,'wrongAnswers' => $wrongAnswers,'user_flag' => '1']);
            $certificateIssued = true;
            // Update the quiz result and issue a certificate if the percentage is >= 70

        }
$quizResult=QuizResult::where('user_id',$user->id)->where('category_id',$quizQuestions[0]->category_id)->first();
        // Redirect to the result page with the quiz result and certificate status
        return redirect()->route('quiz.result', [
            'category' => $quizQuestions[0]->category_id,
            'quizResult' => $quizResult,
            'correctAnswers' => $correctAnswers,
            'wrongAnswers' => $wrongAnswers,
            'certificateIssued' => $quizResult->certificateIssued,
            'totalQuestions' => $totalQuestions,
        ]);

    }

    public function showQuizResult(Category $category)
    {
        $user = auth()->user();
        $quizResult=QuizResult::where('user_id',$user->id)->where('category_id',$category->id)->first();
        $certificateIssued = $quizResult->certificate_issued;
        $totalQuestions=$quizResult->correctAnswers+$quizResult->wrongAnswers;
        // Calculate the percentage, but make sure totalQuestions is greater than zero
        $percentage = $totalQuestions > 0 ? ($quizResult->correctAnswers / $totalQuestions) * 100 : 0;

        return view('frontend.quiz-result', compact('category', 'quizResult','totalQuestions'));
    }





public function showCertificate(QuizResult $quizResult)
{
    $user = auth()->user();
    $category = $quizResult->category;
    $data = [
        'quizResult' => $quizResult,
        'user' => $user,
    'category' => $category,
        // Add other necessary data here
    ];

    QuizResult::where('user_id',$user->id)->where('category_id',$quizResult->category_id)->update(['certificate_issued' => '1']);

    $pdf = new Dompdf();
    $html = View::make('frontend.showCertificate', $data)->render();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'landscape');
    $pdf->render();

    $pdfContent = $pdf->output();

    $path="Upload/Certificates/".$user->id."/".$quizResult->category_id."/";
    $fname="certificate.pdf";

    //$pdfContent->move(public_path($path), $fname);

    $publicPath = public_path($path);

    // Create the directory if it doesn't exist
    if (!file_exists($publicPath)) {
        mkdir($publicPath, 0755, true);
    }

    $fullPath = $publicPath . $fname;

    // Save the PDF directly to the public folder
    file_put_contents($fullPath, $pdfContent);

    return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="certificate.pdf"');
}

}
