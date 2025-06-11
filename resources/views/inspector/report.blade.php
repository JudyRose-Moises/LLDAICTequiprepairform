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
        @include('layouts.inspectorSidebar')
    </div>>
    <div class="content">        
    <h2 class="text-left mb-4"><i class="fas fa-file-alt"></i> Inspector Report</h2>
        <div class="card">
            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" id="inspectorForm">
                @csrf
                @method('PUT')
                <div class="maintenance-section">
                    <div class="form-group">
                        <label><i class="fas fa-tools"></i> Maintenance</label>
                        <div class="d-flex flex-wrap">
                            @php
                                $maintenance = ['Cleaning', 'Disk Cleanup', 'Defrag', 'Backup Data', 'Virus Scanning', 'Others'];
                            @endphp
                            @foreach ($maintenance as $maintenance)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="maintenance[]" value="{{ $maintenance }}" 
                                        {{ is_array(old('maintenance')) && in_array($maintenance, old('maintenance')) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ ucfirst(str_replace('_', ' ', $maintenance)) }}</label>
                                </div>
                            @endforeach
                            <input type="text" name="other_maintenance" class="form-control ms-2" placeholder="Specify if Other" value="{{ old('other_maintenance') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-wrench"></i> Service Performed</label>
                        <input type="text" name="service" class="form-control" placeholder="Specify service performed" value="{{ old('other_service') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-info-circle"></i> Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="deviceStatus" id="forprocurement" value="forprocurement" onclick="toggleDropdown(false)">
                        <label class="form-check-label" for="forprocurement">FOR PROCUREMENT</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="deviceStatus" id="working" value="working" onclick="toggleDropdown(false)">
                        <label class="form-check-label" for="working">WORKING</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="deviceStatus" id="nonworking" value="nonworking" onclick="toggleDropdown(true)">
                        <label class="form-check-label" for="nonworking">NON-WORKING</label>
                    </div>
                </div>
                
                <div class="form-group" id="actionDropdown" style="display: none;">
                    <label><i class="fas fa-exclamation-triangle"></i> Action Required</label>
                    <select name="action" class="form-control">
                        <option value="N/A">N/A</option>
                        <option value="For Outside Repair/services">For Outside Repair/services</option>
                        <option value="For disposal">For disposal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-cogs"></i> Need Parts</label>
                    <input type="text" name="need_parts" class="form-control" placeholder="Specify needed parts" value="{{ old('need_parts') }}">
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fas fa-save"></i> {{ isset($ticket) ? 'Update Report' : 'Submit Report' }}</button>
            </form>
        </div>
        <div class="card mt-4 row d-flex flex-wrap ">
            <h4 class="text-center">Ticket Details</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Equipment Type</th>
                        <th>Model & Serial Number</th>
                        <th>Problems</th>
                        <th>Accessories</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>
                            <a href="{{ route('tickets.edit', $ticket->id) }}">
                                <span class="badge {{ $ticket->status == 'working' ? 'bg-success' : ($ticket->status == 'nonworking' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ $ticket->status }}
                                </span>
                            </a>
                        </td>
                        <td>{{ $ticket->equipment_type }}</td>                        
                        <td>{{ $ticket->brand }}, {{ $ticket->serialNum }}</td>
                        <td>{{ $ticket->problem }}</td>
                        <td>{{ $ticket->accessories }}</td>
                        <td>{{ $ticket->created_at->format('Y-m-d H:i A') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<script>
    function toggleDropdown(show) {
        document.getElementById('actionDropdown').style.display = show ? 'block' : 'none';
    }
</script>
</script>

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif

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
        .form-check-input {
            transform: scale(1.3);
            margin-right: 10px;
        }
        .form-control {
            max-width: 400px;
            font-size: 16px;
        }
        .btn-primary {
            font-size: 18px;
            padding: 12px 20px;
            background: #3498db;
            border: none;
            transition: 0.3s;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: #2980b9;
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
