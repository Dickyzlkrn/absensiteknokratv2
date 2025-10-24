<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | SIPERMATA</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --primary-color: #007bff; /* Biru Cerah */
            --primary-dark: #0056b3;  /* Biru Tua */
            --secondary-color: #3f51b5; /* Warna untuk gradien atau aksen */
            --text-dark: #212529;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --white: #ffffff;
            --red-alert: #dc3545;
        }

        * {
            box-sizing: border-box;
            /* Tambahkan transisi default untuk properti umum jika perlu, tapi fokus pada elemen spesifik lebih baik */
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, #e0f7fa, #c5cae9);
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-dark);
        }

        .login-container {
            background: var(--white);
            padding: 50px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); /* Kurva animasi yang lebih halus */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.8rem;
        }

        h6 {
            text-align: center;
            color: var(--text-muted);
            font-weight: 400;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-size: 0.9rem;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: block;
            font-weight: 600;
        }

        .input-wrapper {
            position: relative;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px 12px 40px;
            font-size: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            /* Transisi pada border dan background */
            transition: border-color 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        input:focus {
            border-color: var(--primary-color);
            background-color: var(--white);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); /* Shadow fokus yang lebih menonjol */
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.0rem;
            pointer-events: none;
            /* Transisi untuk warna ikon */
            transition: color 0.3s ease;
        }

        /* Ubah warna ikon saat input fokus */
        .input-wrapper:focus-within .input-icon {
            color: var(--primary-color);
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
            color: var(--text-muted);
            /* Transisi saat hover */
            transition: color 0.2s ease;
        }

        .show-password:hover {
            color: var(--primary-dark);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 14px;
            font-size: 1.05rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            /* Transisi untuk warna dan transform */
            transition: background-color 0.3s ease, transform 0.15s ease;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            /* Tambahkan box-shadow saat hover untuk efek "angkat" */
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-primary:active {
            /* Efek tekan yang lebih jelas dan smooth */
            transform: scale(0.98);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .alert {
            padding: 12px 20px;
            background: #ffe3e6;
            border: 1px solid var(--red-alert);
            color: var(--red-alert);
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login Admin</h2>
        <h6>SIPERMATA | Setiap Hari Berharga, Setiap Catatan Bermakna</h6>

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
                <label for="npm-input">NPM Admin</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="npm-input" name="npm" placeholder="Masukkan NPM admin" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password-input">Password</label>
                <div class="password-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password-input" name="password" placeholder="Masukkan password" required>
                    <i class="fas fa-eye show-password" onclick="togglePassword()"></i>
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i> Log In
            </button>
        </form>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password-input");
            const icon = document.querySelector(".show-password");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
