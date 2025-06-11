
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .content { margin-left: 280px; padding: 30px; }
        .card { border-radius: 10px; text-align: center; padding: 20px; transition: 0.3s ease; }
        .card:hover { transform: scale(1.05); }
        .card i { font-size: 40px; margin-bottom: 10px; }
        a { text-decoration: none; }
        .no-underline {
            text-decoration: none;
            color: inherit;
        }
        .no-underline:hover {
            color: inherit;
        }
        .section-box { border: 2px solid black; padding: 20px; margin-top: 20px; border-radius: 10px; }
        .ticket-acceptance { position: relative; }
        .accept-btn, .decline-btn { font-size: 20px; position: absolute; top: 5px; right: 10px; cursor: pointer; }
        .accept-btn { color: green; }
        .decline-btn { color: red; right: 35px; }
        .status-working { color: green; font-weight: bold; }
        .status-not-working { color: red; font-weight: bold; }
        @media (max-width: 768px) { .content { margin-left: 0; padding: 20px; } }
    .section-box {
        border: 2px solid black;
        padding: 20px;
        margin-top: 20px;
        border-radius: 10px;
        max-height: 400px;
        overflow-y: auto;
        background-color: #fff;
    }


    </style>
</head>

<body>
    <!-- Sidebar -->
    <div>
        @include('layouts.userSidebar')
    </div>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Dashboard</h2>
        
        <!-- Ticket Summary -->
        <div class="row d-flex flex-wrap">
            <div class="col-md-4">
                <a class="no-underline" href="/tickets">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body">
                            <i class="fas fa-ticket-alt"></i>
                            <h3>{{ $tickets['total'] }}</h3>
                            <p>Total Tickets</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="no-underline" href="/tickets?status=open">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body">
                            <i class="fas fa-exclamation-circle"></i>
                            <h3>{{ $tickets['open'] }}</h3>
                            <p>Open Tickets</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="no-underline" href="/tickets?status=closed">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            <i class="fas fa-check-circle"></i>
                            <h3>{{ $tickets['closed'] }}</h3>
                            <p>Closed Tickets</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Additional Sections -->
        <div class="row">
            <div class="col-md-4">
                
            </div>
            <div class="col-md-4">
            <div class="section-box">
    <h5>Ticket Change Logs</h5>

    @php
        $logs = [];

        foreach ($allTickets as $ticket) {
            $logs[] = [
                'time' => $ticket->created_at,
                'message' => "🟢 Ticket #{$ticket->custom_id} was created"
            ];

            if (!empty($ticket->repairDate)) {
                $logs[] = [
                    'time' => $ticket->repairDate,
                    'message' => "🔧 Ticket #{$ticket->custom_id} was repaired"
                ];
            }

            if (!empty($ticket->assignDate) && !empty($ticket->repairedBy)) {
                $inspectorName = $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : $ticket->repairedBy;

                $logs[] = [
                    'time' => $ticket->assignDate,
                    'message' => "📌 Ticket #{$ticket->custom_id} was assigned to Technician: <strong>{$inspectorName}</strong>"
                ];
            }
        }

        // Sort logs in descending time order
        usort($logs, function ($a, $b) {
            return strtotime($b['time']) <=> strtotime($a['time']);
        });
    @endphp

    @forelse ($logs as $log)
        <div class="mb-3 border-bottom pb-2">
            {!! $log['message'] !!}<br>
            <small class="text-muted">{{ \Carbon\Carbon::parse($log['time'])->format('Y-m-d h:i A') }}</small>
        </div>
    @empty
        <p class="text-muted">No change logs to display.</p>
    @endforelse
</div>


            </div>
            
            <!-- Ticket Acceptance Section -->
            <div class="col-md-4">
    <div class="section-box ticket-acceptance">
        <h5 class="mb-3"><i class="fas fa-clock"></i> Pending Ticket Acceptance</h5>

        @php
            $ticketsToAccept = $pendingTickets->where('for_acceptance', 1);
        @endphp

        @forelse($ticketsToAccept as $ticket)
            <div class="card shadow-sm mb-3">
                <a class="no-underline" href="{{ route('finalreport', $ticket->id) }}">
                    <div class="card-body">
                        <h6><strong>Ticket #{{ $ticket->id }}</strong></h6>
                        <p class="mb-1">{{ $ticket->equipment_type }}</p>
                        <p class="text-muted small">
                            Model: {{ $ticket->brand }}, SN: {{ $ticket->serialNum }}<br>
                            Problem: {{ $ticket->problem }}<br>
                            Created: {{ $ticket->created_at->format('Y-m-d') }}
                        </p>
                        <p>
                            <span class="badge {{ strtolower($ticket->status) === 'working' ? 'bg-success' : (strtolower($ticket->status) === 'nonworking' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </p>

                        {{-- Countdown Display --}}
                        @if($ticket->auto_close_date)
                            <p>Close in: <span id="countdown-{{ $ticket->id }}"></span></p>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <form action="{{ route('tickets.accept', $ticket->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Accept</button>
                            </form></a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="showDeclineModal({{ $ticket->id }})">
                                <i class="fas fa-times"></i> Decline
                            </button>

                        </div>
                    </div>
            </div>
        @empty
            <p class="text-muted">No pending tickets.</p>
        @endforelse
    </div>
</div>


        </div>

    </div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="declineForm" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="declineModalLabel">Decline Ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to decline this ticket?</p>
          <div class="mb-3">
                <label for="declineReason" class="form-label">Optional Reason:</label>
                <textarea class="form-control" id="declineReason" name="decline_reason" rows="3" maxlength="255" placeholder="Enter reason (optional)" oninput="updateCounter()"></textarea>
                <div class="text-end">
                    <small id="charCount">0 / 255 characters</small>
                </div>
            </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm Decline</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.accept-btn').forEach(button => {
            button.addEventListener('click', function () {
                let ticketId = this.getAttribute('data-ticket-id');
                updateTicketStatus(ticketId, 'Accepted');
            });
        });

        document.querySelectorAll('.decline-btn').forEach(button => {
            button.addEventListener('click', function () {
                let ticketId = this.getAttribute('data-ticket-id');
                updateTicketStatus(ticketId, 'Declined');
            });
        });

        @foreach($ticketsToAccept as $ticket)
        @if($ticket->auto_close_date)
            function countdown{{ $ticket->id }}() {
                const countDownDate = new Date("{{ $ticket->auto_close_date }}").getTime();
                const now = new Date().getTime();
                const distance = countDownDate - now;

                if (distance > 0) {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById('countdown-{{ $ticket->id }}').innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";
                } else {
                    document.getElementById('countdown-{{ $ticket->id }}').innerHTML = "EXPIRED";
                }
            }

            countdown{{ $ticket->id }}();
            setInterval(countdown{{ $ticket->id }}, 1000);
        @endif
    @endforeach

        function updateTicketStatus(ticketId, status) {
            fetch(`/tickets/${ticketId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Ticket ${status}!`);
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    function showDeclineModal(ticketId) {
        const form = document.getElementById('declineForm');
        form.action = `/tickets/${ticketId}/decline`; // Set the correct route
        document.getElementById('declineReason').value = ''; // Reset textarea
        const modal = new bootstrap.Modal(document.getElementById('declineModal'));
        modal.show();
    }
    function updateCounter() {
    const textarea = document.getElementById('declineReason');
    const counter = document.getElementById('charCount');
    counter.textContent = `${textarea.value.length} / 255 characters`;
}
</script>
