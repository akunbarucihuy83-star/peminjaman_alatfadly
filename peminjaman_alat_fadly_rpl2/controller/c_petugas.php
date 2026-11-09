<?php
session_start();

require_once __DIR__ . "/../model/m_peminjaman.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../view/v_login.php");
    exit;
}

// Admin dan Petugas sama-sama berhak menyetujui dan mengubah status pengembalian.
$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['admin', 'petugas'], true)) {
    header("Location: ../view/v_dashboard_peminjam.php");
    exit;
}

if (isset($_POST['aksi_peminjaman'])) {
    $id = (int) ($_POST['id_peminjaman'] ?? 0);
    $aksi = $_POST['aksi_peminjaman'] ?? '';

    if ($id <= 0 || !in_array($aksi, ['setujui', 'kembali'], true)) {
        header("Location: ../view/v_peminjaman.php?mode=persetujuan&pesan=aksi_gagal");
        exit;
    }

    $status = ($aksi === 'setujui') ? 'Disetujui' : 'Dikembalikan';

    $peminjaman = new Peminjaman();
    $berhasil = $peminjaman->updateStatus(
        $id,
        $status,
        (int) ($_SESSION['id_user'] ?? 0)
    );

    if (!$berhasil) {
        header("Location: ../view/v_peminjaman.php?mode=persetujuan&pesan=aksi_gagal");
        exit;
    }

    // Setelah Setujui maupun Kembali, tampilkan pemantauan pengembalian.
    header("Location: ../view/v_peminjaman.php?mode=pengembalian&pesan=berhasil");
    exit;
}

header("Location: ../view/v_peminjaman.php?mode=persetujuan&pesan=aksi_gagal");
exit;
?>
