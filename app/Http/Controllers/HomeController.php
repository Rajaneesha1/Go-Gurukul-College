<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function profile()
    {
        // Your profile page logic goes here
        return view('profile');
    }


    public function logout()
    {
        // Your logout logic goes here
        // For example, you can use Laravel's auth() helper to logout the user
        auth()->logout();
        return response()
        ->noCache()
        ->route('home');
        
    }

    public function javaPage()
    {
        // Your Java page logic goes here
        return view('java');
    }

    public function pythonPage()
    {
        // Your Python page logic goes here
        return view('python');
    }

    public function androidPage()
    {
        // Your Android page logic goes here
        return view('android');
    }
}
