<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <meta name="base-url" content="{{ url('/') }}">

    <title>Assign Tickets</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-size: 18px; /* Increased font size */
        }
        .content {
            margin-left: 280px;
            padding: 30px;
        }
        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .filter-section h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
        }
        .filter-section select {
            width: 200px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }
        .filter-section select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        .table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .table th {
            text-align: center;
            background: #2c3e50;
            color: white;
            font-size: 20px;
            padding: 15px;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
            font-size: 18px;
            padding: 15px;
        }
        .assign-inspector {
            width: 100%;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .urgent-checkbox {
            transform: scale(2);
            cursor: pointer;
        }
        .badge-success {
            background-color: #28a745;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-assign {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 18px;
            transition: 0.3s;
            cursor: pointer;
        }
        .btn-assign:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div>
    @include('layouts.inspectorSidebar')
</div>

<div class="content">
    <h2 style="font-size: 28px;"><i class="fas fa-tasks"></i> Claim Tickets</h2>

    <!-- Tickets Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Accountable User</th>
                <th>Issue</th>
                <th>Assign Inspector</th>
                <th>Urgent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="ticket-list">
            @foreach($tickets as $ticket)
            <tr>
                <td><strong>{{ $ticket->id }}</strong></td>
                <td>{{ $ticket->accountableUser }}</td>
                <td>{{ $ticket->problem }}</td>
                <td>
                    @if ($ticket->repairedBy)
                        
                        {{ $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : '—' }}
                        
                    @else

                    <select class="assign-inspector" data-ticket-id="{{ $ticket->id }}">
                        <option value="{{ Auth::user()->emp_number }}">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </option>
                    </select>

                    @endif
                </td>
                <td>
                    <input type="checkbox" class="urgent-checkbox" data-ticket-id="{{ $ticket->id }}" {{ $ticket->urgent ? 'checked' : '' }}>
                </td>
                <td>
                    <button class="btn-assign assign-btn" data-ticket-id="{{ $ticket->id }}">
                                Claim
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function assignTicket(ticketId) {
        let inspectorId = document.querySelector(`.assign-inspector[data-ticket-id='${ticketId}']`).value;
        let urgent = document.querySelector(`.urgent-checkbox[data-ticket-id='${ticketId}']`).checked ? 1 : 0;

        fetch(`/inspector/assign/${ticketId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ inspector: inspectorId, urgent: urgent })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not OK');
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            window.location.reload();  // just reload the page para refresh ang ticket list
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to assign ticket.');
        });
    }

    // Attach to assign button
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('assign-btn')) {
            let ticketId = event.target.getAttribute('data-ticket-id');
            assignTicket(ticketId);
        }
    });
});
</script>



</body>
</html>
