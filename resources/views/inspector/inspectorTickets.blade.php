
<div>
    @include('layouts.inspectorSidebar')
</div>
    <div class="content">
        <h2 class="mb-4"><i class="fas fa-file-alt"></i> Submitted Tickets</h2>
        <div class="filter-section">
        <h3>Filter Tickets</h3>
            <form method="GET">
            <select name="status" id="statusFilter" onchange="this.form.submit()">
                <option value="all">All Status</option>
                <option value="all">All</option>
                <option value="open">Open</option>
                <option value="assigned">Assigned</option>
                <option value="closed">Closed</option>
                <option value="working">Working</option>
                <option value="nonworking">Nonworking</option>
            </select></form>
        <input type="text" id="ticketSearch" class="form-control" placeholder="Search tickets...">
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            @php
                $assigned = $tickets->where('repairedBy', Auth::user()->emp_number);
            @endphp
                <tbody>
                    @foreach($assigned as $ticket)
                    <tr>
                    <td>
                    <a href="{{ route('tickets.edit', $ticket->id) }}"><div class="ticket-card"> 
                            <!-- Equipment & Problem Section -->
                            <div class="ticket-info">
                                <strong>{{ $ticket->equipment_type }}</strong><br><br>
                                <small>Ticket ID: {{ $ticket->custom_id }}</small><br>
                                <small>Model: {{ $ticket->brand }}, SN: {{ $ticket->serialNum }}</small><br>
                                <small>Property ID: {{ $ticket->propertyID }}</small><br>
                                <small>Problem: {{ $ticket->problem }}</small><br>
                                <small>Accessories: {{ $ticket->accessories }}</small><br>
                                <small>Ticket Created: {{ $ticket->created_at->format('Y-m-d') }}</small><br>
                            </div>

                            <!-- Inspector & Date Section -->
                            <div class="inspector-info">
                            <small><strong>End User:</strong> 
                                {{ $ticket->endUser ? $ticket->endUser->first_name . ' ' . $ticket->endUser->last_name : $ticket->emp_number }}
                            </small><br>
                            <small><strong>Accountable User:</strong> {{ $ticket->accountableUser }}</small><br>
                            <small><strong>Division/Section:</strong> {{ $ticket->division }}</small><br><br>
                            <small><strong>Technician:</strong>
                            {{ $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : '—' }}</small><br>
                                <small><strong>Assigned:</strong> {{ $ticket->assignDate }}</small><br>
                                <small><strong>Inspected:</strong> {{ $ticket->repairDate }}</small>
                            </div> 

<!-- Status & Urgent Label -->
<div class="status-rating d-inline d-flex flex-wrap">
    @php
        $statusColors = [
            'open' => 'badge bg-primary',
            'assigned' => 'badge bg-warning text-dark',
            'working' => 'badge bg-success',
            'nonworking' => 'badge bg-danger'
        ];
        $statusColor = $statusColors[$ticket->status] ?? 'badge bg-info';
    @endphp

    <!-- Urgent Badge -->
    @if($ticket->urgent == 1)
        <span class="badge bg-info text-light" style="font-size: 16px; padding: 8px 12px; margin-left: 10px;">
            🚨 Urgent
        </span>
        @else
            <!-- Ticket Status -->
    <span class="{{ $statusColor }}" style="font-size: 16px; padding: 8px 12px;">
        {{ ucfirst($ticket->status) }}
    </span>
    @endif

    @if($ticket->status === 'working' || $ticket->status === 'nonworking')
        @if($ticket->for_acceptance == 1)
            <span class="badge bg-secondary text-light" style="font-size: 16px; padding: 8px 12px;">
                For Acceptance
            </span>
        @elseif($ticket->for_acceptance === 'Declined')
            <span class="badge bg-info text-light" style="font-size: 16px; padding: 8px 12px;">
                Declined
            </span>
        @else

            <!-- Show Rating if ticket is completed -->
            @if($ticket->rating)
                <span class="badge bg-warning text-dark" style="font-size: 16px; padding: 8px 12px;">
                    ⭐ {{ $ticket->rating }}
                </span>
            @else
                <span class="badge bg-danger text-light" style="font-size: 16px; padding: 8px 12px;">
                    Not Rated
                </span>
            @endif
        @endif
    @endif
</div>



                            <!-- Action Buttons -->
                            
<div class="ticket-actions d-inline">
    @if($ticket->status === 'working' || $ticket->status === 'nonworking')
        <form action="{{ route('finalreport', $ticket->id) }}" class="d-inline">
            <button class="btn btn-outline-warning btn-lg"> Report</button>
        </form>
    @endif

    <form method="POST" action="{{ route('ticket.deleteticket', ['id' => $ticket->id]) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-lg" onclick="return confirm('Delete ticket?')">
            Delete
        </button>
    </form>
</div>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('ticketSearch');
    searchInput.addEventListener('keyup', function () {
        let filter = searchInput.value.toLowerCase();
        document.querySelectorAll('.table tbody tr').forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const statusFilter = document.getElementById('statusFilter');
        const equipmentFilter = document.getElementById('equipmentFilter');

        function filterTickets() {
            const rows = document.querySelectorAll('.table tbody tr');
            const statusValue = statusFilter.value.toLowerCase();
            const equipmentValue = equipmentFilter.value.toLowerCase();

            rows.forEach(row => {
                const statusCell = row.cells[1].querySelector('span').textContent.trim().toLowerCase();
                const equipmentCell = row.cells[2].textContent.trim().toLowerCase();

                const statusMatch = statusValue === '' || statusCell === statusValue;
                const equipmentMatch = equipmentValue === '' || equipmentCell.includes(equipmentValue);

                row.style.display = statusMatch && equipmentMatch ? 'table-row' : 'none';
            });
        }


        statusFilter.addEventListener('change', filterTickets);
        equipmentFilter.addEventListener('change', filterTickets);
    });
</script>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif

<style>
/* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
}
.content {
        margin-left: 300px;
        padding: 30px;
    }
/* Container */
.container {
    max-width: 90%;
    margin: auto;
}

/* Ticket Card */
.ticket-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 3px solid black;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    background: white;
    transition: 0.3s ease;
}
.ticket-card:hover {
    background:rgba(91, 91, 91, 0.08);
    transform: translateX(20px);
}

/* Info Sections */
.ticket-info, .inspector-info {
    width: 30%;
    padding: 10px;
    border-right: 3px dashed black;
    font-size: 18px;
}

/* Status & Rating */
.status-rating {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 20%;
    font-size: 18px;
}

/* Buttons */
.ticket-actions {
    display: flex;
    gap: 15px;
    width: 20%;
    justify-content: center;
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
        a {
            text-decoration: none;
            color: inherit;
        }
/* Responsive */
@media (max-width: 768px) {
    .ticket-card {
        flex-direction: column;
        align-items: flex-start;
    }
    .content {
            margin-left: 0;
            padding: 15px;
        }

    .ticket-info, .inspector-info, .status-rating, .ticket-actions {
        width: 100%;
        text-align: center;
        border-right: none;
    }
}
.badge.bg-info {
    background-color:rgb(220, 53, 170) !important;
    font-weight: bold;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    50% { opacity: 0.5; }
}



</style>