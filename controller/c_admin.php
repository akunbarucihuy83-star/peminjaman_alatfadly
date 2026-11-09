<?php

include "../model/m_user.php";

$user = new User();


// =================================
// TAMBAH USER
// =================================

if (isset($_POST['tambah'])) {

    $nama = $_POST['nama'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';


    if (
        $nama === '' ||
        $username === '' ||
        $password === '' ||
        $role === ''
    ) {

        die("Data user belum lengkap.");

    }


    $hasil = $user->tambah(
        $nama,
        $username,
        $password,
        $role
    );


    if ($hasil) {

        header(
            "Location: ../view/v_user.php?pesan=tambah"
        );

        exit;

    } else {

        die(
            "Gagal menambahkan user: "
            . mysqli_error(
                $GLOBALS['koneksi']->koneksi
            )
        );

    }
}


// =================================
// EDIT USER
// =================================

if (isset($_POST['edit'])) {

    $id = $_POST['id_user'] ?? 0;

    $nama = $_POST['nama'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';


    if (
        $id == 0 ||
        $nama === '' ||
        $username === '' ||
        $role === ''
    ) {

        die("Data user belum lengkap.");

    }


    $hasil = $user->edit(
        $id,
        $nama,
        $username,
        $password,
        $role
    );


    if ($hasil) {

        header(
            "Location: ../view/v_user.php?pesan=edit"
        );

        exit;

    } else {

        die(
            "Gagal mengedit user: "
            . mysqli_error(
                $GLOBALS['koneksi']->koneksi
            )
        );

    }
}


// =================================
// HAPUS USER
// =================================

if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];


    if ($id <= 0) {

        die("ID user tidak valid.");

    }


    $hasil = $user->hapus($id);


    if ($hasil) {

        header(
            "Location: ../view/v_user.php?pesan=hapus"
        );

        exit;

    } else {

        die(
            "Gagal menghapus user: "
            . mysqli_error(
                $GLOBALS['koneksi']->koneksi
            )
        );

    }
}

?>