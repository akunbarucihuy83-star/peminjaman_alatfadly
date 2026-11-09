<?php

session_start();

require_once __DIR__ . "/../model/m_peminjaman.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../view/v_login.php");
    exit;
}

if (($_SESSION['role'] ?? '') !== 'peminjam') {
    header("Location: ../view/dashboard.php");
    exit;
}

if (isset($_POST['tambah_peminjaman'])) {

    $id_user = (int) ($_SESSION['id_user'] ?? 0);
    $id_alat = (int) ($_POST['id_alat'] ?? 0);
    $jumlah_pinjam = (int) ($_POST['jumlah_pinjam'] ?? 0);
    $tanggal_pinjam = $_POST['tanggal_pinjam'] ?? '';
    $tanggal_kembali = $_POST['tanggal_kembali'] ?? '';
    $jaminan = 'KTP';
    $keperluan = trim($_POST['keperluan'] ?? '');
    $catatan = trim($_POST['catatan'] ?? '');

    if ($id_user <= 0 || $id_alat <= 0 || $jumlah_pinjam <= 0 || $tanggal_pinjam === '' || $tanggal_kembali === '') {
        die("Data peminjaman belum lengkap.");
    }

    if ($tanggal_kembali < $tanggal_pinjam) {
        die("Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.");
    }

    $peminjaman = new Peminjaman();
    $peminjaman->tambah(
        $id_user,
        $id_alat,
        $jumlah_pinjam,
        $tanggal_pinjam,
        $tanggal_kembali,
        $jaminan,
        $keperluan,
        $catatan
    );

    header("Location: ../view/v_peminjaman_peminjam.php?pesan=berhasil");
    exit;
}

die("Aksi peminjaman tidak ditemukan.");
?>
