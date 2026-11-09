<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true || !in_array(($_SESSION['role'] ?? ''), ['admin', 'petugas'], true)) {
    if (($_SESSION['role'] ?? '') === 'peminjam') {
        header("Location: ../view/v_dashboard_peminjam.php");
    } else {
        header("Location: ../view/v_login.php");
    }
    exit;
}


include "../model/m_alat.php";

$alat = new Alat();


// ==================================================
// TAMBAH ALAT
// ==================================================

if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {

    $nama_alat = $_POST['nama_alat'] ?? '';
    $jumlah    = $_POST['jumlah'] ?? 0;
    $kondisi   = $_POST['kondisi'] ?? '';
    $id_kategori = (int) ($_POST['id_kategori'] ?? 0);

    // Cek data
    if (
        trim($nama_alat) == '' ||
        trim($jumlah) == '' ||
        trim($kondisi) == '' ||
        $id_kategori <= 0
    ) {
        die("Data alat belum lengkap.");
    }

    $hasil = $alat->tambah(
        $nama_alat,
        $jumlah,
        $kondisi,
        $id_kategori
    );

    if ($hasil) {

        header("Location: ../view/v_alat.php");
        exit;

    } else {

        die("Gagal menambahkan alat.");
    }
}


// ==================================================
// EDIT ALAT
// ==================================================

if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {

    $id_alat   = $_POST['id_alat'] ?? 0;
    $nama_alat = $_POST['nama_alat'] ?? '';
    $jumlah    = $_POST['jumlah'] ?? 0;
    $kondisi   = $_POST['kondisi'] ?? '';
    $id_kategori = (int) ($_POST['id_kategori'] ?? 0);

    // Cek data
    if (
        $id_alat <= 0 ||
        trim($nama_alat) == '' ||
        trim($jumlah) == '' ||
        trim($kondisi) == '' ||
        $id_kategori <= 0
    ) {
        die("Data edit alat belum lengkap.");
    }

    $hasil = $alat->edit(
        $id_alat,
        $nama_alat,
        $jumlah,
        $kondisi,
        $id_kategori
    );

    if ($hasil) {

        header("Location: ../view/v_alat.php");
        exit;

    } else {

        die("Gagal mengedit alat.");
    }
}


// ==================================================
// HAPUS ALAT
// ==================================================

if (isset($_GET['hapus'])) {

    $id_alat = (int) $_GET['hapus'];

    if ($id_alat <= 0) {
        die("ID alat tidak valid.");
    }

    $hasil = $alat->hapus($id_alat);

    if ($hasil) {

        header("Location: ../view/v_alat.php");
        exit;

    } else {

        die("Gagal menghapus alat.");
    }
}


// ==================================================
// JIKA TIDAK ADA AKSI
// ==================================================

die("Aksi alat tidak ditemukan.");

?>