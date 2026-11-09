<?php
session_start();

// Jika sudah login, tidak perlu mendaftar lagi.
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header('Location: v_dashboard_peminjam.php');
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Inventaris</title>
    <link rel="stylesheet" href="../asset/login.css?v=register2">
</head>
<body>

<div class="login-container">
    <div class="login-box register-box">

        <div class="logo">
            <span>✦</span> Inventaris
        </div>

        <h2>Buat Akun</h2>
        <p class="subtitle">Daftar akun untuk mulai meminjam alat</p>

        <?php if ($error === 'username'): ?>
            <div class="form-message error">Username sudah digunakan. Silakan pilih username lain.</div>
        <?php elseif ($error === 'password'): ?>
            <div class="form-message error">Konfirmasi password tidak sama.</div>
        <?php elseif ($error === 'empty'): ?>
            <div class="form-message error">Semua data wajib diisi.</div>
        <?php elseif ($error === 'failed'): ?>
            <div class="form-message error">Pendaftaran gagal. Silakan coba lagi.</div>
        <?php endif; ?>

        <form action="../controller/c_register.php" method="POST">

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input id="nama" type="text" name="nama" placeholder="Masukkan nama lengkap" maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" placeholder="Buat username" maxlength="50" autocomplete="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Buat password" minlength="3" autocomplete="new-password" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <input id="password_confirm" type="password" name="password_confirm" placeholder="Ulangi password" minlength="3" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn-login">Daftar</button>
        </form>

        <p class="register-link">
            Sudah punya akun? <a href="v_login.php">Login di sini</a>
        </p>

        <p class="footer-text">Akun baru akan dibuat sebagai <strong>Peminjam</strong>.</p>
    </div>
</div>

</body>
</html>
