<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
date_default_timezone_set('Asia/Manila'); 
class DashboardController extends Controller
{
    public function indexinspector()
    {
      $tickets = [
         'total' => Ticket::where('repairedBy', Auth::user()->emp_number)->count(),
         'assigned' => Ticket::where('repairedBy', Auth::user()->emp_number)->where('status', 'assigned')->count(),
         'closed' => Ticket::where('repairedBy', Auth::user()->emp_number)->whereIn('status', ['working', 'nonworking'])->count(),
     ];

   // 🔹 Top 5 most frequently repaired equipment & common issues
   $topEquipments = Ticket::selectRaw("equipment_type, COUNT(*) as total, GROUP_CONCAT(problem SEPARATOR ', ') as issues")
   ->whereNotNull('equipment_type')
   ->groupBy('equipment_type')
   ->orderByDesc('total')
   ->limit(5)
   ->get();

// 🔹 Most problematic Property ID (based on highest repairs)
$mostProblematicProperty = Ticket::selectRaw("propertyID, equipment_type, COUNT(*) as total")
   ->whereNotNull('propertyID')
   ->groupBy('propertyID', 'equipment_type')
   ->orderByDesc('total')
   ->first();

// 🔹 Problematic Properties (Top 5)
$problematicProperties = Ticket::selectRaw("propertyID, equipment_type, COUNT(*) as total")
   ->whereNotNull('propertyID') 
   ->groupBy('propertyID', 'equipment_type')
   ->orderByDesc('total')
   ->limit(5)
   ->get();

// 🔹 Number of tickets sent this month (for bar graph)
$ticketsThisMonth = Ticket::whereMonth('created_at', Carbon::now()->month)->count();

// 🔹 Tickets per day for the current month (Bar Graph Data)
$ticketsPerDay = Ticket::selectRaw("DAY(created_at) as day, COUNT(*) as count")
   ->whereMonth('created_at', Carbon::now()->month)
   ->groupBy('day')
   ->orderBy('day')
   ->get();

return view('inspector.inspectorDashboard', compact(
   'topEquipments', 
   'mostProblematicProperty', 
   'problematicProperties', 
   'ticketsThisMonth',
   'ticketsPerDay', 'tickets'));
}

public function index()
{
   $firstName = Auth::user()->first_name;
   $lastName = Auth::user()->last_name;

   $tickets = [
      'total' => Ticket::where('first_name', $firstName)
           ->where('last_name', $lastName)
           ->count(),
      'open' => Ticket::where('first_name', $firstName)
    ->where('last_name', $lastName)
    ->where(function ($query) {
        $query->whereIn('status', ['Open', 'assigned', 'forprocurement'])
              ->orWhere('for_acceptance', 'Declined');
    })
    ->count(),
      'closed' => Ticket::where('first_name', $firstName)
            ->where('last_name', $lastName)
            ->whereIn('status', ['working', 'nonworking'])
            ->whereNull('for_acceptance')
            ->count(),
     
   ];

    // Get tickets that need acceptance
    $pendingTickets = Ticket::where('for_acceptance', '1')
        ->where('first_name', $firstName)
        ->where('last_name', $lastName)
        ->get();

   $allTickets = Ticket::where('first_name', $firstName)
   ->where('last_name', $lastName)
   ->get();
        
   $inspectors = User::where('role', 'inspector')->get();

    return view('user.dashboard', compact('tickets', 'pendingTickets', 'allTickets','inspectors',));
}

    public function indexadmin()
    {
        $tickets = [
            'total' => Ticket::count(),
            'assigned' => Ticket::where('status', 'assigned')->count(),
            'unassigned' => Ticket::where('status', 'open')->count(),
            'closed' => Ticket::whereIn('status', ['working', 'nonworking'])->count(),
        ];

   // 🔹 Top 5 most frequently repaired equipment & common issues
   $topEquipments = Ticket::selectRaw("equipment_type, COUNT(*) as total, GROUP_CONCAT(problem SEPARATOR ', ') as issues")
   ->whereNotNull('equipment_type')
   ->groupBy('equipment_type')
   ->orderByDesc('total')
   ->limit(5)
   ->get();

// 🔹 Most problematic Property ID (based on highest repairs)
$mostProblematicProperty = Ticket::selectRaw("propertyID, equipment_type, COUNT(*) as total")
   ->whereNotNull('propertyID')
   ->groupBy('propertyID', 'equipment_type')
   ->orderByDesc('total')
   ->first();

// 🔹 Problematic Properties (Top 5)
$problematicProperties = Ticket::selectRaw("propertyID, equipment_type, COUNT(*) as total")
   ->whereNotNull('propertyID') 
   ->groupBy('propertyID', 'equipment_type')
   ->orderByDesc('total')
   ->limit(5)
   ->get();

// 🔹 Number of tickets sent this month (for bar graph)
$ticketsThisMonth = Ticket::whereMonth('created_at', Carbon::now()->month)->count();

// 🔹 Tickets per day for the current month (Bar Graph Data)
$ticketsPerDay = Ticket::selectRaw("DAY(created_at) as day, COUNT(*) as count")
   ->whereMonth('created_at', Carbon::now()->month)
   ->groupBy('day')
   ->orderBy('day')
   ->get();

return view('admin.adminDashboard', compact(
   'topEquipments', 
   'mostProblematicProperty', 
   'problematicProperties', 
   'ticketsThisMonth',
   'ticketsPerDay', 'tickets'));
}
}
