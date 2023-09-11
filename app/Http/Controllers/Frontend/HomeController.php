<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $categories = Category::all();
        return view('frontend.index', ['categories' => $categories]);
    }
    public function aboutus()
    {
        return view('frontend.aboutus');
    }

    public function profile()
    {
        // Your profile page logic goes here
        return view('frontend.profile');
    }

    public function certificate()
{
    $user = Auth::user();
    $category = Category::get();
    $user_id = $user->id;
    $certificates = []; // Initialize the certificates array

    foreach ($category as $category) {
        $path = "Upload/Certificates/{$user_id}/{$category->id}/certificate.pdf";
        if (file_exists(public_path($path))) {
            $certificates[] = $category;
        }
    }

    return view('frontend.user-certificate', compact('certificates', 'user_id'));
}





}


