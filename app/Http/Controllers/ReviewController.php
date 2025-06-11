<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        // Get all inspectors
        $inspectors = User::where('role', 'inspector')->get();

        // Get all reviews (with existing rating and review)
        $reviews = Ticket::whereNotNull('rating')->get();


        return view('admin.adminReviews', compact('inspectors', 'reviews'));
    }

    public function inspectorReviews()
    {
        $user = Auth::user();
    
        if ($user->role !== 'inspector') {
            abort(403, 'Unauthorized.');
        }
    
        $empnumber = $user->emp_number;
    
        $reviews = Ticket::where('repairedBy', $empnumber)
                        ->whereNotNull('rating')
                        ->get();
    
        return view('inspector.inspectorReviews', compact('user', 'reviews'));
    }
    

}
