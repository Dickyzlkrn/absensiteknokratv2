<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mahasiswa | SIPERMATA</title>

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
            background: linear-gradient(135deg, #4b0000, #5c1a1a, #3a0d0d);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        .login-container img {
            width: 80px;
            margin-bottom: 15px;
        }

        h2 {
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 5px;
            color: #333;
        }

        h6 {
            color: #777;
            font-weight: 400;
            margin-bottom: 25px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
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
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.2);
            outline: none;
        }

        .btn-primary {
            background-color: #800000;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #5a0000;
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

        .quote {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #555;
            font-style: italic;
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
    </style>
</head>

<body>
    <div class="login-container">
        <img src="{{ asset('assets/img/login/login.png') }}" alt="Login Icon">
        <h2>Login Mahasiswa</h2>
        <h6>Sistem Presensi Magang dan Catatan Harian</h6>

        {{-- Alert error login --}}
        @if ($errors->any())
            <div class="alert">
                {{ $errors->first('loginError') }}
            </div>
        @endif

        {{-- Form login --}}
        <form action="{{ route('login.mahasiswa') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="npm">NPM</label>
                <input type="text" id="npm" name="npm" placeholder="Masukkan NPM Anda" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="quote">Setiap Hari Berharga, Setiap Catatan Bermakna</p>
    </div>
</body>

</html>
