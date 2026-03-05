<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | ASHIS Auto Solution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            margin: 0;
            background-color: #f8fafc; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* মোবাইল স্ক্রিনে যেন কার্ডটি একদম উপরে লেগে না যায় */
            padding: 15px;
        }

        .login-card {
            width: 100%;
            /* ডেস্কটপে ম্যাক্স সাইজ */
            max-width: 450px;
            background: #ffffff;
            /* রেসপনসিভ প্যাডিং */
            padding: 40px 30px;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
            transition: all 0.3s ease;
        }

        .logo-wrapper {
            margin-bottom: 30px;
        }

        .company-logo-img {
            /* লোগো যেন স্ক্রিনের বাইরে না যায় */
            max-width: 100%;
            height: auto;
            width: 220px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.03));
        }

        .brand-tagline {
            margin-top: 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #94a3b8;
            font-weight: 600;
        }

        .login-header {
            margin-bottom: 25px;
            text-align: left;
        }

        .login-title {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
        }

        .input-ctrl {
            width: 100%;
            /* টাচ করার জন্য উপযুক্ত হাইট */
            padding: 14px 16px;
            border: 1.5px solid #edf2f7;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #ffffff;
            color: #1e293b;
            /* আইফোনে ইনপুট ফিল্ডের ডিফল্ট স্টাইল বন্ধ করতে */
            -webkit-appearance: none;
        }

        .input-ctrl:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.08);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 11px;
            font-weight: 800;
            color: #0ea5e9;
            padding: 8px 5px;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            border: none;
            background: #1e293b;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-login:hover {
            background: #0ea5e9;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        /* রেসপনসিভ অ্যালার্ট */
        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
        }
        .error { background: #fff1f2; color: #991b1b; border-left: 4px solid #f43f5e; }
        .success { background: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }

        .footer-text {
            margin-top: 30px;
            font-size: 12px;
            color: #cbd5e1;
            font-weight: 500;
        }

        /* ফোন এবং ট্যাবলেটের জন্য মিডিয়া কোয়েরি */
        @media (max-width: 480px) {
            body {
                background-color: #ffffff; /* মোবাইলে ক্লিন লুকের জন্য */
                padding: 10px;
                align-items: flex-start; /* ফোন স্ক্রিনে কার্ডটি কিছুটা উপরে থাকবে */
                padding-top: 40px;
            }

            .login-card {
                padding: 30px 20px;
                border-radius: 0; /* মোবাইলে ফুল স্ক্রিন ফিল দিতে চাইলে */
                border: none;
                box-shadow: none;
            }

            .login-title {
                font-size: 22px;
            }

            .company-logo-img {
                width: 180px; /* ফোনে লোগো কিছুটা ছোট করা হয়েছে */
            }
        }

        /* ছোট ফোনের জন্য স্পেশাল অ্যাডজাস্টমেন্ট */
        @media (max-height: 600px) {
            body {
                align-items: flex-start;
            }
            .login-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        
        <div class="logo-wrapper">
            <img src="{{ asset('assets/logo/image.png') }}" alt="ASHIS Auto Solution" class="company-logo-img">
            <div class="brand-tagline">Payroll Management</div>
        </div>

        <div class="login-header">
            <div class="login-title">Login</div>
            <p class="login-subtitle">Securely access your payroll dashboard.</p>
        </div>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="input-ctrl" value="{{ old('email') }}" placeholder="admin@ashisauto.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="input-ctrl" placeholder="••••••••" required>
                    <span class="toggle-password" id="toggleText" onclick="togglePassword()">SHOW</span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Sign In
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        </form>

        <div class="footer-text">
            &copy; 2026 ASHIS Auto Solution
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleText = document.getElementById('toggleText');
            if (password.type === "password") {
                password.type = "text";
                toggleText.innerText = "HIDE";
            } else {
                password.type = "password";
                toggleText.innerText = "SHOW";
            }
        }
    </script>

</body>
</html>