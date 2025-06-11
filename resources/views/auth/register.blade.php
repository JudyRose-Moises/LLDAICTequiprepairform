<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                <div class="header">{{ __('Register') }}</div> 
                
                @if ($errors->any())
                    <div class="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                        <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="emp_number" class="form-label">{{ __('Employee Number') }}</label>
                        <input id="emp_number" type="text" class="form-control" name="emp_number" value="{{ old('emp_number') }}" required>
                    </div>

                    <div class="form-group">
                <label for="division" class="form-label">{{ __('Division/Section') }}</label>
                <select name="division" id="division" class="form-control">
                    <optgroup label="Office of the Assistant General Manager">
                        <option value="Legal and Adjudication Division">Legal and Adjudication Division</option>
                        <option value="Policy Planning and Information Management Division">Policy Planning and Information Management Division</option>
                    </optgroup>
                    <optgroup label="Management Services Department">
                        <option value="Administrative Division">Administrative Division</option>
                        <option value="Finance Division">Finance Division</option>
                    </optgroup>
                    <optgroup label="Resource Management Development Department">
                        <option value="Project Development Management & Evaluation Division">Project Development Management & Evaluation Division</option>
                        <option value="Community Division">Community Division</option>
                        <option value="Environmental Laboratory & Research Division">Environmental Laboratory & Research Division</option>
                    </optgroup>
                    <optgroup label="Environmental Regulations Department">
                        <option value="Clearance & Permits Division">Clearance & Permits Division</option>
                        <option value="Surveillance and Monitoring Division">Surveillance and Monitoring Division</option>
                        <option value="Enforcement Division">Enforcement Division</option>
                        <option value="Environmental Compliance Division">Environmental Compliance Division</option>
                    </optgroup>
                </select>
            </div>

                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                </form>

                <div class="subtext">Already registered? <a href="{{ route('login') }}">Login</a></div> 
            </div>
        </div>
    </div>
</body>
</html>
