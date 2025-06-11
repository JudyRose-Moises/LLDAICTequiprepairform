<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

date_default_timezone_set('Asia/Manila');

class TicketController extends Controller
{
    public function openTicketForm()
    {
        return view('user/opentix');
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_type' => 'nullable|array',
            'equipment_type.*' => 'string',
            'problem' => 'nullable|array',
            'problem.*' => 'string',
            'other_equipment' => 'nullable|string',
            'other_problem' => 'nullable|string',
            'accessories' => 'nullable|array',
            'accessories.*' => 'string',
            'other_accessory' => 'nullable|string',
        ]);

        // Auto-generate custom ID (YYYYMMDD###)
        $today = now()->format('Ymd'); // 20250520
        $countToday = Ticket::whereDate('created_at', now()->toDateString())->count() + 1;
        $increment = str_pad($countToday, 3, '0', STR_PAD_LEFT); // 001, 002, ...
        $customId = $today . $increment; // e.g. 20250520001

        $equipment_type = $request->has('equipment_type') ? implode(',', $request->equipment_type) : null;
        if ($request->filled('other_equipment')) {
            $equipment_type .= ', '. $request->other_equipment;
        }

        $problem = $request->has('problem') ? implode(',', $request->problem) : null;
        if ( $request->filled('other_problem')) {
            $problem .= ', '. $request->other_problem;
        }

        $accessories = $request->has('accessories') ? implode(',', $request->accessories) : null;
        if ($request->filled('other_accessory')) {
            $accessories .= ', '. $request->other_accessory;
        }

        $brand = $request->has('brand') ? ($request->brand) : null;
        if ($request->filled('other_brand')) {
            $brand .= $request->other_brand;
        }

        $serialNum = $request->has('serialNum') ? ($request->serialNum) : null;
        if ($request->filled('other_serialNum')) {
            $serialNum .= $request->other_serialNum;
        }

        $propertyID = $request->has('propertyID') ? ($request->propertyID) : null;
        if ($request->filled('other_propertyID')) {
            $propertyID .= $request->other_propertyID;
        }

        $accountableUser = $request->has('accountableUser') ? ($request->accountableUser) : null;
        $users = $request->has('users') ? ($request->users) : null;

        $ticket = new Ticket();
        $ticket->custom_id = $customId; // 🟢 Store custom ticket ID
        $ticket->subject = 'New Ticket';
        $ticket->status = 'open';
        $ticket->equipment_type = $equipment_type;
        $ticket->problem = $problem;
        $ticket->accessories = $accessories;
        $ticket->brand = $brand;
        $ticket->serialNum = $serialNum;
        $ticket->propertyID = $propertyID;
        $ticket->first_name = Auth::user()->first_name;
        $ticket->last_name = Auth::user()->last_name;
        $ticket->division = Auth::user()->division;
        $ticket->emp_number = Auth::user()->emp_number;
        $ticket->accountableUser = $accountableUser;
        $ticket->users = $users;
        $ticket->save();

        return redirect()->route('dashboard')->with('success', 'Ticket submitted! ID: ' . $customId);
    }


    public function index(Request $request)
    {
        $query = Ticket::query(); 
    
        
        if ($request->has('status') && $request->status !== '') {
            if ($request->status == 'all') {
                $query->whereIn('status', ['working', 'nonworking', 'open', 'closed', 'assigned']);
            } elseif ($request->status == 'closed') {
                $query->whereIn('status', ['working', 'nonworking']); // clse status
            } else {
                $query->where('status', $request->status);
            }
        }  
    
        $tickets = $query->orderByRaw("FIELD(urgent, 1) DESC, FIELD(for_acceptance, 'Declined') DESC, created_at DESC")->get();
    
        return view('tickets', compact('tickets')); // filter data
    }

    public function indexinspector(Request $request)
    {
        $query = Ticket::query(); 
    
        if ($request->has('status') && $request->status !== '') {
            if ($request->status == 'all') {
                $query->whereIn('status', ['working', 'nonworking', 'open', 'closed', 'assigned']);
            } elseif ($request->status == 'closed') {
                $query->whereIn('status', ['working', 'nonworking']); // clse status
            } else {
                $query->where('status', $request->status);
            }
        }
        
    
        $tickets = $query->orderByRaw("FIELD(urgent, 1) DESC, FIELD(for_acceptance, 'Declined') DESC, created_at DESC")->get();
    
        return view('inspector.inspectorTickets', compact('tickets')); // filter data
    }

    public function indexadmin(Request $request)
    {
        $query = Ticket::query(); 
    
        if ($request->has('status') && $request->status !== '') {
            if ($request->status == 'all') {
                $query->whereIn('status', ['working', 'nonworking', 'open', 'closed', 'assigned']);
            } elseif ($request->status == 'closed') {
                $query->whereIn('status', ['working', 'nonworking']); // clse status
            } else {
                $query->where('status', $request->status);
            }
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->get();
        return view('admin.adminTickets', compact('tickets')); // filter data
    }
    

    public function update(Request $request, Ticket $ticket)
    {
        // Validation
        $request->validate([
            'deviceStatus' => 'required|in:working,nonworking,forprocurement',
            'maintenance' => 'nullable|array',
            'maintenance.*' => 'string',
            'action' => 'nullable|string',
            'need_parts' => 'nullable|string',
            'checked_by' => 'nullable|string',
            'date_checked' => 'nullable|date',
            'noted_by' => 'nullable|string',
            'service' => 'nullable|string',
        ]);
    
        $maintenance = $request->has('maintenance') ? json_encode($request->maintenance) : null;
    
        // Update ticket fields
        $deviceStatus = $request->deviceStatus;
$updateData = [
    'status' => $deviceStatus,
    'maintenance' => $maintenance,
    'action' => $request->action,
    'parts' => $request->need_parts,
    'service' => $request->service,
    'repairDate' => now(),
    'noted' => $request->noted_by,
    'urgent' => '0',
    'auto_close_date' => now()->addMinutes(1),
];

if ($deviceStatus === 'working' || $deviceStatus === 'nonworking') {
    $updateData['for_acceptance'] = '1';
}

$ticket->update($updateData);
    
        return redirect('/inspector/tickets')->with('success', 'Ticket status updated. Awaiting user acceptance.');

    }
    
    

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets', compact('ticket'));
    }
    

    public function edit(Ticket $ticket)
    {
        return view('inspector.report', compact('ticket'));
    }

    public function finalReport($id)
    {
        $ticket = Ticket::findOrFail($id);
        $role = Auth::user()->role; 

        return view('finalreport', compact('ticket', 'role')); // Pass both ticket and role
    }

    public function destroy($id)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->delete();

    return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully');
}

