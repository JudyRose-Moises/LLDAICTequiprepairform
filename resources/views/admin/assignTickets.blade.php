<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assign Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-size: 18px; }
        .content { margin-left: 280px; padding: 30px; }
        .filter-section {
            margin-bottom: 20px; padding: 15px; background: white; border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .filter-section h3 { margin-bottom: 15px; color: #2c3e50; }
        .filter-section input[type="radio"] {
            height: 20px; width: 20px; vertical-align: middle;
        }
        .table { background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
        .table th, .table td {
            text-align: center; font-size: 18px; padding: 15px; vertical-align: middle;
        }
        .table th { background: #2c3e50; color: white; font-size: 20px; }
        .assign-inspector {
            width: 100%; font-size: 18px; padding: 10px; border-radius: 5px; border: 1px solid #ddd;
        }
        .urgent-checkbox { transform: scale(2); cursor: pointer; }
        .btn-assign {
            background-color: #007bff; color: white; border: none;
            padding: 10px 15px; border-radius: 5px; font-size: 18px; transition: 0.3s;
        }
        .btn-assign:hover { background-color: #0056b3; }
        @media (max-width: 768px) {
            .content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

@include('layouts.adminSidebar')

<div class="content">
    <h2><i class="fas fa-tasks"></i> Assign Tickets</h2>

    <!-- Filter -->
    <div class="filter-section">
        <h3>Filter Tickets</h3>
        <form id="filter-form">
            <label>
                <input type="radio" name="ticket_filter" value="assigned"> Assigned
            </label>
            &nbsp;&nbsp;
            <label>
                <input type="radio" name="ticket_filter" value="unassigned" checked> Unassigned
            </label>
        </form>
    </div>

    <!-- Inspector Load -->
    <div class="filter-section">
        <h2><i class="fas fa-users"></i> Inspectors and Active Tickets</h2>
        <table class="table table-striped">
            <thead>
                <tr><th>Inspector</th><th>Active Tickets</th></tr>
            </thead>
            <tbody>
                @foreach($inspectorsWithTickets as $inspector)
                <tr>
                    <td><strong>{{ $inspector->first_name }} {{ $inspector->last_name }}</strong></td>
                    <td><span class="badge bg-primary" style="font-size: 18px;">{{ $inspector->active_tickets }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tickets -->
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
            @include('partials.assignTicketRows', ['tickets' => $tickets, 'inspectors' => $inspectors])
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function assignTicket(ticketId) {
        let inspectorId = document.querySelector(`.assign-inspector[data-ticket-id='${ticketId}']`).value;
        let urgent = document.querySelector(`.urgent-checkbox[data-ticket-id='${ticketId}']`).checked ? 1 : 0;

        fetch(`/admin/assign/${ticketId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ inspector: inspectorId, urgent: urgent })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            fetchFilteredTickets();
        })
        .catch(error => {
            console.error(error);
            alert('Failed to assign ticket.');
        });
    }

    function unassignTicket(ticketId) {
        fetch(`/admin/unassign/${ticketId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            fetchFilteredTickets();
        })
        .catch(error => {
            console.error(error);
            alert('Failed to unassign ticket.');
        });
    }

    function fetchFilteredTickets() {
        let selectedFilter = document.querySelector('input[name="ticket_filter"]:checked').value;

        fetch(`/admin/assign?filter=${selectedFilter}`)
            .then(res => res.text())
            .then(html => {
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const newRows = parsed.querySelector('#ticket-list').innerHTML;
                document.querySelector('#ticket-list').innerHTML = newRows;
            });
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('assign-btn')) {
            assignTicket(e.target.dataset.ticketId);
        }
        if (e.target.classList.contains('unassign-btn')) {
            unassignTicket(e.target.dataset.ticketId);
        }
    });

    document.querySelectorAll('input[name="ticket_filter"]').forEach(radio => {
        radio.addEventListener('change', fetchFilteredTickets);
    });

    fetchFilteredTickets();
});
</script>

</body>
</html>
