<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::all(); // Get all users
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, $emp_number)
{
    $request->validate([
        'role' => 'required|in:employee,inspector,admin',
    ]);

    $user = User::where('emp_number', $emp_number)->firstOrFail(); // Use emp_number instead of id
    $user->role = $request->role;
    $user->save();

    return response()->json(['message' => 'User role updated successfully!']);
}



    
}    
