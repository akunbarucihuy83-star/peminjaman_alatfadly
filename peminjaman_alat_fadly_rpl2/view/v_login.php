<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventaris</title>

    <link rel="stylesheet" href="../asset/login.css?v=register2">
</head>

<body>

<div class="login-container">

    <div class="login-box">

        <div class="logo">
            <span>✦</span> Inventaris
        </div>

        <h2>Selamat Datang</h2>
        <p class="subtitle">Silakan login untuk melanjutkan</p>

        <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
            <div class="form-message success">Pendaftaran berhasil. Silakan login dengan akun baru.</div>
        <?php endif; ?>

        <!-- LOGIN DIKIRIM KE CONTROLLER -->
<form action="../controller/login.php" method="POST">

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan username"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>

        </form>

        <p class="register-link">
            Belum punya akun? <a href="v_register.php">Daftar sekarang</a>
        </p>

        <p class="footer-text">
            Sistem Informasi Inventaris
        </p>

    </div>

</div>

</body>
</html>