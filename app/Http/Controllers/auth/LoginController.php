<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('emp_number', 'password');

        // Check if the student number exists in the database
        $user = User::where('emp_number', $credentials['emp_number'])->first();

        if (!$user) {
            // Student number does not exist
            return back()->withErrors(['emp_number' => 'Account does not exist.'])->withInput($request->only('emp_number'));
        }

        if (Auth::attempt($credentials)) {
            // Authentication successful
            return $this->authenticated($request, $user);
        }

        // Authentication failed
        return back()->withErrors(['emp_number' => 'These credentials do not match our records.'])->withInput($request->only('emp_number'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('register');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role == 'admin') { 
            return redirect()->route('admindashboard');
        } else if ($user->role == 'inspector') {
            return redirect()->route('inspectordashboard');
        }else {
            return redirect()->route('dashboard'); 
            }
    }
}