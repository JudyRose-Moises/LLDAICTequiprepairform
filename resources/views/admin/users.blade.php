<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <meta name="base-url" content="{{ url('/') }}">

    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</head>

<div>
    @include('layouts.adminSidebar')
</div>

<div class="content">
    <h2 class="mb-4"><i class="fas fa-users"></i> Manage Users</h2>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-list"></i> Registered Users</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>
                        <select class="form-select role-select" data-user-id="{{ $user->emp_number }}">
                            <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="inspector" {{ $user->role == 'inspector' ? 'selected' : '' }}>Technician</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success save-role" data-user-id="{{ $user->emp_number }}">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.save-role').forEach(button => {
        button.addEventListener('click', function () {
            let empNumber = this.getAttribute('data-user-id'); // Change to emp_number
            let role = document.querySelector(`.role-select[data-user-id='${empNumber}']`).value;

            fetch(`/admin/users/${empNumber}/update-role`, { // Use emp_number in URL
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ role: role })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update role. Please try again.');
            });
        });
    });
});

</script>
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
