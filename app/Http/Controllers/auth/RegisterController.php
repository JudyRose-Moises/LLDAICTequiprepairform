<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'emp_number' => 'required|string|max:255|unique:users',
        'division' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'required' => 'The :attribute field is required.',
        'unique' => 'The :attribute has already been taken.',
        'confirmed' => 'The :attribute confirmation does not match.',
        'min' => 'The :attribute must be at least :min characters.',
    ]);

    User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'emp_number' => $request->emp_number,
        'division' => $request->division,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Registration successful. Welcome to our website!');
}
}
