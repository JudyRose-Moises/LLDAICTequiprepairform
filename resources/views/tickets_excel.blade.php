<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tickets Excel View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e3e3e3;
        }
    </style>
</head>
<body>
    <h2>Ticket Export (Excel-style View)</h2>

    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Equipment Type</th>
                <th>Brand</th>
                <th>Serial No</th>
                <th>Problem</th>
                <th>Accessories</th>
                <th>Created</th>
                <th>End User</th>
                <th>Technician</th>
                <th>Status</th>
                <th>Urgent</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->custom_id }}</td>
                    <td>{{ $ticket->equipment_type }}</td>
                    <td>{{ $ticket->brand }}</td>
                    <td>{{ $ticket->serialNum }}</td>
                    <td>{{ $ticket->problem }}</td>
                    <td>{{ $ticket->accessories }}</td>
                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                    <td>
                        {{ $ticket->endUser ? $ticket->endUser->first_name . ' ' . $ticket->endUser->last_name : $ticket->emp_number }}
                    </td>
                    <td>
                        {{ $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : '—' }}
                    </td>
                    <td>{{ ucfirst($ticket->status) }}</td>
                    <td>{{ $ticket->urgent ? 'Yes' : 'No' }}</td>
                    <td>{{ $ticket->rating ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
