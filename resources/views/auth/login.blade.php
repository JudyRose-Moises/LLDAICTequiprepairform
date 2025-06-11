<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg,rgb(35, 148, 78), #4ca1af);
        }

        .container {
            display: flex;
            height: 90vh;
            width: 80vw;
            max-width: 1100px;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .info-section {
            width: 50%;
            background:rgb(10, 83, 70);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }

        .info-section img {
            width: 80px;
            margin-bottom: 15px;
        }

        .form-container {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
            color: #444;
        }

        .form-control {
            width: 100%;
            height: 40px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn-primary {
            width: 100%;
            height: 45px;
            background: #4ca1af;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .subtext {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }

        .subtext a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .subtext a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90vw;
                height: auto;
            }

            .info-section, .form-container {
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="info-section">
            <img src="{{ asset('images/lldalogo.png') }}" alt="Logo">
            <h2>LAGUNA LAKE DEVELOPMENT AUTHORITY</h2>
            <p>Information & Communication Technology (ICT) Equipment Repair Form</p>
        </div>
        <div class="form-container">
            <div class="card">
                <div class="header">{{ __('Login') }}</div>
                
                @if ($errors->any())
                    <div class="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="emp_number" class="form-label">{{ __('Username') }}</label>
                        <input id="emp_number" type="text" name="emp_number" value="{{ old('emp_number') }}" required autofocus class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                </form>

                <div class="subtext">Don't have an account? <a href="{{ route('register') }}">Register</a></div> 
            </div>
        </div>
    </div>
</body>
</html>
