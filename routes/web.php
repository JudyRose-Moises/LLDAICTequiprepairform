<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);

Route::get('/openticket', [TicketController::class, 'openTicketForm'])->name('tickets.open');
Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');

Route::get('/hello', [TicketController::class, 'index'])->name('tickets.index');

Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
Route::put('/tickets/{ticket}/edit', [TicketController::class, 'update'])->name('tickets.update');



Route::post('/submit-rating/{id}', [TicketController::class, 'submitRating'])->name('submitRating');
Route::get('/rate-ticket/{id}', [TicketController::class, 'rateTicket'])->name('rateTicket');

Route::get('/finalreport/{id}', [TicketController::class, 'finalReport'])->name('finalreport');

Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('ticket.deleteticket');


Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/tickets/{id}/accept', [TicketController::class, 'accept'])->name('tickets.accept');
Route::post('/tickets/{id}/decline', [TicketController::class, 'decline'])->name('tickets.decline');


Route::middleware(['auth.session'])->group(function () {
    Route::get('/inspector/dashboard', [DashboardController::class, 'indexinspector'])->name('inspectordashboard');
    Route::get('/inspector/tickets', [TicketController::class, 'indexinspector'])->name('inspectortickets.index');
    
    Route::get('/admin/dashboard', [DashboardController::class, 'indexadmin'])->name('admindashboard');
    Route::get('/admin/tickets', [TicketController::class, 'indexadmin'])->name('tickets.index');
    
    Route::get('/admin/assign', [TicketController::class, 'showAssignPage'])->name('admin.assign');
    Route::put('/admin/assign/{id}', [TicketController::class, 'assignTicket']);
    Route::put('/admin/unassign/{ticket}', [TicketController::class, 'unassign']);
});

use App\Models\Ticket;

Route::get('/excelview', function () {
    $tickets = Ticket::with(['endUser', 'inspector'])->get();
    return view('tickets_excel', compact('tickets'));
})->name('tickets.excelview');


use App\Http\Controllers\UserController;

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
Route::put('/admin/users/{emp_number}/update-role', [UserController::class, 'updateRole'])->name('admin.updateRole');

Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews');
Route::get('/inspector/reviews', [ReviewController::class, 'inspectorReviews'])->name('inspector.reviews');

Route::get('/inspector/tickets/claim', [TicketController::class, 'inspectorClaimPage'])->name('inspector.claim');
Route::put('/inspector/assign/{id}', [TicketController::class, 'assignTicket']);

Route::get('/procurement',[TicketController::class, 'forProcurement']);

Route::get('/tickets/export-csv', [TicketController::class, 'exportCsv'])->name('tickets.export');
