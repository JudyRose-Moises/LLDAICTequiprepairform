<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspector Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div>
    @if ($role == 'inspector')
        @include('layouts.inspectorSidebar')
    @elseif ($role == 'employee')
        @include('layouts.userSidebar')
    @elseif ($role == 'admin')
        @include('layouts.adminSidebar')
    @endif
    </div>
    <div class="content">        
        <h2 class="text-left mb-4"><i class="fas fa-file-alt"></i> Inspector Report</h2>
        <div class="card">
            <h4 class="text-center">Ticket Details</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Equipment Type</th>
                            <th>Model & Serial Number</th>
                            <th>Problems</th>
                            <th>Accessories</th>
                            <th>Date Created</th>
                            <th>Date Assigned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->equipment_type }}</td>
                            <td>{{ $ticket->brand }}, {{ $ticket->serialNum }}</td>
                            <td>{{ $ticket->problem }}</td>
                            <td>{{ $ticket->accessories }}</td>
                            <td>{{ $ticket->created_at->format('Y-m-d H:i A') }}</td>
                            <td>{{ $ticket->assignDate }}</td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <div class="card mt-4">
        <h4 class="text-center">Inspector Form</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Maintenance</th>
                        <th>Service Performed</th>
                        <th>Status</th>
                        <th>Action Required</th>
                        <th>Need Parts</th>
                        <th>Checked / Repaired by</th>
                        <th>Date</th>
                        <th>Noted By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $ticket->maintenance }}</td>
                        <td>{{ $ticket->service }}</td>
                        <td>
                            <span class="badge {{ $ticket->status == 'working' ? 'bg-success' : ($ticket->status == 'nonworking' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td>{{ $ticket->action ?? 'N/A' }}</td>
                        <td>{{ $ticket->parts }}</td>
                        <td>{{ $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : '—' }}</td>
                        <td>{{ $ticket->repairDate }}</td>
                        <td>{{ $ticket->noted }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card mt-4">
        <h4 class="text-center">Service Review</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>⭐ {{ $ticket->rating }} Stars</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> Review: {{ $ticket->review }} </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }
    .content {
        margin-left: 300px;
        padding: 30px;
    }
    .card {
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
    }
    .table th {
        background-color: #f4f4f4;
    }
    .badge {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
    }
    @media (max-width: 768px) {
        .content {
            margin-left: 0;
            padding: 15px;
        }
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            box-shadow: none;
        }
    }
</style>