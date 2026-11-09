<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: v_login.php");
    exit;
}
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: dashboard.php");
    exit;
}
?>

<?php

include "../controller/c_login.php";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori - Inventaris</title>

    <link rel="stylesheet" href="../asset/kategorii.css?v=20261005form">

</head>

<body>

    <div class="container">

        <div class="form-box">

            <h2>Tambah Kategori</h2>

            <form
                action="../controller/c_kategori.php"
                method="POST"
            >

                <div class="form-group">

                    <label for="nama_kategori">
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        id="nama_kategori"
                        name="nama_kategori"
                        placeholder="Masukkan nama kategori"
                        required
                    >

                </div>

                <div class="form-button">

                    <a
                        href="v_kategori.php"
                        class="btn-back"
                    >
                        Kembali
                    </a>

                    <button
                        type="submit"
                        name="tambah_kategori"
                        class="btn-save"
                    >
                        Tambah
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>