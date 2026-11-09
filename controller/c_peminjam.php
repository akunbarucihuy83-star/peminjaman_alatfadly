<?php
session_start();

// Halaman yang menggunakan controller ini hanya untuk peminjam.
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../view/v_login.php');
    exit;
}

if (($_SESSION['role'] ?? '') !== 'peminjam') {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header('Location: ../view/dashboard.php');
    } elseif (($_SESSION['role'] ?? '') === 'petugas') {
        header('Location: ../view/v_dashboard_petugas.php');
    } else {
        header('Location: ../view/v_login.php');
    }
    exit;
}
?>
