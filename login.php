<?php
session_start();

// 1. Koneksi ke database sesuai db_balaidesa
require_once 'koneksi.php';

// Cek koneksi database
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Cek apakah tombol login sudah diklik
if (isset($_POST['submit_login'])) {
    $login_input = mysqli_real_escape_string($koneksi, trim($_POST['email'] ?? ''));
    $password_input = $_POST['password'];

    // 2. Jalankan Query berdasarkan struktur tabel tb_user
    // Bisa login dengan username singkat (mis. admin), nama, atau email lengkap
    $query = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE LOWER(email) = LOWER('$login_input') OR LOWER(nama) = LOWER('$login_input') OR LOWER(SUBSTRING_INDEX(email, '@', 1)) = LOWER('$login_input')");

    // Cek apakah akun ditemukan
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);

        // 3. Cek Kecocokan Password (Teks biasa / Plain Text)
        if ($password_input === $row['password']) {

            // 4. SET SESSION LENGKAP (Dibaca oleh index.php dan profil.php)
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['profile_picture'] = $row['profile_picture'];
            $_SESSION['alamat'] = $row['alamat'];
            $_SESSION['telepon'] = $row['telepon'];

            $_SESSION['baru_login'] = true;

            // Alihkan langsung ke halaman dashboard utama (atau sesuaikan ke index.php?page=profil)
            header("Location: index.php");
            exit;
        }
    }
    // Jika salah, buat pesan error
    $error = "Username/Email atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - SIMDES - Sistem Informasi Management Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Favicon -->
    <link href="uplouds/logo.png" rel="icon">
    <style>
        :root {
            /* Mengubah tema warna dasar login menjadi Hijau sesuai branding SIMDES Anda */
            --primary: #007f3e;
            --primary-dark: #005c2d;
            --text: #0f172a;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;

            background:
                linear-gradient(rgba(0, 0, 0, 0.45),
                    rgba(0, 0, 0, 0.45)),
                url("uplouds/Gapura.jpeg");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-shell {
            width: 100%;
            max-width: 980px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 34px 30px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.3);
            transform: translateY(0);
            animation: fadeInUp 0.7s ease both;
        }

        .brand-badge {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), #239a58);
            color: white;
            font-size: 24px;
            box-shadow: 0 10px 24px rgba(0, 127, 62, 0.3);
        }

        .login-card h3 {
            color: #ffffff;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .login-subtitle {
            color: #e2e8f0;
            font-size: 0.95rem;
            margin-bottom: 20px;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #f8fafc;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            border-radius: 12px;
            height: 46px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 0 14px;
            transition: all 0.25s ease;
            background-color: rgba(255, 255, 255, 0.75);
            color: #0f172a;
        }

        .form-control:focus {
            border-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .btn-login {
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: .5px;
            transition: all .3s ease;
            box-shadow: 0 10px 22px rgba(0, 127, 62, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(0, 127, 62, 0.4);
        }

        .alert-danger {
            border-radius: 10px;
            font-size: 0.92rem;
            border: none;
            color: #7f1d1d;
            background: rgba(254, 226, 226, 0.95);
        }

        .login-footer {
            font-size: 0.82rem;
            color: #e2e8f0;
            margin-top: 18px;
            text-align: center;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Input dengan icon */
        .input-icon {
            position: relative;
        }

        .input-icon .icon-left {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 16px;
            z-index: 10;
        }

        .input-icon .icon-right {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 16px;
            cursor: pointer;
            z-index: 10;
            transition: .3s;
        }

        .input-icon .icon-right:hover {
            color: var(--primary);
        }

        .input-custom {
            width: 100%;
            height: 50px;
            padding-left: 48px;
            padding-right: 48px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, .35);
            background: rgba(255, 255, 255, .90);
            transition: .3s;
        }

        .input-custom:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(0, 127, 62, .15);
        }

        .input-icon:focus-within .icon-left,
        .input-icon:focus-within .icon-right {
            color: var(--primary);
        }
    </style>
</head>

<body>

    <div class="login-shell">
        <div class="login-card">
            <div class="brand-badge">🔐</div>
            <h3 class="text-center">SIMDES</h3>
            <p class="text-center login-subtitle">Masuk untuk mengakses sistem informasi management desa</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger py-2"><?= $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Username / Email</label>
                    <div class="input-icon">
                        <i class="fas fa-user icon-left"></i>
                        <input type="text" name="email" class="form-control input-custom"
                            placeholder="Masukkan username atau email Anda" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock icon-left"></i>
                        <input type="password" name="password" id="password" class="form-control input-custom"
                            placeholder="Masukkan password" required>
                        <i class="fas fa-eye icon-right" id="togglePassword"></i>
                    </div>
                </div>
                <button type="submit" name="submit_login" class="btn btn-login w-100">Login</button>
            </form>

            <div class="login-footer">
                Sistem Informasi Management Desa
            </div>
        </div>
    </div>

    <script>
        const password = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function () {
            if (password.type === "password") {
                password.type = "text";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            }
        });
    </script>

</body>

</html>