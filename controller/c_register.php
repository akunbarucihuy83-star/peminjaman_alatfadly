<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/v_register.php');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

if ($nama === '' || $username === '' || $password === '' || $password_confirm === '') {
    header('Location: ../view/v_register.php?error=empty');
    exit;
}

if ($password !== $password_confirm) {
    header('Location: ../view/v_register.php?error=password');
    exit;
}

require_once __DIR__ . '/../model/m_koneksi.php';

global $koneksi;
$db = $koneksi->koneksi;

// Username harus unik.
$stmt = mysqli_prepare($db, 'SELECT id_user FROM users WHERE username = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    header('Location: ../view/v_register.php?error=username');
    exit;
}
mysqli_stmt_close($stmt);

// Sistem pendaftaran publik hanya membuat akun PEMINJAM.
$role = 'peminjam';
$stmt = mysqli_prepare($db, 'INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssss', $nama, $username, $password, $role);
$success = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$success) {
    header('Location: ../view/v_register.php?error=failed');
    exit;
}

header('Location: ../view/v_login.php?register=success');
exit;
