
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
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
        .no-underline {
            text-decoration: none;
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
</head>
<body>
    <div>
        @include('layouts.userSidebar')
    </div> 
    <div class="content">
        <h2 class="mb-4"><i class="fas fa-file-alt"></i> Open a New Ticket</h2>
        <div class="card">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                <div class="form-group">
                         <h5 class="mb-3"><i class="fas fa-user"></i> User Information</h5>
                            <div class="d-flex flex-wrap gap-3">
                                <input type="text" name="accountableUser" class="form-control" placeholder="Accountable User">
                                <input type="text" name="users" class="form-control" placeholder="Other User/s">
                            </div>
                </div><br>
                <div class="form-group">
                         <h5 class="mb-3"><i class="fas fa-wrench"></i> Device Information</h5>
                            <div class="d-flex flex-wrap gap-3">
                                <input type="text" name="brand" class="form-control" placeholder="Brand/Model" value="{{ old('other_brand') }}">
                                <input type="text" name="serialNum" class="form-control" placeholder="Serial Number" value="{{ old('other_serialNum') }}">
                                <input type="text" name="propertyID" class="form-control" placeholder="Property ID" value="{{ old('other_propertyID') }}">
                            </div>
                    </div><br>
                    <h5 class="mb-3"><i class="fas fa-desktop"></i> ICT Equipment Type</h5>
                    
                    <div class="d-flex flex-wrap gap-3">
                        @php
                            $equipment_type = ['Desktop', 'Laptop', 'Monitor', 'Printer', 'KB/Mouse', 'UPS/AVR'];
                        @endphp
                        @foreach ($equipment_type as $equipment_type)
                        <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="equipment_type[]" value="{{ $equipment_type }}">
                                <label class="form-check-label">{{ ucfirst($equipment_type) }}</label>
                            </div>
                        @endforeach
                        <input type="text" name="other_equipment" class="form-control" placeholder="Specify if Other">
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-exclamation-triangle"></i> Problems Encountered</h5>
                    <div class="d-flex flex-wrap gap-3">
                        @php
                        $problems = ['Hang', 'Upgrade/Update', 'Intermittent', 'Blackout/No Power', 'No Disply/Garbage', 'Virus', 'Software', 'Color', 'Jam', 'Mechanical', 'Wont Read', 'Wont Detect', 'Wont Print', 'Dirty Printout', 'Stuck up', 'Malfunction Keys', 'Not Functioning', 'Wont Charge', 'LAN Problem', 'Boot Problem', 'Installation'];
                        @endphp
                        @foreach ($problems as $problem)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="problem[]" value="{{ $problem }}">
                                <label class="form-check-label">{{ ucfirst($problem) }}</label>
                            </div>
                        @endforeach
                        <input type="text" name="other_problem" class="form-control" placeholder="Specify if Other">
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-plug"></i> Accessories Included</h5>
                    <div class="d-flex flex-wrap gap-3">
                        @php
                            $accessories = ['Ink/Toner', 'AC Adaptor', 'Power Cord', 'USB'];
                        @endphp
                        @foreach ($accessories as $accessory)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="accessories[]" value="{{ $accessory }}">
                                <label class="form-check-label">{{ ucfirst($accessory) }}</label>
                            </div>
                        @endforeach
                        <input type="text" name="other_accessory" class="form-control" placeholder="Specify if Other">
                    </div>
                </div>
                
                <a class="no-underline" href="/tickets"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane"></i> Submit Ticket</button></a>
            </form>
        </div>
    </div>

