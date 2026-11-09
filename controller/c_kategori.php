<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../view/v_dashboard_peminjam.php");
    exit;
}


include "../model/m_kategori.php";

$kategori = new Kategori();


// ==========================================
// TAMBAH KATEGORI
// ==========================================

if (isset($_POST['tambah_kategori'])) {

    $nama_kategori = $_POST['nama_kategori'] ?? '';

    if (trim($nama_kategori) == '') {
        die("Nama kategori belum diisi.");
    }

    $kategori->tambah($nama_kategori);

    header("Location: ../view/v_kategori.php");
    exit;
}


// ==========================================
// EDIT KATEGORI
// ==========================================

if (isset($_POST['edit_kategori'])) {

    $id_kategori = $_POST['id_kategori'] ?? 0;
    $nama_kategori = $_POST['nama_kategori'] ?? '';

    if ($id_kategori <= 0) {
        die("ID kategori tidak valid.");
    }

    if (trim($nama_kategori) == '') {
        die("Nama kategori belum diisi.");
    }

    $kategori->ubah(
        $id_kategori,
        $nama_kategori
    );

    header("Location: ../view/v_kategori.php");
    exit;
}


// ==========================================
// HAPUS KATEGORI
// ==========================================

if (isset($_GET['hapus_kategori'])) {

    $id_kategori = $_GET['hapus_kategori'];

    $kategori->hapus($id_kategori);

    header("Location: ../view/v_kategori.php");
    exit;
}


// ==========================================
// JIKA AKSI TIDAK DITEMUKAN
// ==========================================

die("Aksi kategori tidak ditemukan.");

?>