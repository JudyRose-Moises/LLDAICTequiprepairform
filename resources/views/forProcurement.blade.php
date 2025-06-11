<div>
@if ($role == 'inspector')
        @include('layouts.inspectorSidebar')
    @elseif ($role == 'admin')
        @include('layouts.adminSidebar')
@endif
</div>

<div class="content">
    <h2 class="mb-4"><i class="fas fa-toolbox"></i> For Procurement Tickets</h2>

    <div class="row">
        <!-- Ticket List Column -->
        <div class="col-lg-8 mb-4">
            <div class="mb-3">
                <input type="text" id="ticketSearch" class="form-control" placeholder="Search tickets...">
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>
                                <div class="ticket-card">
                                    <div class="ticket-info">
                                        <strong>{{ $ticket->equipment_type }}</strong><br><br>
                                        <small>Ticket ID: {{ $ticket->id }}</small><br>
                                        <small>Model: {{ $ticket->brand }}, SN: {{ $ticket->serialNum }}</small><br>
                                        <small>Problem: {{ $ticket->problem }}</small><br>
                                        <small>Accessories: {{ $ticket->accessories }}</small><br>
                                        <small>Created: {{ $ticket->created_at->format('Y-m-d') }}</small><br>
                                    </div>

                                    <div class="inspector-info">
                                    <small><strong>End User:</strong> 
                                        {{ $ticket->endUser ? $ticket->endUser->first_name . ' ' . $ticket->endUser->last_name : $ticket->emp_number }}
                                    </small><br>
                                        <small><strong>Accountable User:</strong> {{ $ticket->accountableUser }}</small><br>
                                        <small><strong>Division/Section:</strong> {{ $ticket->division }}</small><br><br>
                                        <small><strong>Technician:</strong> 
                                            {{ $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : '—' }}
                                        </small><br>
                                        <small><strong>Inspected:</strong> {{ $ticket->repairDate }}</small><br>
                                    </div>

                                    <div class="status-rating">
                                        <span class="badge bg-dark text-white mb-2">🧩 Parts Needed</span>
                                        <div style="font-weight: bold;">{{ $ticket->parts ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Grocery Parts List Column -->
        <div class="col-lg-4">
            <div class="card shadow p-3">
                <h5 class="mb-3"><i class="fas fa-list-ul"></i> Needed Parts Summary</h5>
                <ul class="list-group">
                    @forelse($allParts as $part => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ ucfirst($part) }}  {{-- Optional: capitalize only first letter --}}
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </li>
                        @empty
                        <li class="list-group-item text-muted">No parts listed.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .content {
        margin-left: 300px;
        padding: 30px;
    }
    .ticket-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 3px solid black;
        padding: 20px;
        border-radius: 10px;
        background: white;
        transition: 0.3s ease;
    }
    .ticket-card:hover {
        background: rgba(91, 91, 91, 0.08);
        transform: translateX(10px);
    }
    .ticket-info, .inspector-info, .status-rating {
        width: 30%;
        padding: 10px;
        font-size: 17px;
        border-right: 3px dashed black;
    }
    .status-rating {
        border-right: none;
        text-align: center;
    }
    @media (max-width: 768px) {
        .ticket-card {
            flex-direction: column;
        }
        .ticket-info, .inspector-info, .status-rating {
            width: 100%;
            border-right: none;
            text-align: center;
        }
        .content {
            margin-left: 0;
            padding: 15px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('ticketSearch');
    searchInput.addEventListener('keyup', function () {
        let filter = searchInput.value.toLowerCase();
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });
});
</script>
