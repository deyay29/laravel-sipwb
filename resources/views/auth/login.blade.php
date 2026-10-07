<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Bandung Info Center</title>
    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Full screen background hero malam hari tanpa navbar */
       .login-hero-container {
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('{{ asset("images/beranda_admin.jpeg") }}') center/cover no-repeat;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    width: 100%;
}

        /* Card login transparan glassmorphism */
        .login-glass-box {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 30px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            color: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        /* Label input */
        .login-glass-box .input-group-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.9);
            text-align: left;
            margin-bottom: 4px;
            font-weight: 500;
            padding-left: 0.5rem;
        }

        /* Kolom input kapsul */
        .login-glass-box .form-control {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.6);
            color: #0f172a;
            border-radius: 50px;
            padding: 0.65rem 1.2rem;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .login-glass-box .form-control:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }

        /* Tombol Masuk */
        .btn-login-custom {
            background: #2563eb;
            border: none;
            border-radius: 50px;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 0.5rem;
            transition: background 0.3s;
        }

        .btn-login-custom:hover {
            background: #1d4ed8;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-hero-container">
    <div class="login-glass-box">
        <!-- Logo & Header -->
        <div class="mb-4">
           <img src="{{ asset('images/logo.png') }}" alt="Logo Bandung Info Center" style="max-height: 115px; width: auto; margin-bottom: 8px; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.2));">
            
            <div style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.8); font-weight: 600; letter-spacing: 1.5px; margin-bottom: 1rem;"></div>
            
            <!-- Badge Suhu Kapsul -->
            <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill" style="background: rgba(15, 23, 42, 0.45); border: 1px solid rgba(255, 255, 255, 0.3); font-size: 0.8rem; color: #ffffff; gap: 6px;">
                <span>☁️ 22°C Berawan</span>
            </div>
        </div>

        <!-- Form Login -->
        <form method="POST" action="#">
            @csrf
            
            <div class="text-start">
                <div class="input-group-label">Email</div>
                <input type="email" name="email" class="form-control" placeholder="admin01@gmail.com" required>
            </div>
            
            <div class="text-start">
                <div class="input-group-label">Password</div>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-login-custom">
                Masuk
            </button>
        </form>
    </div>
</div>

</body>
</html>