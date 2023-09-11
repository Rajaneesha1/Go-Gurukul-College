<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Content;
use App\Models\User;
use App\Models\Video;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Hash;
use App\Models\QuizResult;

class AdminController extends Controller
{
    public function authenticate(Request $request) {

        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))) {

            return redirect()->route('admin.dashboard');

        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {

        $userCount = User::count();
        $categoryCount = Category::count();
        $contentCount = Content::count();
        $videoCount = Video::count();
        $quizQuestionCount = QuizQuestion::count();
        $certificateCount = QuizResult::count();

        return view('admin-pages.dashboard', compact('userCount', 'categoryCount', 'contentCount', 'videoCount', 'quizQuestionCount' , 'certificateCount'));
    }


    public function showProfile()
    {
        return view('admin-pages.profile');
    }

    public function showChangePasswordForm()
    {
        return view('admin-pages.changepassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The provided current password does not match your actual password.']);
        }

        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('admin.show.change.password')->with('success', 'Password changed successfully.');
    }


}

