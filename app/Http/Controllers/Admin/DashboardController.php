<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Content;
use App\Models\User;
use App\Models\Video;
use App\Models\QuizQuestion;
use App\Models\QuizResult;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $categoryCount = Category::count();
        $contentCount = Content::count();
        $videoCount = Video::count();
        $quizQuestionCount = QuizQuestion::count();
        $certificateCount = QuizResult::count();

        return view('admin-pages.dashboard', compact('userCount', 'categoryCount', 'contentCount', 'videoCount', 'quizQuestionCount', 'certificateCount'));
    }
}