public function rateTicket($id)
{
    $ticket = Ticket::findOrFail($id);
    return view('rate_ticket', compact('ticket'));
}

public function submitRating(Request $request, $id)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
    ]);

    $ticket = Ticket::findOrFail($id);
    $ticket->rating = $request->rating;
    $ticket->review = $request->review;
    $ticket->save();

    return redirect('/tickets')->with('success', 'Rating submitted successfully!');
}

public function assignTicket(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->repairedBy = $request->inspector;
    $ticket->urgent = $request->urgent;
    $ticket->assignDate = now();
    $ticket->status = 'assigned';
    $ticket->save();

    return response()->json(['message' => 'Ticket assigned successfully!']);
}

public function unassign(Ticket $ticket)
{
    $ticket->update([
        'repairedBy' => null,
        'urgent' => 0,
        'assignDate' => null,
        'status' => 'open',
    ]);

    return response()->json(['message' => 'Ticket unassigned successfully.']);
}


public function showAssignPage(Request $request)
{
    $filter = $request->query('filter', 'unassigned'); // Default filter: unassigned

    // Start query and include inspector and end user relationships
    $query = Ticket::with(['inspector', 'endUser']);

    // Apply filter
    if ($filter === 'assigned') {
        $query->whereNotNull('repairedBy');
    } elseif ($filter === 'unassigned') {
        $query->whereNull('repairedBy');
    }

    // Get filtered tickets
    $tickets = $query->get();

    // Get all inspectors
    $inspectors = User::where('role', 'inspector')->get();

    // Count active assigned tickets per inspector
    $inspectorsWithTickets = $inspectors->map(function ($inspector) {
        $inspector->active_tickets = Ticket::where('repairedBy', $inspector->emp_number)
            ->whereIn('status', ['assigned', 'open']) // or just 'assigned' depending on your logic
            ->count();
        return $inspector;
    });

    return view('admin.assignTickets', compact('tickets', 'inspectors', 'inspectorsWithTickets'));
}


public function inspectorClaimPage()
{

    // Get the currently logged-in inspector
    $inspectors = Auth::user();

    // Ensure the user is an inspector
    if ($inspectors->role !== 'inspector') {
        abort(403, 'Unauthorized access.');
    }

    $tickets = Ticket::where('status', 'open')->get(); // Get all tickets

    return view('inspector.claimTickets', compact('tickets', 'inspectors'));
}

public function accept($id)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->for_acceptance = 0;
    $ticket->save();

    return back()->with('success', 'You accepted the ticket.');
}

public function decline(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->for_acceptance = 'Declined';
    $ticket->decline_reason = $request->input('decline_reason');
    $ticket->save();

    return back()->with('success', 'You declined the ticket.');
}

public function forProcurement()
{
    $tickets = Ticket::where('status', 'forprocurement')->get();
    $role = Auth::user()->role; 
    $allParts = [];

    foreach ($tickets as $ticket) {
        if (!empty($ticket->parts)) {
            $parts = explode(',', $ticket->parts);
            foreach ($parts as $part) {
                $part = trim(strtolower($part)); // ← normalize to lowercase
                if ($part !== '') {
                    $allParts[$part] = ($allParts[$part] ?? 0) + 1;
                }
            }
        }
    }

    return view('forProcurement', compact('tickets', 'allParts','role'));
}


public function exportCsv()
{
    $fileName = 'tickets_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ];

    $callback = function () {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'Ticket ID', 'Equipment Type', 'Brand', 'Serial Number',
            'Problem', 'Accessories', 'Created At',
            'End User', 'Accountable User', 'Other Users', 'Division',
            'Technician', 'Assigned', 'Inspected', 'Status',
            'Urgent', 'Acceptance', 'Rating'
        ]);

        $tickets = Ticket::with(['endUser', 'inspector'])->get();

        foreach ($tickets as $ticket) {
            fputcsv($file, [
                $ticket->custom_id,
                $ticket->equipment_type,
                $ticket->brand,
                $ticket->serialNum,
                $ticket->problem,
                $ticket->accessories,
                $ticket->created_at->format('Y-m-d'),

                $ticket->endUser ? $ticket->endUser->first_name . ' ' . $ticket->endUser->last_name : $ticket->emp_number,
                $ticket->accountableUser,
                $ticket->users,
                $ticket->division,

                $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : '—',
                $ticket->assignDate,
                $ticket->repairDate,
                ucfirst($ticket->status),
                $ticket->urgent ? 'Yes' : 'No',
                $ticket->for_acceptance === 1 ? 'For Acceptance' : ($ticket->for_acceptance === 'Declined' ? 'Declined' : '—'),
                $ticket->rating ?? 'Not Rated'
            ]);
        }

        fclose($file);
    };

    return new StreamedResponse($callback, 200, $headers);
}



}