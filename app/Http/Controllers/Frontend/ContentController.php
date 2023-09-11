<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Content;

class ContentController extends Controller
{
    public function showByCategory(Category $category)
    {
        // Get contents based on the selected category
        $contents = Content::where('category_id', $category->id)->get();

        return view('frontend.view-content-user', compact('category', 'contents'));
    }
}
