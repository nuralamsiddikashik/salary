<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Payroll System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            width: 360px;
            background: #fff;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .login-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 13px;
            color: #4f46e5;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            border: none;
            background: #4f46e5;
            color: #fff;
            font-size: 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #4338ca;
        }

        .error {
            background: #ffe5e5;
            color: #b91c1c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .success {
            background: #e6fffa;
            color: #065f46;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="login-title">
            Payroll Login
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >

                    <span
                        class="toggle-password"
                        id="toggleText"
                        onclick="togglePassword()"
                    >
                        Show
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>

        </form>

        <div class="footer">
            Payroll Management System
        </div>

    </div>

    <script>
        function togglePassword() {

            const password = document.getElementById('password');
            const toggleText = document.getElementById('toggleText');

            if (password.type === "password") {
                password.type = "text";
                toggleText.innerText = "Hide";
            } else {
                password.type = "password";
                toggleText.innerText = "Show";
            }

        }
    </script>

</body>

</html>