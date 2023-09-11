<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use App\Models\VideoView;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
class CategoryUserController extends Controller
{
    public function index($categoryId)
    {
        // Retrieve the category details from the database using the given category ID
        $category = Category::find($categoryId);

        // If the category with the given ID is not found, redirect back with an error message
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }

        // Pass the category details to the category_user.index view
        return view('category_user.index', compact('category'));
    }

    public function startAssessment($categoryId)
{
    $category = Category::find($categoryId);
    $subcategory = SubCategory::all();

    $totalVideos = Video::where('category_id', $categoryId)->count();


    $user = Auth::user();
    $videosWatched = VideoView::where('user_id', $user->id)
                             ->where('category_id', $categoryId)
                             ->count();

    $percentageWatched = ($videosWatched / $totalVideos) * 100;


    return view('category_user.start', compact('category', 'subcategory','totalVideos', 'percentageWatched', 'videosWatched'));

}

}
