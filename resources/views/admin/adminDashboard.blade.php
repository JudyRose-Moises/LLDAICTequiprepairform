<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .content {
            margin-left: 280px;
            padding: 30px;
        }
        .card {
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            transition: 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .no-underline {
            text-decoration: none;
            color: inherit;
        }
        .no-underline:hover {
            color: inherit;
        }
        .chart-container {
            height: 350px;
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
    @include('layouts.adminSidebar')
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Dashboard</h2>

    <!-- Ticket Counters -->
    <div class="row">
        <div class="col-md-3">
            <a class="no-underline" href="/admin/tickets">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>{{ $tickets['total'] }}</h3>
                        <p>Total Tickets</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="no-underline" href="/admin/assign">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <i class="fas fa-exclamation-circle"></i>
                        <h3>{{ $tickets['unassigned'] }}</h3>
                        <p>Unassigned Tickets</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="no-underline" href="/admin/assign">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        <i class="fas fa-user-check"></i>
                        <h3>{{ $tickets['assigned'] }}</h3>
                        <p>Assigned Tickets</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a class="no-underline" href="/admin/tickets?status=closed">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <i class="fas fa-check-circle"></i>
                        <h3>{{ $tickets['closed'] }}</h3>
                        <p>Completed Tickets</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Ticket Status Chart -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-chart-pie"></i> Ticket Status Distribution</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="ticketStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Tickets Bar Graph -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-chart-bar"></i> Monthly Ticket Distribution</h5>
                </div>
                <div class="card-body chart-container">
                    <canvas id="monthlyTicketsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Data -->
    <div class="row mt-5">
        <!-- Top 5 Most Repaired Equipment -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-tools"></i> Top 5 Most Repaired Equipment
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Times Repaired</th>
                                <th>Common Issues</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topEquipments as $equipment)
                                <tr>
                                    <td>{{ $equipment->equipment_type }}</td>
                                    <td>{{ $equipment->total }}</td>
                                    <td>{{ Str::limit($equipment->issues, 50) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Most Problematic Property ID -->
        <!-- <div class="col-md-6">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body text-center">
                    <h5><i class="fas fa-exclamation-triangle"></i> Most Problematic Property ID</h5>
                    @if ($mostProblematicProperty)
                        <h3 class="font-weight-bold">
                            {{ $mostProblematicProperty->equipment_type }} #{{ $mostProblematicProperty->propertyID }}
                        </h3>
                        <p>Repaired {{ $mostProblematicProperty->total }} times</p>
                    @else
                        <p>No problematic property found.</p>
                    @endif
                </div>
            </div>
        </div> -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('monthlyTicketsChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ticketsPerDay->pluck('day')->toArray()) !!},
                datasets: [{
                    label: 'Tickets Sent This Month',
                    data: {!! json_encode($ticketsPerDay->pluck('count')->toArray()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
            responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { 
                            display: true,
                            text: 'Number of Tickets Sent'
                        }
                    },
                    x: {
                        title: { 
                            display: true,
                            text: 'Day of the Month'
                        }
                    }
                 }
             }
         });

        // Ticket Status Chart (Pie)
        var ctx2 = document.getElementById('ticketStatusChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Unassigned', 'Assigned', 'Completed'],
                datasets: [{
                    data: [{{ $tickets['unassigned'] }}, {{ $tickets['assigned'] }}, {{ $tickets['closed'] }}],
                    backgroundColor: ['rgb(255, 99, 132)', 'rgb(75, 192, 192)', 'rgb(75, 175, 80)'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Ticket Status' }
                }
            }
        });
    });
</script>

</body>
</html>
