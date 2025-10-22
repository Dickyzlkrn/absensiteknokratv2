<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | SIPERMATA</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        h6 {
            text-align: center;
            color: #555;
            font-weight: normal;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 0.95rem;
            color: #444;
            margin-bottom: 6px;
            display: block;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        .password-wrapper {
            position: relative;
        }

        .show-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.2rem;
            color: #888;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 10px;
            background: #ffdddd;
            border: 1px solid #f5c6cb;
            color: #a94442;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login Admin SIPERMATA</h2>
        <h6>Setiap Hari Berharga, Setiap Catatan Bermakna</h6>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="alert">
                {{ $errors->first('loginError') }}
            </div>
        @endif

        {{-- Login Form --}}
        <form action="{{ route('login.admin') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>NPM Admin</label>
                <input type="text" name="npm" placeholder="Masukkan NPM admin" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-primary">Log in</button>
        </form>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.querySelector(".show-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.textContent = "🙈";
            } else {
                passwordInput.type = "password";
                icon.textContent = "👁️";
            }
        }
    </script>
</body>

</html>
