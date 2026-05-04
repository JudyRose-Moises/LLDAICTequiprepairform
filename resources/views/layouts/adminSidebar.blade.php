
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 style="text-align: left;">Admin: <br><small>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</small></h3>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
        </li>
    </ul>
    <hr>
    <h5>Ticket Management</h5>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="/admin/assign" class="nav-link"><i class="fas fa-user-check"></i> Assign Tickets</a>
        </li>
        <li class="nav-item">
            <a href="/admin/tickets" class="nav-link"><i class="fas fa-ticket"></i> All Tickets</a>
        </li>
        <li class="nav-item">
            <a href="/openticket" class="nav-link"><i class="fas fa-plus"></i> Open Ticket</a>
        </li>
        <li class="nav-item">
            <a href="/procurement" class="nav-link"><i class="fas fa-box-open"></i> For Procurement</a>
        </li>
    </ul>
    <hr>
    <h5>User & System</h5>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> Users</a>
        </li>
        <li class="nav-item">
            <a href="/admin/reviews" class="nav-link"><i class="fas fa-star"></i> Reviews</a>
        </li>
    </ul>
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="/login" class="nav-link"><i class="fa fa-sign-out"></i> Logout</a>
        </li>
    </ul>
</div>
</body>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
       body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            position: fixed;
            padding: 25px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar h3 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #fff;
            border-bottom: 2px solid #1a252f;
            padding-bottom: 10px;
        }
        .nav-link {
            color: white;
            font-size: 18px;
            padding: 12px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-link i {
            margin-right: 12px;
            font-size: 20px;
        }
        .nav-link:hover, .nav-link.active {
            background: #1a252f;
            transform: translateX(5px);
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
