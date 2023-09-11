<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('frontend.login');
    }

    // Handle the login form submission
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Show the registration form
    public function showRegistrationForm()
    {
        return view('frontend.register');
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        Auth::login($user);

        return redirect('/login')->with('success', 'Registration successful! You can now log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return redirect()->back()->withErrors(['current_password' => 'The provided current password does not match your actual password.']);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return redirect()->route('show.change.password')->with('success', 'Password changed successfully.');
}

public function showChangePasswordForm()
{
    return view('frontend.changepassword');
}

public function showForgotPasswordForm()
{
    return view('frontend.forgot-password');
}




public function sendResetLinkEmail(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if ($user) {
        // User exists, redirect to reset form
        return redirect()->route('password.reset', ['email' => $request->email]);
    } else {
        // User does not exist
        return redirect()->back()->with('success', 'User not found with the provided email.');
    }
}





public function showResetForm(Request $request)
{
    return view('frontend.reset-password', ['email' => $request->email]);
}



public function resetPassword(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if ($user) {
        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
        ]);

        return redirect()->route('login')->with('success', 'Password reset successfully.');
    }

    return redirect()->route('password.request')->with('success', 'Invalid reset request.');
}


}
