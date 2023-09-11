<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserSignupReportController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin-pages.user-signup-report', compact('users'));
    }

    public function edit(User $user)
{
    // Your edit logic
    // Return the view with the user details
    return view('admin-pages.users-edit', compact('user'));
}
public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            // Add other validation rules for other fields
        ]);

        // Update user attributes
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            // Update other fields as needed
        ]);

        return redirect()->route('user.details')->with('success', 'User details updated successfully.');
    }

public function destroy(User $user)
{
    // Your destroy logic
    // Delete the user and redirect
    $user->delete();
    return redirect()->route('user.details')->with('success', 'User deleted successfully.');
}

}
